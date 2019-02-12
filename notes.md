

These work!

# run in /var/app/doc-en - takes 2m33.262s

php ../doc-base/configure.php --partial=imagick.newimage

# run in /var/app - takes 6 seconds
php /var/app/phd/render.php \
    --docbook /var/app/doc-en/doc-base/.manual.imagick.newimage.xml \
    --memoryindex \
    --package PHP \
    --format php







These are the files generated in doc-base.

.manual.xml
developer.template.xml
entities/file-entities.ent
install-unix.xml
install-win.xml
manual.xml
scripts/file-entities.php
version.xml


in file
   /doc-editor/js/ui/cmp/PreviewFile.js


previewFile

/doc-editor/php/controller.php?task=previewFile&path=%2Fmanual%2Fen%2Fphdoe-1549586703-imagick.setup.php


php doc-base/configure.php --generate='/manual/en/phdoe-1549586703-imagick.setup.php'



php phd/render.php \
    --package PHP \
    --format php \
    --memoryindex \
    -d doc-base/.manual.xml \
    --output '-new/output-d41d8cd98f00b204e9800998ecf8427e/'

--generate=FILENAME            Create an XML only for provided file

--with-partial=imagick.newimage       Root ID to build (e.g. <book xml:id="MY-ID">) [{$acd['PARTIAL']}]



PARTIAL



# run in /var/app/doc-en - takes 2m33.262s
cd /var/app/doc-en/en

php ../doc-base/configure.php \
    --generate='/var/app/doc-en/en/reference/imagick/imagick/newimage.xml' \
    --with-partial=imagick.newimage












