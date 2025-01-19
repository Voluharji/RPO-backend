FROM php:8.3-apache
LABEL authors="jasap"
RUN HTTPDUSER=$(ps axo user,comm | grep -E '[a]pache|[h]ttpd|[_]www|[w]ww-data|[n]ginx' | grep -v root | head -1 | cut -d\  -f1)
RUN sudo setfacl -dR -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
RUN sudo setfacl -R -m u:"$HTTPDUSER":rwX -m u:$(whoami):rwX var
RUN a2enmod rewrite
RUN apt-get update \
  && apt-get install -y libzip-dev git wget --no-install-recommends \
  && apt-get clean \
  && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN docker-php-ext-install pdo mysqli pdo_mysql zip;
RUN wget https://getcomposer.org/download/2.0.9/composer.phar \
   && mv composer.phar /usr/bin/composer && chmod +x /usr/bin/composer \
;

COPY docker/apache.conf /etc/apache2/sites-enabled/000-default.conf
#COPY . /var/www/
WORKDIR /var/www
CMD ["apache2-foreground"]
