FROM graze/stats:7.0

RUN apk add --no-cache --repository "http://dl-cdn.alpinelinux.org/alpine/edge/testing" \
    php7-mbstring \
    php7-xdebug

ADD . /opt/graze/data-node

WORKDIR /opt/graze/data-node

CMD /bin/bash
