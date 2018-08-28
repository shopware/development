FROM webdevops/php-apache:7.2

COPY php-config.ini /usr/local/etc/php/conf.d/
COPY wait-for-it.sh /usr/local/bin/
COPY addExternalUser.sh /addExternalUser

ENV COMPOSER_CACHE_DIR=/.composer/cache
ENV WEB_DOCUMENT_ROOT=/app/public

RUN sed -ri -e 's!VirtualHost \*:80!VirtualHost \*:8000!g' /opt/docker/etc/httpd/vhost.conf \
    && echo "Listen 8000" | tee -a /etc/apache2/ports.conf \

    # Install Google Chrome
    && curl -sL https://dl-ssl.google.com/linux/linux_signing_key.pub | apt-key add - \
    && sh -c 'echo "deb [arch=amd64] http://dl.google.com/linux/chrome/deb/ stable main" >> /etc/apt/sources.list.d/google.list' \

    # https://bugs.debian.org/cgi-bin/bugreport.cgi?bug=863199
    && mkdir -p /usr/share/man/man1 \
    && curl -sL https://deb.nodesource.com/setup_10.x | bash \

    && apt-install mysql-client nodejs google-chrome-stable libicu-dev graphviz vim \
    && npm i npm -g \

    # intl support
    && docker-php-ext-configure intl \
    && docker-php-ext-install intl \

    && chmod +x /usr/local/bin/wait-for-it.sh \
    && chmod +x /addExternalUser

WORKDIR /app
