#!/bin/bash
git submodule init
git submodule update

if [ ! -d cache ]; then
  mkdir cache
  chmod -R 777 cache
fi
rm -Rf cache/*
if [ ! -d log ]; then
  mkdir log
  chmod -R 777 log
fi
if [ ! -d web/uploads/thumbnail ]; then
  mkdir -p web/uploads/thumbnail
  chmod -R 777 web/uploads/thumbnail
fi
if [ ! -d web/uploads/logo ]; then
  mkdir -p web/uploads/logo
  chmod -R 777 web/uploads/logo
fi
if [ ! -d web/uploads/profil ]; then
  mkdir -p web/uploads/profil
  chmod -R 777 web/uploads/profil
fi
if [ ! -d web/pdf ]; then
  mkdir -p web/pdf
  chmod -R 777 web/pdf
fi
if [ ! -d web/sf ]; then
  ln -s ./../lib/vendor/symfony/data/web/sf ./web/
fi

php symfony plugin:publish-assets
php symfony cc

read -p "Voulez vous générer les virtualhost Y/N [N]" reponse
if [[ "$reponse" == "Y" || "$reponse" == "y" ]]
then
  sudo sh vhost.sh ffst
fi

if [ -z "$1" ]; then
  exit
else
  read -p "Voulez vous regénérer la base de données Y/N [N]" reponse
  if [[ "$reponse" == "Y" || "$reponse" == "y" ]]
  then
    php symfony doctrine:build --no-confirmation --all --env="$1"
    read -p "Voulez vous charger les données fixtures Y/N [N]" reponse
    if [[ "$reponse" == "Y" || "$reponse" == "y" ]]
    then
      php symfony doctrine:data-load data/fixtures/sfGuard.yml --env="$1"
    fi
  fi
fi

