v2+ : docker-compose 통한 컨테이너화 + nginx

php / db 컨테이너로 관리하기
secrets 로 DB password 관리
env 로 DB 정보 관리

# 실행
docker compose up -d 
# 삭제
docker compose down


docker compose logs php


# 트래픽 증가시 일시적으로 스케일업
# container_name: php-app  # replica / --scale 을 하려면 컨테이너명 고정 x 
docker compose up -d --scale php=3 --no-recreate