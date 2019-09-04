# To change this license header, choose License Headers in Project Properties.
# To change this template file, choose Tools | Templates
# and open the template in the editor.
FROM php:7.2-fpm

# Set timezone
RUN rm /etc/localtime
RUN ln -s /usr/share/zoneinfo/Europe/Paris /etc/localtime
RUN "date"
# Type docker-php-ext-install to see available extensions
RUN docker-php-ext-install pdo pdo_mysql


WORKDIR .
