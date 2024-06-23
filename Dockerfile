FROM php:8.2-cli-buster

RUN apt-get update \
  && apt-get upgrade -y

RUN docker-php-ext-configure pcntl --enable-pcntl \
  && docker-php-ext-install pcntl

COPY . /myapp

WORKDIR /myapp

# memo: artisan内では（Laravel起動中のためか）envで環境変数が取れないため、ここで指定
ENV GOOGLE_APPLICATION_CREDENTIALS /myapp/config/gcp_serviceaccount.json

CMD [ "php", "artisan", "serve", "--host", "0.0.0.0", "--port", "8080" ]
