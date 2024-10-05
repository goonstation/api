FROM ubuntu:22.04

LABEL maintainer="Taylor Otwell"

ARG WWWGROUP
ARG NODE_VERSION=20
ARG POSTGRES_VERSION=15

WORKDIR /var/www/html

ENV DEBIAN_FRONTEND noninteractive
ENV TZ=UTC
ENV SUPERVISOR_PHP_COMMAND="/usr/bin/php -d variables_order=EGPCS /var/www/html/artisan serve --host=0.0.0.0 --port=80"
ENV SUPERVISOR_PHP_USER="sail"

RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apt-get update \
    && mkdir -p /etc/apt/keyrings \
    && apt-get install -y gnupg gosu curl ca-certificates zip unzip git supervisor sqlite3 libcap2-bin libpng-dev python2 dnsutils librsvg2-bin fswatch ffmpeg nano  \
    && curl -sS 'https://keyserver.ubuntu.com/pks/lookup?op=get&search=0x14aa40ec0831756756d7f66c4f4ea0aae5267a6c' | gpg --dearmor | tee /etc/apt/keyrings/ppa_ondrej_php.gpg > /dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/ppa_ondrej_php.gpg] https://ppa.launchpadcontent.net/ondrej/php/ubuntu jammy main" > /etc/apt/sources.list.d/ppa_ondrej_php.list \
    && apt-get update \
    && apt-get install -y php8.3-cli php8.3-dev \
       php8.3-pgsql php8.3-sqlite3 php8.3-gd \
       php8.3-curl \
       php8.3-imap php8.3-mysql php8.3-mbstring \
       php8.3-xml php8.3-zip php8.3-bcmath php8.3-soap \
       php8.3-intl php8.3-readline \
       php8.3-ldap \
       php8.3-msgpack php8.3-igbinary php8.3-redis php8.3-swoole \
       php8.3-memcached php8.3-pcov php8.3-imagick php8.3-xdebug \
    && curl -sLS https://getcomposer.org/installer | php -- --install-dir=/usr/bin/ --filename=composer \
    && curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg \
    && echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_VERSION.x nodistro main" > /etc/apt/sources.list.d/nodesource.list \
    && apt-get update \
    && apt-get install -y nodejs \
    && npm install -g npm \
    && npm install -g pnpm \
    && npm install -g bun \
    && curl -sS https://dl.yarnpkg.com/debian/pubkey.gpg | gpg --dearmor | tee /etc/apt/keyrings/yarn.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/yarn.gpg] https://dl.yarnpkg.com/debian/ stable main" > /etc/apt/sources.list.d/yarn.list \
    && curl -sS https://www.postgresql.org/media/keys/ACCC4CF8.asc | gpg --dearmor | tee /etc/apt/keyrings/pgdg.gpg >/dev/null \
    && echo "deb [signed-by=/etc/apt/keyrings/pgdg.gpg] http://apt.postgresql.org/pub/repos/apt jammy-pgdg main" > /etc/apt/sources.list.d/pgdg.list \
    && apt-get update \
    && apt-get install -y yarn \
    && apt-get install -y mysql-client \
    && apt-get install -y postgresql-client-$POSTGRES_VERSION \
    && apt-get install -y ffmpeg build-essential libasound2-dev libpulse-dev lame \
    && apt-get install -y jpegoptim optipng pngquant gifsicle webp libavif-bin

RUN setcap "cap_net_bind_service=+ep" /usr/bin/php8.3

RUN groupadd --force -g $WWWGROUP sail
RUN useradd -ms /bin/bash --no-user-group -g $WWWGROUP -u 1337 sail

###########################################
# Custom stuff
##########################################

# Youtube DLP
RUN curl -L https://github.com/yt-dlp/yt-dlp/releases/latest/download/yt-dlp -o /usr/local/bin/yt-dlp && \
        chmod a+rwx /usr/local/bin/yt-dlp

## Dectalk
RUN cd /home/sail && \
        git clone https://github.com/dectalk/dectalk.git && \
        cd dectalk/src && \
        autoreconf -si && \
        ./configure && \
        make -j && \
        cd /home/sail && \
        chown -R sail:sail dectalk && \
        ln -s /home/sail/dectalk/dist/say /usr/local/bin/dectalk

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
        mv chromedriver-linux64/chromedriver /usr/local/bin

# Packages for compiling the game with Byond
RUN dpkg --add-architecture i386 && \
    apt-get update && \
    apt-get install -y rsync gcc-multilib lib32stdc++6 zlib1g-dev:i386 libssl-dev:i386 pkg-config:i386 libstdc++6 libstdc++6:i386

# Configure git credentials for Goonstation and install Cargo
USER sail
RUN git config --global credential.helper store && \
    echo "https://robuddybot:foo@github.com" > ~/.git-credentials && \
    curl --proto '=https' --tlsv1.2 -sSf https://sh.rustup.rs | sh -s -- -y && \
    ~/.cargo/bin/rustup target add i686-unknown-linux-gnu && \
    chmod -R 777 ~/.cargo
USER root

# Clean up apt cache
RUN apt-get -y autoremove && \
        apt-get clean && \
        rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

COPY start-container /usr/local/bin/start-container
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY php.ini /etc/php/8.3/cli/conf.d/99-sail.ini
RUN chmod +x /usr/local/bin/start-container

EXPOSE 8000

ENTRYPOINT ["start-container"]
