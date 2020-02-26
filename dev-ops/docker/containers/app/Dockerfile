FROM webdevops/php-apache:7.2

COPY wait-for-it.sh /usr/local/bin/

ENV COMPOSER_HOME=/.composer
ENV NPM_CONFIG_CACHE=/.npm
ENV WEB_DOCUMENT_ROOT=/app/public
ARG USER_ID
ARG GROUP_ID

RUN groupadd -r -g ${GROUP_ID} appuser || true
RUN useradd -r -u ${USER_ID} -g ${GROUP_ID} appuser || true

RUN sed -ri -e 's!VirtualHost \*:80!VirtualHost \*:8000!g' /opt/docker/etc/httpd/vhost.conf \
    && echo "Listen 8000" | tee -a /etc/apache2/ports.conf \
    \
    # Install Google Chrome
    && curl -sL https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \
    \
    # Install docker
    && curl -fsSL https://download.docker.com/linux/debian/gpg | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] https://download.docker.com/linux/debian stretch stable" >> /etc/apt/sources.list.d/docker.list' \
    \
    # https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=863199
    && mkdir -p /usr/share/man/man1 \
    && curl -sL https://deb.nodesource.com/setup_12.x | bash \
    \
    && mkdir -p ${NPM_CONFIG_CACHE} \
    && apt-install default-mysql-client nodejs google-chrome-stable libicu-dev graphviz vim gnupg2 docker-ce=5:18.09.7~3-0~debian-stretch \
    && npm i npm -g \
    && npm i forever -g \
    && chown -R ${USER_ID}:${GROUP_ID} ${NPM_CONFIG_CACHE} \
    \
    && ln -s /app/psh.phar /bin/psh \
    \
    && pecl install pcov-1.0.6 \
    && docker-php-ext-enable pcov \
    && pecl install xdebug-2.9.2 \
    && docker-php-ext-enable xdebug \
    && composer global require hirak/prestissimo

COPY php-config.ini /usr/local/etc/php/conf.d/99-docker.ini

WORKDIR /app

