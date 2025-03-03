ARG PHP_VERSION=8.4
ARG COMPOSER_VERSION=2.8
ARG BUN_VERSION=1.2.4

FROM composer:${COMPOSER_VERSION} AS vendor

FROM php:${PHP_VERSION}-cli-bookworm AS base

ARG WWWUSER=42080
ARG WWWGROUP=42080
ARG TZ=UTC

ENV DEBIAN_FRONTEND=noninteractive \
    TERM=xterm-color \
    OCTANE_SERVER=swoole \
    TZ=${TZ} \
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
    libbrotli-dev \
    # Custom packages
    nano unzip gnupg software-properties-common rsync \
    ffmpeg automake build-essential libasound2-dev libpulse-dev lame \
    jpegoptim optipng pngquant gifsicle webp libavif-bin \
    # Stuff for building with Byond
    && dpkg --add-architecture i386 \
    && apt-get update \
    && apt-get install -yqq --no-install-recommends --show-progress \
    gcc-multilib lib32stdc++6 zlib1g-dev:i386 libssl-dev:i386 pkg-config:i386 libstdc++6 libstdc++6:i386

# Install PHP extensions
RUN install-php-extensions \
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
    uv \
    vips \
    excimer

RUN apt-get -y autoremove \
    && apt-get clean \
    && docker-php-source delete \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* \
    && rm /var/log/lastlog /var/log/faillog

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
# Custom root packages
##########################################

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

###########################################
# Custom user packages
##########################################

USER ${USER}

# Install Cargo for building Rust-G
RUN curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y && \
    ~/.cargo/bin/rustup target add i686-unknown-linux-gnu && \
    chmod -R 770 ~/.cargo

# Node via NVM
ENV NVM_DIR=/home/${USER}/.nvm
RUN curl https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.1/install.sh | bash && \
    . $NVM_DIR/nvm.sh && \
    nvm install node && \
    nvm use default && \
    npm install -g npm

##########################################

COPY --link --chown=${WWWUSER}:${WWWGROUP} --from=vendor /usr/bin/composer /usr/bin/composer

COPY --link --chown=${WWWUSER}:${WWWGROUP} deploy/supervisord.conf /etc/
COPY --link --chown=${WWWUSER}:${WWWGROUP} deploy/supervisord.app.conf /etc/supervisor/conf.d/
COPY --link --chown=${WWWUSER}:${WWWGROUP} deploy/php.ini ${PHP_INI_DIR}/conf.d/99-octane.ini
COPY --link --chown=${WWWUSER}:${WWWGROUP} deploy/start-container /usr/local/bin/start-container
COPY --link --chown=${WWWUSER}:${WWWGROUP} deploy/healthcheck /usr/local/bin/healthcheck

RUN chmod +x /usr/local/bin/start-container /usr/local/bin/healthcheck

###########################################

FROM base AS common

USER ${USER}

COPY --link --chown=${WWWUSER}:${WWWGROUP} . .

RUN composer install \
    --no-dev \
    --no-interaction \
    --no-autoloader \
    --no-ansi \
    --no-scripts \
    --audit

###########################################
# Build frontend assets with Bun
###########################################

FROM oven/bun:${BUN_VERSION} AS build

ENV ROOT=/var/www/html \
    NODE_ENV=production

WORKDIR ${ROOT}

COPY --link package.json bun.lock* ./

RUN bun install --frozen-lockfile

COPY --link . .
COPY --link --from=common ${ROOT}/vendor vendor

RUN bun run build

###########################################

FROM common AS runner

WORKDIR ${ROOT}

USER ${USER}

COPY --link --chown=${WWWUSER}:${WWWGROUP} . .
COPY --link --chown=${WWWUSER}:${WWWGROUP} --from=build ${ROOT}/public public

RUN mkdir -p \
    storage/framework/{sessions,views,cache,testing} \
    storage/logs \
    bootstrap/cache && chmod -R a+rw storage

RUN composer install \
    --classmap-authoritative \
    --no-interaction \
    --no-ansi \
    --no-dev \
    && composer clear-cache

EXPOSE 8000
EXPOSE 8080

VOLUME ["storage", "public"]

ENTRYPOINT ["start-container"]

HEALTHCHECK --start-period=5s --interval=2s --timeout=5s --retries=8 CMD healthcheck || exit 1
