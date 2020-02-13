#!/bin/bash

docker rm $(docker ps -aq)
docker build -t my-php-app .

docker run -p 8080:80 --name my-running-app  my-php-app 