FROM php:8.4-fpm

RUN apt-get update \
    && apt-get install -y \
        curl \
        gnupg \
        unixodbc-dev \
    && curl -sSL https://packages.microsoft.com/keys/microsoft.asc \
        | gpg --dearmor \
        > /usr/share/keyrings/microsoft-prod.gpg \
    && curl -sSL https://packages.microsoft.com/config/debian/12/prod.list \
        > /etc/apt/sources.list.d/mssql-release.list \
    && apt-get update \
    && ACCEPT_EULA=Y apt-get install -y msodbcsql18 \
    && pecl install sqlsrv pdo_sqlsrv \
    && docker-php-ext-enable sqlsrv pdo_sqlsrv \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Faz o www-data usar o mesmo UID/GID do usuário do host,
# para conseguir escrever em storage/ e bootstrap/cache/ montados via volume
ARG UID=1000
ARG GID=1000
RUN groupmod -o -g ${GID} www-data \
    && usermod -o -u ${UID} -g ${GID} www-data

WORKDIR /var/www/html
