#!/bin/bash
if [ -z "$1" ]; then
    ./symfony doctrine:build --all --no-confirmation
    ./symfony doctrine:data-load data/fixtures/fixtures.yml
    bdd="ffst_dev"
elif [ "$1" == "prod" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    bdd="ffst"
elif [ "$1" == "sb" ]; then
    ./symfony doctrine:build --all --no-confirmation --env="sandbox"
    ./symfony doctrine:data-load data/fixtures/fixtures.yml
    bdd="ffst_sb"
else
    ./symfony doctrine:build --all --no-confirmation --env="$1"
    ./symfony doctrine:data-load data/fixtures/fixtures.yml
    bdd="ffst_$1"
fi

codepostaux="data/patch/codepostaux.sql"

if [ "$1" == "prod" ]; then
    sUSER=ffst
    sPASSWORD=NicudOaltij7
elif [ "$1" == "sb" ]; then
    sUSER=ffst_sb
    sPASSWORD=pho4raCh
else
    sUSER="userdev"
    sPASSWORD="dev_projets"
fi

mysql -u $sUSER --password=${sPASSWORD} -h localhost -D ${bdd} < $codepostaux
