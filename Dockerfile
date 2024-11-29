# Используем официальный образ WordPress
FROM wordpress:latest

# Копируем тему в контейнер
COPY ./theme/ /var/www/html/wp-content/themes/my-theme/

# Копируем плагин в контейнер
COPY ./plugin/ /var/www/html/wp-content/plugins/my-core/

# Копируем кастомные настройки PHP
COPY ./custom.ini /usr/local/etc/php/conf.d/custom.ini

# Открываем порт 80
EXPOSE 80

# Команда запуска
CMD ["apache2-foreground"]
