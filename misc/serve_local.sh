#!/bin/bash
set -eu

CUR_DIR=`pwd`
export GOOGLE_APPLICATION_CREDENTIALS="$CUR_DIR/../src/config/gcp_serviceaccount.json"
echo $GOOGLE_APPLICATION_CREDENTIALS

php artisan serve --env=local
