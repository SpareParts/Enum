FROM phpstorm/php-71-cli-xdebug
WORKDIR /app

RUN \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" &&\
    php composer-setup.php --install-dir=/bin --filename=composer &&\
    php -r "unlink('composer-setup.php');"

RUN \
    apt-get update &&\
    apt-get install -y --force-yes vim curl git

COPY . /app

ENTRYPOINT bash