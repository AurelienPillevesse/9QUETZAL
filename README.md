9Quetzal
========

docker pull mysql

docker run --name 9quetzal-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest

php bin/console doctrine:database:create

php bin/console doctrine:schema:update --force