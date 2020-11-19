set -e
set -x

# This directory holds both the language directory and the 'base' directory
DOC_DIRECTORY="/var/app/docs"

# The language directory
DOC_EN_DIRECTORY="${DOC_DIRECTORY}/en"

# The doc 'base' directory
DOC_BASE_DIRECTORY="${DOC_DIRECTORY}/doc-base"

# PHD directory
PHD_DIRECTORY="/var/app/phd"

# Website directory
PHP_NET_DIRECTORY="/var/app/php.net"


if [ ! -d "$DOC_DIRECTORY" ];
then
    echo "DOC_DIRECTORY $DOC_DIRECTORY does not exist, lets create it"
    set -e
    mkdir -p $DOC_DIRECTORY
    set +e
else
    echo "DOC_DIRECTORY $DOC_DIRECTORY already exists, skipping cloning."
fi



if [ ! -d "$DOC_EN_DIRECTORY" ];
then
  echo "$DOC_EN_DIRECTORY does not exist, lets clone it."
  cd $DOC_DIRECTORY

  #Default repo
  EN_REPO="https://github.com/phpdoctest/en"

  if [[ ! -z "${DOC_EN_REPO}" ]]; then
    EN_REPO=${DOC_EN_REPO}
    echo "Using EN_REPO is $EN_REPO"
  fi

  set -e
  git clone $EN_REPO
  set +e
else
    echo "DOC_EN_DIRECTORY $DOC_EN_DIRECTORY already exists, skipping cloning."
fi


if [ ! -d "$DOC_BASE_DIRECTORY" ];
then
    echo "DOC_BASE_DIRECTORY $DOC_BASE_DIRECTORY does not exist, lets clone it."
    cd $DOC_DIRECTORY
    set -e
    git clone https://github.com/phpdoctest/doc-base
    set +e
else
    echo "DOC_BASE_DIRECTORY $DOC_BASE_DIRECTORY already exists, skipping cloning."
fi


if [ ! -d "$PHD_DIRECTORY" ];
then
    echo "PHD_DIRECTORY $PHD_DIRECTORY does not exist, lets clone it."
    cd /var/app
    set -e
    git clone http://git.php.net/repository/phd.git
    set +e
else
    echo "PHD_DIRECTORY $PHD_DIRECTORY already exists, skipping cloning."
fi


# echo "Configuring docs - this takes about 2 minutes"
# php "${DOC_BASE_DIRECTORY}/configure.php"
#
# echo "Intial render of docs - this takes about 1.5 minutes"
# php "${PHD_DIRECTORY}/render.php" --docbook "${DOC_BASE_DIRECTORY}/.manual.xml" --memoryindex --package PHP --format php

if [ ! -d "$PHP_NET_DIRECTORY" ];
then
    echo "PHP_NET_DIRECTORY does not exist, lets clone it."
    cd /var/app
    set -e
    git clone http://git.php.net/repository/web/php.git php.net
    mv /var/app/php.net/manual/en /var/app/php.net/manual/en_git
    ln -s /var/app/output/php-web /var/app/php.net/manual/en
    set +e
else
    echo "PHP_NET_DIRECTORY already exists, skipping cloning."
fi


echo "Okay, manual ready to view at http://127.0.0.1/. Container should now exit.";

