# Accepted values: 8.3 - 8.2
ARG PHP_VERSION=8.3

ARG FRANKENPHP_VERSION=latest

ARG COMPOSER_VERSION=latest

ARG NODE_VERSION="current"

ARG BUN_VERSION="latest"

FROM node:${NODE_VERSION}-slim AS node

FROM oven/bun:${BUN_VERSION} AS bun

FROM composer:${COMPOSER_VERSION} AS vendor

FROM dunglas/frankenphp:${FRANKENPHP_VERSION}-php${PHP_VERSION}

ARG WWWUSER=1000
ARG WWWGROUP=1000
ARG TZ=UTC
ARG APP_DIR=/var/www/html
ARG WITH_HORIZON=false
ARG WITH_SCHEDULER=false

ENV DEBIAN_FRONTEND=noninteractive \
    TERM=xterm-color \
    WITH_HORIZON=${WITH_HORIZON} \
    WITH_SCHEDULER=${WITH_SCHEDULER} \
    OCTANE_SERVER=frankenphp \
    USER=octane \
    ROOT=${APP_DIR} \
    COMPOSER_FUND=0 \
    COMPOSER_MAX_PARALLEL_HTTP=24 \
    XDG_CONFIG_HOME=${APP_DIR}/.config \
    XDG_DATA_HOME=${APP_DIR}/.data

WORKDIR ${ROOT}

SHELL ["/bin/bash", "-eou", "pipefail", "-c"]

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

RUN apt-get update; \
    apt-get upgrade -yqq; \
    apt-get install -yqq --no-install-recommends --show-progress \
    apt-utils \
    curl \
    wget \
    vim \
    git \
    ncdu \
    procps \
    ca-certificates \
    supervisor \
    libsodium-dev \
    # Custom packages
    nano unzip gnupg software-properties-common \
    ffmpeg automake build-essential libasound2-dev libpulse-dev lame \
    jpegoptim optipng pngquant gifsicle webp libavif-bin \
    # Chrome
    fonts-liberation libasound2 libatk-bridge2.0-0 libatk1.0-0 libatspi2.0-0 \
    libcups2 libdbus-1-3 libdrm2 libgbm1 libgtk-3-0 libnspr4 libnss3 libwayland-client0 \
    libxcomposite1 libxdamage1 libxfixes3 libxkbcommon0 libxrandr2 xdg-utils libu2f-udev libvulkan1 \
    # Install PHP extensions (included with dunglas/frankenphp)
    && install-php-extensions \
    bz2 \
    pcntl \
    mbstring \
    bcmath \
    sockets \
    pgsql \
    pdo_pgsql \
    opcache \
    exif \
    pdo_mysql \
    zip \
    intl \
    gd \
    redis \
    rdkafka \
    memcached \
    igbinary \
    ldap

RUN arch="$(uname -m)" \
    && case "$arch" in \
    armhf) _cronic_fname='supercronic-linux-arm' ;; \
    aarch64) _cronic_fname='supercronic-linux-arm64' ;; \
    x86_64) _cronic_fname='supercronic-linux-amd64' ;; \
    x86) _cronic_fname='supercronic-linux-386' ;; \
    *) echo >&2 "error: unsupported architecture: $arch"; exit 1 ;; \
    esac \
    && wget -q "https://github.com/aptible/supercronic/releases/download/v0.2.29/${_cronic_fname}" \
    -O /usr/bin/supercronic \
    && chmod +x /usr/bin/supercronic \
    && mkdir -p /etc/supercronic \
    && echo "*/1 * * * * php ${ROOT}/artisan schedule:run --no-interaction" > /etc/supercronic/laravel

RUN userdel --remove --force www-data \
    && groupadd --force -g ${WWWGROUP} ${USER} \
    && useradd -ms /bin/bash --no-log-init --no-user-group -g ${WWWGROUP} -u ${WWWUSER} ${USER}

RUN chown -R ${USER}:${USER} ${ROOT} /var/{log,run} \
    && chmod -R a+rw ${ROOT} /var/{log,run}

RUN cp ${PHP_INI_DIR}/php.ini-production ${PHP_INI_DIR}/php.ini

###########################################
# Custom stuff
##########################################

# Youtube DLP
RUN curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp && \
    chmod a+rwx /usr/local/bin/yt-dlp

## Dectalk
RUN cd /home/${USER} && \
    git clone https://github.com/dectalk/dectalk.git && \
    cd dectalk/src && \
    autoreconf -si && \
    ./configure && \
    make -j && \
    cd ../../ && \
    chown -R ${USER}:${USER} dectalk && \
    ln -s /home/${USER}/dectalk/dist/say /usr/local/bin/dectalk

# Chrome
RUN curl https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb --output chrome.deb && \
    apt-get install -y ./chrome.deb && \
    rm chrome.deb

# Chromedriver
RUN CHROMEDRIVER_VERSION=$(curl https://googlechromelabs.github.io/chrome-for-testing/LATEST_RELEASE_STABLE) && \
    curl -L https://storage.googleapis.com/chrome-for-testing-public/$CHROMEDRIVER_VERSION/linux64/chromedriver-linux64.zip --output chromedriver-linux64.zip && \
    unzip chromedriver-linux64.zip && \
    rm chromedriver-linux64.zip && \
    chmod +x chromedriver-linux64/chromedriver && \
    mv chromedriver-linux64/chromedriver /usr/local/bin && \
    rm -rf chromedriver-linux64

##########################################

RUN apt-get -y autoremove \
    && apt-get clean \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

USER ${USER}

RUN mkdir -p \
    storage/framework/{sessions,views,cache,testing} \
    storage/logs \
    bootstrap/cache && chmod -R a+rw storage

COPY --link --chown=${USER}:${USER} --from=vendor /usr/bin/composer /usr/bin/composer
COPY --link --chown=${USER}:${USER} --from=bun /usr/local/bin/bun /usr/local/bin/bun
COPY --link --chown=${USER}:${USER} --from=node /usr/local/bin /usr/local/bin
COPY --link --chown=${USER}:${USER} --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --link --chown=${USER}:${USER} ./supervisord.conf /etc/supervisor/
COPY --link --chown=${USER}:${USER} ./octane/FrankenPHP/supervisord.frankenphp.conf /etc/supervisor/conf.d/
COPY --link --chown=${USER}:${USER} ./supervisord.*.conf /etc/supervisor/conf.d/
COPY --link --chown=${USER}:${USER} ./start-container /usr/local/bin/start-container
COPY --link --chown=${USER}:${USER} ./healthcheck /usr/local/bin/healthcheck
COPY --link --chown=${USER}:${USER} ./php.ini ${PHP_INI_DIR}/conf.d/99-octane.ini
COPY --link --chown=${USER}:${USER} ./utilities.sh /tmp/utilities.sh

# FrankenPHP embedded PHP configuration
COPY --link --chown=${USER}:${USER} ./php.ini /lib/php.ini

RUN chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck

RUN cat /tmp/utilities.sh >> ~/.bashrc

EXPOSE 8000
EXPOSE 443
EXPOSE 443/udp
EXPOSE 2019

ENTRYPOINT ["start-container"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD healthcheck || exit 1