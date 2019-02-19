FROM mariadb:10.3

# custom dirs for mysql shared docker environments
RUN mkdir -p /mysql-tmp && mkdir -p /mysql-data
RUN chown mysql:mysql /mysql-tmp /mysql-data

# copy mysql config
ADD dev.cnf /etc/mysql/conf.d/dev.cnf
ADD remote-access.cnf /etc/mysql/conf.d/remote-access.cnf

COPY grant.sql /docker-entrypoint-initdb.d/grant.sql
