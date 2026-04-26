FROM php:8.3-cli
# FROM php:8.3-fpm
# php -S 쓰면 8000 + proxy_pass, php-fpm 쓰면 9000 + fastcgi_pass

# mysqli 확장 설치
RUN docker-php-ext-install mysqli

# docker compose와 충돌해결
WORKDIR /app
# WORKDIR /var/www/html
# COPY . . 
# >> 있으면 app/app/index.php를 찾음

# 서버 실행
CMD ["php", "-S", "0.0.0.0:8000"]