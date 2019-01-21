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