# 公式のPHP 8.2イメージにApache web serverがプリインストールされたベースイメージを設定
FROM php:8.2-apache

# コンテナに必要なパッケージ(zip、unzip、git)をインストール
# ここを修正 git \
# ここを追記 libpq-dev（PHPからPostgreSQLに接続するために必要なライブラリ）
RUN apt-get update && apt-get install -y \
  zip \
  unzip \
  git \
  libpq-dev

# ここを追記（PostgreSQLのドライバをインストール）
RUN docker-php-ext-install pdo_pgsql

# PHPアプリケーションの処理を高速化する拡張機能をインストール
RUN docker-php-ext-install -j "$(nproc)" opcache && docker-php-ext-enable opcache

# デフォルトのApacheポートを80から8080に変更
RUN sed -i 's/80/8080/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

# デフォルトのApacheドキュメントルートを/var/www/htmlから/var/www/html/publicに変更
RUN sed -i 's#/var/www/html#/var/www/html/public#g' /etc/apache2/sites-available/000-default.conf

# Laravelのルーティング機能を使用できる様、ApacheのURLリライト機能を有効化
RUN cd /etc/apache2/mods-enabled \
&& ln -s ../mods-available/rewrite.load

# php.ini-productionをphp.iniにリネーム（サーバー環境に適したPHPの設定を、PHP設定ファイルとして使用）
RUN mv "$PHP_INI_DIR/php.ini-production" "$PHP_INI_DIR/php.ini"

# コンテナの作業ディレクトリを/var/www/htmlに設定
WORKDIR /var/www/html

# 現在のディレクトリ(プロジェクトの中身)をコンテナの/var/www/htmlにコピー
COPY . ./

# Composerのインストール
RUN cd /usr/bin && curl -s http://getcomposer.org/installer | php && ln -s /usr/bin/composer.phar /usr/bin/composer

# Install AWS S3 package for file uploads
# RUN composer require league/flysystem-aws-s3-v3 "^3.0" --with-all-dependencies

# プロジェクトファイルの所有者を、rootユーザーからApacheのデフォルトユーザーに変更
RUN chown -Rf www-data:www-data ./

# venderの作成（laravelの依存関係をインストール）
RUN composer install --no-dev --optimize-autoloader --no-scripts

# APP_KEYの表示
# RUN php artisan key:generate --show


RUN php artisan migrate:fresh --force
# RUN php artisan db:seed --force




RUN php artisan config:clear
RUN php artisan cache:clear
RUN php artisan route:clear
RUN php artisan optimize:clear


# 起動コマンド
CMD ["apache2-foreground"]
