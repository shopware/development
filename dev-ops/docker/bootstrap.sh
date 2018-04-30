docker exec $1 /addExternalUser $2

if [ "$2" -eq "1000" ]; then
    docker cp $3 $1:/home/application/.ssh/id_rsa
    docker exec $1 chmod 400 /home/application/.ssh/id_rsa
else
    docker cp $3 $1:/home/git-user/.ssh/id_rsa
    docker exec $1 chmod 400 /home/git-user/.ssh/id_rsa
fi