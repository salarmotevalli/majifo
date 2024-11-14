FROM ubuntu:22.04

ARG WWWGROUP

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND=noninteractive
ENV TZ=UTC
ENV SUPERVISOR_PHP_COMMAND="symfony local:server:start"

COPY . .

RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch ffmpeg nano  \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.3-cli php8.3-dev php8.3-pdo php8.3-pdo-pgsql \
    php8.3-curl \
    php8.3-imap php8.3-pgsql php8.3-mbstring \
    php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
    php8.3-intl php8.3-readline \
    php8.3-ldap \
    php8.3-msgpack php8.3-igbinary \
    php8.3-pcov php8.3-xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && apt-get update \
    && apt-get update \
    && apt-get -y autoremove \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN groupadd --force -g 1000 symfony
RUN useradd -ms /bin/bash --no-user-group -g 1000 -u 1337 symfony

COPY ./docker/start-container /usr/local/bin/start-container
COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN chmod +x /usr/local/bin/start-container

EXPOSE 80/tcp

ENTRYPOINT start-container
