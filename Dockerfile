FROM php:8.1-apache

ENV DEBIAN_FRONTEND noninteractive

RUN set -x \
    && apt-get update
	
RUN apt remove -y --purge ca-certificates-java default-jre default-jre-headless java-common openjdk-17-jre:amd64 openjdk-17-jre-headless:amd64
RUN apt install -y --no-install-recommends ca-certificates-java
RUN apt install -y --no-install-recommends default-jre default-jre-headless java-common openjdk-17-jre:amd64 openjdk-17-jre-headless:amd64
RUN apt-get install -y --no-install-recommends \
       libreoffice libreoffice-java-common
RUN rm -rf /var/lib/apt/lists/*

COPY src/ /var/www/html/

RUN usermod -d /var/www www-data

RUN mkdir -p /var/www/.config /var/www/.cache && chown -R www-data:www-data /var/www/.config /var/www/.cache /var/cache/fontconfig