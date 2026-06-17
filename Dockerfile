FROM php:8.2-apache

# Cài đặt các thư viện mở rộng cần thiết cho PDO MySQL hoạt động
RUN docker-php-ext-install pdo pdo_mysql

# Bật module rewrite của Apache 
RUN a2enmod rewrite

# Thay đổi DocumentRoot của Apache sang /var/www/html/public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Copy toàn bộ code dự án vào thư mục chạy của Apache
COPY . /var/www/html/

# Cấp quyền đọc ghi cho thư mục
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80