# Installation of WAP project

## Define environment variables in .env file

Create .env file from example file. Inside this file you can edit your local database access informations. *If you wish you can change user name and password but in local environment you don't need to change any values.*

``` bash
cp .env.example .env
```

There is also antoher file insice **source** folder. These new files must have same values. 

``` bash
cp source/.env.example source/.env
```

These two files are in **.gitignore** so no sensitive data will be public.



## Create docker containers

This operation takes a long time to finish.
``` bash
sudo docker-compose up --build
```

## Init Laravel project

Once docker containers are running, it's time to init laravel. 

First switch to **source** directory.

``` bash
cd source
```

Download composer dependencies for Laravel such as **vendor** direcotry.

``` bash
sudo docker-compose run --rm composer update
```

After that Laravel wants to generate key for *php artisan*.

``` bash
sudo docker-compose run --rm php /var/www/html/artisan key:generate
```

Now it should work.

## Delete docker containers and images

In case there was any problem and you wish to delete docker containers and images.

Easiest way - delete all containers and images:

``` bash
sudo docker rm -f $(sudo docker ps -a -q)
```

``` bash
sudo docker rmi -f $(sudo docker images -a -q)
```
