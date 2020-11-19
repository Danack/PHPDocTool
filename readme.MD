
# Local dev for php docs.

This repo allows you to edit, build and view the compiled version of the PHP manual.

To use it you need to either check out the manual source files first, or allow this tool to git clone the repo for you.

## How to use

0. Fork the manual on github, so you can push changes. 


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

## Directories


* ./docs/doc-base
* ./docs/en - the English version of the manual



## Debugging stuff

Run this to bash into the PHP container.

```
docker exec -it phpdoctool_php_fpm_1 bash
```

The script that checks out the repos is in:

./docker/installer/entrypoint.sh


In the unlikely event you need to delete all the docker containers:

 ```
 docker rm $(docker ps -a -q)
 docker rmi $(docker images -q)
 docker network rm $(docker network ls -q)
 ```
  
 
## Repo list

For reference, here is a list of all the repos involved in the manual

PHP Manual Base
https://github.com/phpdoctest/doc-base

PHP English manual
https://github.com/phpdoctest/en

PHP DocBook code -   
http://git.php.net/repository/phd.git

"The PHP Documentation Team site"
https://git.php.net/repository/web/doc.git

 
## TODO list

