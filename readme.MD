


# Local dev for php docs.


## How to use


put this in your /etc/hosts file
```
127.0.0.1 local.php.net
```


Run this to build the containers.
```
docker-compose build
```


Run this to bring the containers up.
```
docker-compose up --build
```


visit local.php.net in a web browser. You should see the manual


docker exec -it phpdoctool_php_fpm_1 bash







## Notes

preview.php.net refused to connect
preview.php.net and edit.php.net have the same IP address.

