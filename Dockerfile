FROM php:8.2-apache

# Cài đặt các thư viện mở rộng cần thiết cho PDO MySQL hoạt động
RUN docker-php-ext-install pdo pdo_mysql

# Bật module rewrite của Apache (rất quan trọng cho mô hình MVC/Router của bạn)
RUN a2enmod rewrite

# Copy toàn bộ code dự án vào thư mục chạy của Apache
COPY . /var/www/html/

# Cấp quyền đọc ghi cho thư mục
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80