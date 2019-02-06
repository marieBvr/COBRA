# Starting services

## Start mysql and configure
```
/usr/bin/systemctl start mysqld
/usr/bin/mysql_secure_installation
# exemple mysql -u root -p mysql
```

## Open port 3306/tcp
```
firewall-cmd --permanent --zone=trusted --add-port=3306/tcp
firewall-cmd  --reload
```

## Start mongodb
Autorize network connexion
```
/usr/sbin/setsebool -P httpd_can_network_connect 1
```
Start service
```
sudo service mongod start
```

## Start php
```
sudo systemctl start php-fpm
```

## Start nginx
```
sudo systemctl start nginx
```