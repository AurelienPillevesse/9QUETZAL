9Quetzal by Pierre Pic and Aurélien Pillevesse
========

    docker pull mysql

    docker run --name 9quetzal-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest

    composer install #For all dependencies

    php bin/console doctrine:database:create

    php bin/console doctrine:schema:update --force

    php bin/console server:run

    If you come back on the project another time after the docker run and you didn't destroy it, run: docker start 9quetzal-mysql

#### Justification de l'utilisation de FOSUserBundle
