FROM php
WORKDIR /app/public
RUN docker-php-ext-install pdo pdo_mysql
CMD ["php", "-S", "0.0.0.0:80"]
