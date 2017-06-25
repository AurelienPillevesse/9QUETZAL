9Quetzal
========

 by Pierre Pic and Aurélien Pillevesse

 -----------------

 ### Installation guide:

First get mysql docker image:

    docker pull mysql

Launch docker:

    docker run --name 9quetzal-mysql -e MYSQL_ROOT_PASSWORD=root -d mysql:latest

Install all dependencies in the project ([get composer here](https://www.getComposer.org)):

    composer install

Create database

    php bin/console doctrine:database:create

Update the schema

    php bin/console doctrine:schema:update --force

### And run it

    php bin/console server:run

If you come back on the project another time after the docker run and you didn't destroy it, run:

    docker start 9quetzal-mysql

### Justification de l'utilisation de FOSUserBundle

- Bundle deja coder, securisé et fiable.
- Plus facile pour mettre en une connexion sur le site
- Grande communauté et documentation
- Pre-template ( redefinis )

### Routes


Route par defaut
>`/`

Register via the API
>`/api/register`

Login via the api
>`/api/login`

Commenter un JokePost avec l'API
>`/api/jokepost/{id}/comment`

Creer un nouveau JokePost
>`/jokepost/new`

Liker un Post

$id ⇒ Id du post
>`/jokepost/{id}/like`


Unliker un JokePost

$id ⇒ Id du post
>`/jokepost/{id}/unlike`


Consulter

$id ⇒ Id du post
>`/jokepost/{id}`

Récuperer la list de tout les JokePost en JSON via l'API
>`/api/jokepost/all`

Recuperer un JOkePost en JSON via l'API
>`/api/jokepost/{id}`

Creer un nouveau JokePost en JSON via l'API
>`/api/jokepost/new`

Liker un JokePost en JSON via l'API
>`/api/jokepost/{id}/like`

Unliker un JokePost en JSON via l'API
>`/api/jokepost/{id}/unlike`
