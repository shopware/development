FROM app-base

RUN rm /usr/local/bin/composer \
    && ln -s /usr/local/bin/composer1 /usr/local/bin/composer \
    && composer global require hirak/prestissimo
