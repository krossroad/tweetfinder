FROM ubuntu:16.04
ENV LANG C.UTF-8
ENV DEBIAN_FRONTEND noninteractive

RUN apt-get update && apt-get -y upgrade && \
    apt-get -y install -q supervisor \
                       nginx \
                       unzip \
                       git \
                       curl \
                       php7.0-fpm \
                       php7.0-cli \
                       php7.0-gd \
                       php7.0-imap \
                       php7.0-intl \
                       php7.0-json \
                       php7.0-mcrypt \
                       php7.0-mysql \
                       php7.0-pgsql \
                       php7.0-mbstring \
                       php7.0-ldap \
                       php7.0-zip \
                       php7.0-xml \
                       php7.0-curl && \
                       apt-get clean

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && chmod +x /usr/local/bin/composer

RUN curl -sL https://deb.nodesource.com/setup_6.x -o nodesource_setup.sh && \
     bash nodesource_setup.sh && \
     apt-get install nodejs && \
     rm nodesource_setup.sh

WORKDIR /var/www
#Add confs
ADD conf/www.conf /etc/php/7.0/fpm/pool.d/www.conf
ADD conf/supervisor.conf /etc/supervisord.conf
ADD conf/nginx.conf /etc/nginx/nginx.conf

RUN mkdir /var/run/php/
RUN useradd -u 1000 1000

ENTRYPOINT ["/usr/bin/supervisord", "-n", "-c",  "/etc/supervisord.conf"]
