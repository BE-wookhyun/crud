v3 : docker swarm 으로 여러개의 서버에서 컨테이너 분산 관리

Swarm 으로 서버를 여러개 묶는다
# Leader server 
docker swarm init --advertise-addr <SERVER_A_IP>
# Worker server
docker swarm join ~~~~ 를 다른 서버에 추가하기 



# 실행
docker stack deploy --with-registry-auth -c docker-compose.yml php_stack
# 삭제
docker stack rm php_stack

# deploy 확인
docker stack ls
docker stack ps php_stack 

# 서비스확인
docker service ls
docekr service ps php_stack_php