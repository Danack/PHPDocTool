


# Local dev for php docs.


## How to use


docker-compose build

docker-compose up --build

cd /var/app

git clone https://github.com/phpdoctest/en

cd /var/app/en

git clone https://github.com/phpdoctest/doc-base

cd /var/app
git clone http://git.php.net/repository/web/php.git php.net

mv /var/app/php.net/manual/en /var/app/php.net/manual/en_git

ln -s /var/app/output/php-web /var/app/php.net/manual/en

# this takes 2 minutes
php /var/app/doc-en/doc-base/configure.php



php /var/app/phd/render.php --docbook /var/app/doc-en/doc-base/.manual.xml --package PHP --format php






