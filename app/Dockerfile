FROM php:8.3-cli

# mysqli 확장 설치
RUN docker-php-ext-install mysqli

# 작업 디렉토리
WORKDIR /app

# 현재 폴더 복사
COPY . .

# 서버 실행
CMD ["php", "-S", "0.0.0.0:8000"]