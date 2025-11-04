# -----------------------------
# 基本イメージ
# -----------------------------
FROM php:8.2-apache

# -----------------------------
# PHP拡張モジュールと必要パッケージをインストール
# -----------------------------
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    unzip \
    git \
    && docker-php-ext-install pdo pdo_pgsql zip opcache

# -----------------------------
# Composerをインストール
# -----------------------------
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# -----------------------------
# Laravelアプリをコピー
# -----------------------------
WORKDIR /var/www/html
COPY . .

# -----------------------------
# 権限とPHP設定（ここ重要！）
# -----------------------------
RUN chmod -R 777 storage bootstrap/cache && \
    echo "upload_max_filesize = 20M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "post_max_size = 20M" >> /usr/local/etc/php/conf.d/uploads.ini && \
    echo "memory_limit = 512M" >> /usr/local/etc/php/conf.d/uploads.ini

# -----------------------------
# Apache設定
# -----------------------------
RUN a2enmod rewrite
COPY ./apache/000-default.conf /etc/apache2/sites-available/000-default.conf

# -----------------------------
# Laravelセットアップ
# -----------------------------
RUN composer install --no-interaction --prefer-dist --optimize-autoloader && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache

# -----------------------------
# ポートと起動コマンド
# -----------------------------
EXPOSE 80
CMD ["apache2-foreground"]
