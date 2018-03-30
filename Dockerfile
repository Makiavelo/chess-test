FROM php:7.0-apache
LABEL Description="Image for Chess test" Vendor="Emanuel Bohnenkamp" Version="1.0"
LABEL maintainer="Emanuel Bohnenkamp (emanuel.bohnenkamp@gmail.com)"

ARG BASE_URL="http://basebuilders.pink"
ARG VIEW_DOMAIN_PROTOCOL="http://"
ARG VIEW_DOMAIN="basebuilders.pink"
ARG PROJECT_DIR_PATH="../../site/"
ARG APPLICATION_URL="http://basebuilders.blue"
ARG DB_HOSTNAME=mariadb
ARG DB_USERNAME=proj281
ARG DB_PASSWORD=proj281pwd
ARG DB_DATABASE=proj281

ENV APACHE_DOCUMENT_ROOT /var/www/chess/public/

#Apache/PHP config
ADD . /var/www/chess

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf