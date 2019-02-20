# Starting services

## Start mysql and configure
```bash
/usr/bin/systemctl start mysqld
/usr/bin/mysql_secure_installation
# exemple mysql -u root -p mysql
```

## Open port 3306/tcp
```bash
firewall-cmd --permanent --zone=trusted --add-port=3306/tcp
firewall-cmd  --reload
```

## Start mongodb
Autorize network connexion
```bash
/usr/sbin/setsebool -P httpd_can_network_connect 1
```
Start service
```bash
sudo service mongod start
```

## Start php
```bash
sudo systemctl start php-fpm
```

## Start nginx
```bash
sudo systemctl start nginx
```