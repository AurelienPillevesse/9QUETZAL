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

Recuperer un JokePost en JSON via l'API
>`/api/jokepost/{id}`

Creer un nouveau JokePost en JSON via l'API
>`/api/jokepost/new`

Liker un JokePost en JSON via l'API
>`/api/jokepost/{id}/like`

Unliker un JokePost en JSON via l'API
>`/api/jokepost/{id}/unlike`


### API documentation

#### Register
URL: POST /api/register

Content:
```json
{
	"username": "Aurelien",
	"email": "aurelien@aurelien.fr",
	"password": "password"
}
```

Response:
```json
{
	"id": 10,
	"username": "Aurelien"
}
```

#### Login
URL: POST /api/login

Content:
```json
{
	"username": "Aurelien",
	"password": "password"
}
```

Response:
```json
{
	"user": {
		"id": 7,
		"username": "Aurelien"
	},
	"hash": "594fe86476b7a"
}
```

#### Get one post
URL: GET /api/jokepost/{id}

Response:
```json
{
	"id": 1,
	"author": {
		"id": 1,
		"username": "Aurelien"
	},
	"title": "Aurticle 1",
	"image": "f2d8ebe499e7a795f096577de4d197e2.png",
	"date": "2017-06-25 09:16:05",
	"upvotes": 0,
	"downvotes": 1,
	"totalvotes": 1,
	"comments": [{
		"id": 5,
		"content": "Super article",
		"user": {
			"id": 7,
			"username": "Aurélien"
		}
	}]
}
```

#### Get all posts
URL: GET /api/jokepost/all

Response:
```json
[{
	"id": 2,
	"author": {
		"id": 1,
		"username": "Pierre"
	},
	"title": "Article 2",
	"image": "9efd5a7fe90332620879a5bb2982367d.png",
	"date": "2017-06-25 05:55:24",
	"upvotes": 0,
	"downvotes": 0,
	"totalvotes": 0,
	"comments": []
}, {
	"id": 1,
	"author": {
		"id": 1,
		"username": "Pierre"
	},
	"title": "Article 1",
	"image": "c9ad545807c8e8c7ebd8d1f974e85341.jpeg",
	"date": "2017-06-25 05:53:40",
	"upvotes": 0,
	"downvotes": 0,
	"totalvotes": 0,
	"comments": []
}]
```
#### Like one post
URL: POST /api/jokepost/{id}/like

Content:
```json
{
	"token": "YOUR TOKEN AFTER LOGIN"
}
```

Response:
```json
{
	"id": 1,
	"author": {
		"id": 1,
		"username": "Pierre"
	},
	"title": "Article 1",
	"image": "f2d8ebe499e7a795f096577de4d197e2.png",
	"date": "2017-06-25 09:16:05",
	"upvotes": 1,
	"downvotes": 0,
	"totalvotes": 1,
	"comments": []
}
```

#### Unlike one post
URL: POST /api/jokepost/{id}/unlike

Content:
```json
{
	"token": "YOUR TOKEN AFTER LOGIN"
}
```

Response:
```json
{
	"id": 1,
	"author": {
		"id": 1,
		"username": "Pierre"
	},
	"title": "Article 1",
	"image": "f2d8ebe499e7a795f096577de4d197e2.png",
	"date": "2017-06-25 09:16:05",
	"upvotes": 0,
	"downvotes": 1,
	"totalvotes": 1,
	"comments": [{
		"id": 5,
		"content": "Super article",
		"user": {
			"id": 7,
			"username": "Aurélien"
		}
	}]
}
```

#### Comment for one post
URL: POST /api/jokepost/{id}/comment

Content:
```json
{
	"token": "YOUR TOKEN AFTER LOGIN",
	"content": "Les commentaires fonctionnent"
}
```

Response:
```json
{
	"id": 7,
	"content": "Les commentaires fonctionnent",
	"user": {
		"id": 7,
		"username": "Aurélien"
	}
}
```
