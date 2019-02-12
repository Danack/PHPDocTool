
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


3. Change a file in ./docs/en/reference.....

Watch supervisor say stuff is done....check that file is changed in the manual.


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



## Where is everything

./injectionParams - this contains the dependency injection information, for each of the different environments.

./routes - this contains the routing information for the website

./cli_commands - this is where all of the cli commands are hooked up.
 
./lib - all of the actual source code. 
 
./docker - contains the dockerfile's for (instructions for how to build) each of the containers, the config files used by each of the containers, and all the entrypoint.sh scripts, that actually run the stuff inside the container.

./docker/supervisord/tasks - this contains the definitions for the background jobs run by supervisord. 
 
 
Supervisord has an interface at http://127.0.0.1:8080 where you can control the running jobs. Turning them off while changing the code is a good idea...
  
 
## TODO list

* Allow people to set their own git repos for cloning - this will be done through allowing docker-compose-override.yml file to set an ENV variable and then reading the repo URL from en

* Make a frontend so that people can see the status of what is happening in the background.

* Use Python watchdog to monitor files, rather than `git ls`

* Allow non-en files to be edited - and also watch doc-base files, though these might need to trigger a full rebuild.

* Allow skipping of full build on container start.

* Allow people to trigger a full rebuild.

* Wrap exec calls with pipes, so that output can be read + stored immediately, rather than waiting for cmd to finish executing first.

