#!/bin/bash
if [ -z "$1" ]; then
    ./symfony doctrine:build --all --no-confirmation
    bdd="ffst_dev"
else
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    bdd="ffst_$1"
fi
./symfony doctrine:data-load data/fixtures/fixtures.yml

-codepostaux="data/sql/codepostaux.sql"

export PGUSER=userdev;
export PGPASSWORD=dev_projets;


psql -h localhost -d $bdd -f $codepostaux