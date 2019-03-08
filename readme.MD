
# Local dev for php docs.

## How to use

1. Run this to build the containers.
```
docker-compose build
```


2. Run this to bring the containers up.
```
docker-compose up --build
```

Fyi for those who don't use docker compose on a regular basis, this command line will stay watching the output from the containers until you press CTRL+C which also stops the containers.

The first run takes a while as cloning the repos takes a few minutes, as does the initial full render of the file.

Wait until you see: 
```
installer_1      | Okay, should be installed. Container should now exit.
phpdoctool_installer_1 exited with code 0
```

which means everything has finished building.


3. Visit http://local.docs.phpimagick.com/ in a web browser. You should see the manual.




4. In a different command line window run. 

```
php watch_for_changes.php
```



## Debugging stuff

Run this to bash into the PHP container.

```
docker exec -it phpdoctool_php_fpm_1 bash
```

All of the command line tools are available throught  
```
php cli.php
```

Where all of the available commands are listed with help notes.


 ```
 docker rm $(docker ps -a -q)
 docker rmi $(docker images -q)
 docker network rm $(docker network ls -q)
 ```

 


https://git.php.net/repository/web/doc.git
 
  
 
## TODO list

