#!/usr/bin/env bash


# run in /var/app/doc-en - takes 2m33.262s
cd /var/app/docs/en
php ../doc-base/configure.php --partial=imagick.newimage

# run in /var/app - takes 6 seconds

cd /var/app
php /var/app/phd/render.php \
    --docbook /var/app/doc-en/doc-base/.manual.imagick.newimage.xml \
    --memoryindex \
    --package PHP \
    --format php






cd /var/app/docs/en
php ../doc-base/configure.php \
    --generate='/var/app/docs/en/reference/imagick/imagick/newimage.xml' \
    --with-partial=imagick.newimage


cd /var/app
php /var/app/phd/render.php \
    --docbook /var/app/docs/doc-base/.manual.imagick.newimage.xml \
    --memoryindex \
    --package PHP \
    --format php \
    --output ./output_new



echo "File should be updated";