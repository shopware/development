#!/usr/bin/env node

const fs = require('fs');
const path = require('path');
const complexity = require('typhonjs-escomplex');


const extractJsFiles = function(directory) {
    const files = fs.readdirSync(directory, {withFileTypes: true});

    return files
        .filter(function(dirent) {
            return !dirent.isDirectory();
        })
        .filter(function(dirent) {
            return '.js' === path.extname(dirent.name);
        })
        .map(function(dirent) {
            return dirent.name;
        })
        .map(function(name) {
            return directory + '/' + name;
        });
};

const extractDirectories = function(directory) {
    const files = fs.readdirSync(directory, {withFileTypes: true});

    return files
        .filter((dirent) => {
            return dirent.name !== 'node_modules';
        })
        .filter(function(dirent) {
            return dirent.isDirectory();
        })
        .map(function(dirent) {
            return directory + '/'  + dirent.name;
        });
};

const recurseDirectories = function (directory) {
    let list = [];

    extractDirectories(directory).forEach((directory) => {
        list.push([directory]);
        list.push(recurseDirectories(directory));
    });

    return [].concat.apply([], list);

    return extractDirectories(directory)
        .map(function(directory) {
            return extractJsFiles(directory);
        });
};



const files= [].concat.apply([],
    recurseDirectories('/app/vendor/shopware/platform/src/Administration/Resources/administration')
        .map((directory) => {
            return extractJsFiles(directory);
        })
    );

const reports = files
    .map((file) => {
        const contents = fs.readFileSync(file, 'utf8');

        return [file, complexity.analyzeModule(contents)];
    });

let flatReports = {};

reports.forEach((item) => {
    flatReports[item[0]] = item[1];
});

fs.writeFileSync('/app/build/artifacts/admin-complexity.json', JSON.stringify(flatReports, null, 4));

