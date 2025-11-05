# 公式のPHP 8.2イメージにApache web serverがプリインストールされたベースイメージを設定
FROM php:8.2-apache

# ---------------------------------------------------
# 必要パッケージとPHP拡張をインストール
# ---------------------------------------------------
RUN apt-get update && apt-get install -y \
  zip \
  unzip \
  git \
  libpq-dev \
  libzip-dev && \
  docker-php-ext-install pdo pdo_pgsql zip opcache && \
  docker-php-ext-enable opcache

# ---------------------------------------------------
# Apache設定（ポート・ルート）
# ---------------------------------------------------
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Laravelのルーティング機能を使用できる様、ApacheのURLリライト機能を有効化
RUN cd /etc/apache2/mods-enabled && ln -s ../mods-available/rewrite.load

# php.ini-productionをサーバー環境用に設定
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# ---------------------------------------------------
# 作業ディレクトリとアプリ配置
# ---------------------------------------------------
WORKDIR /var/www/html
COPY . ./

# ---------------------------------------------------
# Composer インストール
# ---------------------------------------------------
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# ---------------------------------------------------
# 権限とComposer install
# ---------------------------------------------------
RUN chown -Rf www-data:www-data ./ && composer install --no-dev --optimize-autoloader

# ---------------------------------------------------
# Laravel初期化コマンド
# ---------------------------------------------------
RUN php artisan key:generate --show && \
    php artisan config:clear && \
    php artisan cache:clear

# ---------------------------------------------------
# ポートと起動コマンド
# ---------------------------------------------------
EXPOSE 8080
CMD ["apache2-foreground"]
