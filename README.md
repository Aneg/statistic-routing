Дано
----

2 файла с логами.

В 1-ом файле в каждой строке выводится 5 атрибутов разделенные символом
«|»: дата, время, IP-адрес пользователя, URL с которого зашел, URL куда зашел.

Во 2-ом — в каждой строке 3 атрибута, также разделенные символом «|»: ip aдрес 
пользователя, наименование используемого браузера, наименование используемой ОС.

Задача
------------
1. Нужно придумать структуру БД на СУБД PostgresSQL и наполнить ее данными 
(На выбор кандидата: можно с помощью sql-скрипта, можно сделать парсер логов) 
2. Сделать грид просмотра данных со следующими полями: IP-адрес, браузер 
(возможность сортировки), ос(возможность сортировки), URL с которого зашел первый раз, 
URL на который зашел последний раз, кол-во просмотренных уникальных URL-адресов. 
Требуется также фильтрацию данных по IP..

Установка
---------

1. Композер

        composer install
    
2. Подключить базу данных создав файл `config/autoload/local.php` (пример настроек `config/autoload/local.php.dist`)

3. Загружаем структуру базы из дампа

Парсинг данных
--------------

В папке public/data есть 2 заготовленных тестовых файла `displacements.data` и `users.data`

Распарсить и занести данные о пользователях в базу

        php index.php parsing users users.data

Распарсить и занести данные о перемещениях пользователя в базу       
        
        php index.php parsing displacements displacements.data
        
при желании можно положить свои файлы в папку и указать их при парсинге 

Web server setup
----------------

### PHP CLI server

The simplest way to get started if you are using PHP 5.4 or above is to start the internal PHP cli-server in the root
directory:

    php -S 0.0.0.0:8080 -t public/ public/index.php

This will start the cli-server on port 8080, and bind it to all network
interfaces.

**Note:** The built-in CLI server is *for development only*.

### Apache setup

To setup apache, setup a virtual host to point to the public/ directory of the
project and you should be ready to go! It should look something like below:

    <VirtualHost *:80>
        ServerName zf2-app.localhost
        DocumentRoot /path/to/zf2-app/public
        <Directory /path/to/zf2-app/public>
            DirectoryIndex index.php
            AllowOverride All
            Order allow,deny
            Allow from all
            <IfModule mod_authz_core.c>
            Require all granted
            </IfModule>
        </Directory>
    </VirtualHost>

### Nginx setup

To setup nginx, open your `/path/to/nginx/nginx.conf` and add an
[include directive](http://nginx.org/en/docs/ngx_core_module.html#include) below
into `http` block if it does not already exist:

    http {
        # ...
        include sites-enabled/*.conf;
    }


Create a virtual host configuration file for your project under `/path/to/nginx/sites-enabled/zf2-app.localhost.conf`
it should look something like below:

    server {
        listen       80;
        server_name  zf2-app.localhost;
        root         /path/to/zf2-app/public;

        location / {
            index index.php;
            try_files $uri $uri/ @php;
        }

        location @php {
            # Pass the PHP requests to FastCGI server (php-fpm) on 127.0.0.1:9000
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_param  SCRIPT_FILENAME /path/to/zf2-app/public/index.php;
            include fastcgi_params;
        }
    }

Restart the nginx, now you should be ready to go!
