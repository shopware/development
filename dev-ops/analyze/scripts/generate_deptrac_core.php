<?php

require_once __DIR__ . '/../vendor/autoload.php';

$platformPath = $argv[1];


class Renderer {
    const HEAD_TEMPLATE = <<<EOD
paths:
  - %s
exclude_files:
  - .*test.*
  - .*Definition.php
  - .*Struct.php
  - .*Entity.php
  - .*Collection.php
  - .*Event.php
  - .*DemodataCommand.php

layers:

EOD;

    const LAYER_TEMPLATE = <<<EOD
  - name: %s
    collectors:
      - type: bool
        must:
          - type: className
            regex: %s

EOD;

    public static function do(\Symfony\Component\Finder\SplFileInfo $contextDirectory, array $bundleLayers, string $rootName = null, string $rootNamespace = null): string
    {
        $depfile = sprintf(self::HEAD_TEMPLATE, $contextDirectory->getRealPath());

        foreach ($bundleLayers as $name => $classPrefix) {
            $depfile .= sprintf(self::LAYER_TEMPLATE, $name, $classPrefix);
        }

        if($rootName) {
            $depfile .= <<<EOD
  - name: "ROOT:$rootName"
    collectors:
      - type: bool
        must:
          - type: className
            regex: $rootNamespace
        must_not:

EOD;


            foreach ($bundleLayers as $classPrefix) {
                $depfile .= <<<EOD
          - type: className
            regex: $classPrefix

EOD;
            }
        }

        $depfile .= "ruleset:\n";
        $layerMap = array_keys($bundleLayers);

        if($rootName) {
            $layerMap[] = '"ROOT:' . $rootName . '"';
        }

        foreach ($layerMap as $name) {
            $depfile .= "  $name:\n";

            foreach($layerMap as $allowedName) {
                $depfile .= "    - $allowedName\n";
            }
        }

        return $depfile;
    }
}

class Generate {

    const NAMESPACE_PREFIX = 'Shopware\\\\Core';

    const EXCLUDE_DIRS = ['Flag', 'Migration', 'Resources', 'DependencyInjection', 'Test'];


    private static function getBundleDirectories(): \Symfony\Component\Finder\Finder
    {
        global $platformPath;

        return (new \Symfony\Component\Finder\Finder())
            ->directories()
            ->in($platformPath . '/src/Core')
            ->exclude(self::EXCLUDE_DIRS)
            ->depth('== 1');
    }

    public static function generateExternalDepFiles(): void
    {
        $layers = [];

        foreach(self::getBundleDirectories() as $contextDirectory) {
            $rootName = str_replace('/', '\\', $contextDirectory->getRelativePathname());

            $layers[$rootName] = self::NAMESPACE_PREFIX . '\\\\' . str_replace('/', '\\\\', $contextDirectory->getRelativePathname());
        }

        foreach(self::getBundleDirectories() as $contextDirectory) {
            $depfileContent = Renderer::do($contextDirectory, $layers);

            self::dump($depfileContent, 'external', $contextDirectory->getRelativePathname());
        }

    }

    public static function generateInternalDepFiles(): void {
        foreach(self::getBundleDirectories() as $contextDirectory) {
            $rootName = str_replace('/', '\\\\', $contextDirectory->getRelativePathname());

            $bundleDirectories = (new \Symfony\Component\Finder\Finder())
                ->directories()
                ->in($contextDirectory->getRealPath())
                ->depth('== 0');

            $bundleLayers = [];
            foreach ($bundleDirectories as $bundleDirectory) {
                $name = str_replace('/', '\\', $contextDirectory->getRelativePathname())
                    . '\\' . $bundleDirectory->getRelativePathname();


                $bundleLayers[$name] = self::NAMESPACE_PREFIX
                    . '\\\\' . str_replace('/', '\\\\', $contextDirectory->getRelativePathname())
                    . '\\\\' . $bundleDirectory->getRelativePathname();
            }

            $depfileContents = Renderer::do($contextDirectory, $bundleLayers, $rootName, self::NAMESPACE_PREFIX . '\\\\' . $rootName);

            self::dump($depfileContents, 'internal', $contextDirectory->getRelativePathname());
        }
    }

    private static function dump(string $depfileContents, string $type, string $rawName)
    {
        $depfileName = 'depfile.domain.core.bundle.'
            . $type
            . '.'
            . strtolower(str_replace('/', '.', $rawName));

        $depfilePath = __DIR__
            . '/../tmp/depfiles/'
            . $depfileName
            . '.yml';

        file_put_contents(
            $depfilePath,
            $depfileContents
        );
    }
}
Generate::generateExternalDepFiles();
Generate::generateInternalDepFiles();

echo "\n DONE \n";
