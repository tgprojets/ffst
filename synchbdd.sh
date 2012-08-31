#!/bin/bash
if [ -z "$1" ]; then
    ./symfony doctrine:build --all --no-confirmation
    bdd="ffst_dev"
elif [ "$1" == "prod" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    ./symfony doctrine:data-load data/fixtures/prod.yml
    bdd="ffst"
elif [ "$1" == "sb" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="sandbox"
    bdd="ffst_sb"
else
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    bdd="ffst_$1"
fi
./symfony doctrine:data-load data/fixtures/fixtures.yml

codepostaux="data/patch/codepostaux.sql"

if [ "$1" == "prod" ]; then
    export PGUSER=ffst;
    export PGPASSWORD=NicudOaltij7;
elif [ "$1" == "sb" ]; then
    export PGUSER=ffst_sb;
    export PGPASSWORD=pho4raCh;
else
    export PGUSER=userdev;
    export PGPASSWORD=dev_projets;
fi

psql -h localhost -d $bdd -f $codepostaux