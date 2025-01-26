# Accepted values: 8.3 - 8.2
ARG PHP_VERSION=8.3

ARG COMPOSER_VERSION=latest

ARG NODE_VERSION="current"

FROM node:${NODE_VERSION}-slim AS node

FROM composer:${COMPOSER_VERSION} AS vendor

FROM php:${PHP_VERSION}-cli-bookworm

ARG WWWUSER=1000
ARG WWWGROUP=1000
ARG TZ=UTC
ARG WITH_HORIZON=false
ARG WITH_SCHEDULER=false

ENV DEBIAN_FRONTEND=noninteractive \
    TERM=xterm-color \
    WITH_HORIZON=${WITH_HORIZON} \
    WITH_SCHEDULER=${WITH_SCHEDULER} \
    OCTANE_SERVER=swoole \
    USER=octane \
    ROOT=/var/www/html \
    COMPOSER_FUND=0 \
    COMPOSER_MAX_PARALLEL_HTTP=24

WORKDIR ${ROOT}

SHELL ["/bin/bash", "-eou", "pipefail", "-c"]

RUN ln -snf /usr/share/zoneinfo/${TZ} /etc/localtime \
    && echo ${TZ} > /etc/timezone

ADD --chmod=0755 https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

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
    # Install PHP extensions
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
    ldap \
    swoole \
    uv

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

# Node via NVM
USER ${USER}
SHELL ["/bin/bash", "--login", "-c"]
ENV NVM_DIR /home/${USER}/.nvm
RUN curl https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash && \
    . $NVM_DIR/nvm.sh && \
    nvm install --default $NODE_VERSION && \
    nvm use default && \
    npm install -g npm bun
USER root

# Youtube DLP
RUN curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp && \
    chmod a+rwx /usr/local/bin/yt-dlp

## Dectalk
RUN cd /usr/local/src && \
    curl -fsSL https://github.com/dectalk/dectalk/releases/download/2023-10-30/ubuntu-latest.tar.gz -o dectalk.tar.gz && \
    mkdir dectalk && \
    tar -xzf dectalk.tar.gz -C dectalk/ && \
    rm dectalk.tar.gz && \
    chmod -R +x dectalk && \
    ln -s /usr/local/src/dectalk/say /usr/local/bin/dectalk

# Chrome
RUN curl -fsSL https://dl.google.com/linux/direct/google-chrome-stable_current_amd64.deb -o chrome.deb && \
    apt-get install -y ./chrome.deb && \
    rm chrome.deb

# Chromedriver
RUN CHROMEDRIVER_VERSION=$(curl https://googlechromelabs.github.io/chrome-for-testing/LATEST_RELEASE_STABLE) && \
    curl -fsSL https://storage.googleapis.com/chrome-for-testing-public/$CHROMEDRIVER_VERSION/linux64/chromedriver-linux64.zip -o chromedriver-linux64.zip && \
    unzip chromedriver-linux64.zip && \
    rm chromedriver-linux64.zip && \
    chmod +x chromedriver-linux64/chromedriver && \
    mv chromedriver-linux64/chromedriver /usr/local/bin

# Packages for compiling the game with Byond
RUN dpkg --add-architecture i386 && \
    apt-get update && \
    apt-get install -y rsync gcc-multilib lib32stdc++6 zlib1g-dev:i386 libssl-dev:i386 pkg-config:i386 libstdc++6 libstdc++6:i386

# Install Cargo for building Rust-G
USER ${USER}
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y && \
    ~/.cargo/bin/rustup target add i686-unknown-linux-gnu && \
    chmod -R 770 ~/.cargo
USER root

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
COPY --link --chown=${USER}:${USER} ./supervisord.conf /etc/supervisor/
COPY --link --chown=${USER}:${USER} ./octane/Swoole/supervisord.swoole.conf /etc/supervisor/conf.d/
COPY --link --chown=${USER}:${USER} ./supervisord.*.conf /etc/supervisor/conf.d/
COPY --link --chown=${USER}:${USER} ./php.ini ${PHP_INI_DIR}/conf.d/99-octane.ini
COPY --link --chown=${USER}:${USER} ./start-container /usr/local/bin/start-container
COPY --link --chown=${USER}:${USER} ./healthcheck /usr/local/bin/healthcheck
COPY --link --chown=${USER}:${USER} ./utilities.sh /tmp/utilities.sh

RUN chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck

RUN cat /tmp/utilities.sh >> ~/.bashrc

EXPOSE 8000

ENTRYPOINT ["start-container"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD healthcheck || exit 1
