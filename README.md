9Quetzal
========

    docker pull mysql

    docker run --name 9quetzal-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest

    composer install #For all dependencies

    sudo apt-get install php-mysql #PDO, MySQL Drivers

    php bin/console doctrine:database:create

    php bin/console doctrine:schema:update --force

    php bin/console server:run

#### Justification de l'utilisation de FOSUserBundle
