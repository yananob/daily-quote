#!/bin/bash
set -eu

CUR_DIR=`pwd`
# いる？
# export GOOGLE_APPLICATION_CREDENTIALS="$CUR_DIR/../src/config/gcp_serviceaccount.json"
# echo $GOOGLE_APPLICATION_CREDENTIALS

cd ../src

php artisan command:deliver-quote --env=local
