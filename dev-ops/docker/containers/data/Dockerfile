FROM robertdebock/docker-centos-openssh

RUN echo 'root:nexus123' | chpasswd \
 && groupdel tape \
 && groupadd -g 33 www-data \
 && usermod -g www-data root

WORKDIR /app
