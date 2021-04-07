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

If Laravel shows Permission denied error for storage:
``` bash
sudo chmod -R 777 storage bootstrap/cache
```

After that Laravel wants to generate key for *php artisan*.

``` bash
sudo docker-compose exec php php artisan key:generate
```

To run periodical scheduling
 ``` bash
sudo docker-compose exec -d php crond -f
```

Than everything inside method `schedule()` in `source/App/Console/Kernel.php` will be executed. Than use for example `$schedule->call(function () {})->everyTwoMinutes();` to execute function every two minutes (see [Laravel scheduling](https://laravel.com/docs/8.x/scheduling))

After install npm dependencies.

``` bash
sudo docker-compose run --rm npm install
```


If you want to change .vue files, you have to run this:

``` bash
sudo docker-compose run --rm npm run watch
```

<!--
Next you must install the frontend scaffolding (Bootstrap and Vue.js). ("Yes" for commands with `--auth`):
``` bash
php artisan ui bootstrap
php artisan ui vue
php artisan ui bootstrap --auth
php artisan ui vue --auth
```

Next you must install project frontend dependencies:
``` bash
npm install
```
-->

If you edit css (sass) files, run `npm run dev` command to compile sass file and it will be put into `public/css` directory (more [frontend writing](https://laravel.com/docs/7.x/frontend#writing-css)). 


Now it should work:

Service | Address
------- | -------
Web app | localhost:8080
phpmyadmin | localhost:8081

In phpmyadmin leave **Server** field empty. Default **Name** is *root* and default **Password** is *secret* (from **.env** file).


## MySQL configuration

Open MySQL container
``` bash
docker-compose exec mysql bash
```

Create user
``` bash
mysql -u root -p
show databases;
GRANT ALL ON laravel_db.* TO 'username'@'%' IDENTIFIED BY 'secret';
FLUSH PRIVILEGES;
EXIT;
exit
```


## Delete docker containers and images

In case there was any problem and you wish to delete docker containers and images.

Easiest way - delete all containers and images:

``` bash
sudo docker rm -f $(sudo docker ps -a -q)
```

``` bash
sudo docker rmi -f $(sudo docker images -a -q)
```


## Migrate Database

To initialize tables and data in PhpMyAdmin run command: 

``` bash
docker-compose exec php php artisan migrate:fresh --seed
```
