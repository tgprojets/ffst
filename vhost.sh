#!/bin/bash
symfony_websf="/opt/symfony/sf1.4"

if [ -z "$1" ]; then
 echo usage: $0 project_name
 exit
fi

PROJECT=$1
bExiste=false
if [ -f /etc/apache2/sites-available/$PROJECT ]; then
  bExiste=true
fi

#Virtualhost 

URL_PROJECT=`echo "$PROJECT.localhost" | tr '[[:upper:]]' '[[:lower:]]'`

sudo echo "
<virtualhost 127.0.0.1:80>
  ServerName $URL_PROJECT

  DocumentRoot \"`pwd`/web\"
  DirectoryIndex index.php

  <directory \"`pwd`/web\">
    AllowOverride All
    Allow from all
  </directory>

</virtualhost>
" > /etc/apache2/sites-available/$PROJECT
if [ $bExiste = "false" ]; then
  sudo echo "127.0.0.1  $URL_PROJECT" >> /etc/hosts
fi
sudo a2ensite $PROJECT

sudo /etc/init.d/apache2 restart