FROM php:5.6-cli

RUN docker-php-ext-install mbstring && \
    curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

ADD . /opt/graze/dataFlow

WORKDIR /opt/graze/dataFlow

CMD /bin/bash