#!/bin/bash
if [ -z "$1" ]; then
    ./symfony doctrine:build --all --no-confirmation
    bdd="ffst_dev"
elif [ "$1" == "prod" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    bdd="ffst"
elif [ "$1" == "sb" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="sandbox"
    bdd="ffst_sb"
else
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    bdd="ffst_$1"
fi
./symfony doctrine:data-load data/fixtures/fixtures.yml

codepostaux="data/sql/codepostaux.sql"

if [ "$1" == "prod" ]; then
    export PGUSER=ffst;
    export PGPASSWORD=NicudOaltij7;
else
    export PGUSER=userdev;
    export PGPASSWORD=dev_projets;
fi

psql -h localhost -d $bdd -f $codepostaux