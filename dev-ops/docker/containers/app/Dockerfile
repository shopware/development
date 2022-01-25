ARG IMAGE_PHP=webdevops/php-apache
ARG IMAGE_PHP_VERSION=7.4

FROM ${IMAGE_PHP}:${IMAGE_PHP_VERSION}

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
    && apt-install default-mysql-client nodejs google-chrome-stable libicu-dev graphviz vim gnupg2 docker-ce=5:18.09.7~3-0~debian-stretch libgtk2.0-0 libgtk-3-0 libgbm-dev libnotify-dev libgconf-2-4 libnss3 libxss1 libasound2 libxtst6 xauth xvfb jq \
    \
    && npm install -g npm@^6.14.11 \
    && npm i forever -g \
    && chown -R ${USER_ID}:${GROUP_ID} ${NPM_CONFIG_CACHE} \
    \
    && ln -s /app/psh.phar /bin/psh \
    \
    && pecl install pcov \
    && docker-php-ext-enable pcov

COPY php-config.ini /usr/local/etc/php/conf.d/99-docker.ini

WORKDIR /app
