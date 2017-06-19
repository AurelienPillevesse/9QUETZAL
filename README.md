9Quetzal
========

    docker pull mysql

    docker run --name 9quetzal-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest

    composer install #For all dependencies

    sudo apt-get install php-mysql #PDO, MySQL Drivers

    php bin/console doctrine:database:create

    php bin/console doctrine:schema:update --force

    php bin/console server:run

## Si composer n'est pas installer executer les commandes suivantes:

	 php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
	 
	php -r "if (hash_file('SHA384', 'composer-setup.php') === '669656bab3166a7aff8a7506b8cb2d1c292f042046c5a994c43155c0be6190fa0355160742ab2e1c88d40d5be660b410') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
	
	php composer-setup.php
	
	php -r "unlink('composer-setup.php');"
	
	php composer.phar and command


#### Justification de l'utilisation de FOSUserBundle
