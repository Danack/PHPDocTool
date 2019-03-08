

echo "Configuring docs - this takes about 2 minutes"
php "docs/doc-base/configure.php"


echo "Intial render of docs - this takes about 1.5 minutes"
php "phd/render.php" --docbook "docs/doc-base/.manual.xml" --memoryindex --package PHP --format php

