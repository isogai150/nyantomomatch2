# PHP 8.2 + Apache
FROM php:8.2-apache

# ---------------------------------------------------
# 必要パッケージと PHP 拡張をインストール（MySQL対応）
# ---------------------------------------------------
RUN apt-get update && apt-get install -y \
    zip \
    unzip \
    git \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    libzip-dev \
    default-mysql-client \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath \
    && docker-php-ext-enable pdo_mysql

# ---------------------------------------------------
# Apache 設定（ポート・ドキュメントルート）
# ---------------------------------------------------
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# URLリライト有効化
RUN a2enmod rewrite

# php.ini-production を使用
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# ---------------------------------------------------
# プロジェクト配置
# ---------------------------------------------------
WORKDIR /var/www/html
COPY . .

# ---------------------------------------------------
# Composer インストール
# ---------------------------------------------------
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# ---------------------------------------------------
# 権限設定 & Composer install
# ---------------------------------------------------
RUN chown -Rf www-data:www-data /var/www/html \
    && composer install --no-dev --optimize-autoloader

# ---------------------------------------------------
# Laravel 初期化コマンド
# ---------------------------------------------------
RUN php artisan key:generate --show \
    && php artisan config:clear \
    && php artisan cache:clear

# ---------------------------------------------------
# 起動設定
# ---------------------------------------------------
EXPOSE 8080
CMD ["apache2-foreground"]
