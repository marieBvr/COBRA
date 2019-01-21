# Install

## Pre-requisite : CentOS 7
* Make sure your OS is up-to-date before running these command line.
```
sudo yum update
sudo yum install yum-utils
sudo yum install xlrd prettytable gcc openssl-devel
```

## Install git
```
sudo yum install git
```

## Install php
```
sudo yum install php-gd php-mysql php-mbstring php-fpm php php-pear php-devel
```

## Install mongodb 
You can follow official instructions [MongoDB for CentOS](https://docs.mongodb.com/manual/tutorial/install-mongodb-on-red-hat/#configure-the-package-management-system-yum) or use pecl :
```
sudo pecl install mongo
sudo vi /etc/php.ini # uncomment line extension=mongo.so
```

## Install nginx
```
sudo yum install nginx
sudo vi /etc/nginx/nginx.conf
```
Paste this content on the server part and replace YOUR_SERVER_ADRESS :
```
    server {
        #listen       80 default_server;
		listen		  YOUR_SERVER_ADRESS;
        listen       [::]:80 default_server;
        server_name  _;
        root         /usr/share/nginx/html;
	index	/info.php;

        # Load configuration files for the default server block.
        include /etc/nginx/default.d/*.conf;

        location / {
        }

        error_page 404 /404.html;
            location = /40x.html {
        }

        error_page 500 502 503 504 /50x.html;
            location = /50x.html {
        }
    }
```
Then, if not existing, create default.conf file :
```
sudo touch /etc/nging/conf.d/default.conf
sudo vi /etc/nging/conf.d/default.conf
```
Then paste content :
```
server {
    listen       80;
    server_name  localhost;

    # note that these lines are originally from the "location /" block
    root   /usr/share/nginx/html;
    index login.php;

    location / {
        try_files $uri $uri/ =404;
    }
    error_page 404 /404.html;
    error_page 500 502 503 504 /50x.html;
    location = /50x.html {
        root /usr/share/nginx/html;
    }

    location ~ \.php$ {
        try_files $uri =404;
        fastcgi_pass unix:/var/run/php-fpm/php-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```
See [here](https://www.digitalocean.com/community/tutorials/how-to-install-linux-nginx-mysql-php-lemp-stack-on-centos-7#step-one-%E2%80%94-install-nginx) for more information.

## Install mysql
```
sudo rpm -Uvh http://dev.mysql.com/get/mysql-community-release-el7-5.noarch.rpm
sudo yum install mysql-community-server
```

## Install R
```
sudo yum install R
```

## Add epel repository
```
sudo yum install epel-release
```

## Install pip and pymongo
```
sudo yum -y install python-pip
sudo python -m pip install --upgrade pymongo
```