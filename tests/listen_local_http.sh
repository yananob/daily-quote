#!/bin/bash
set -eu

source ./tests/secrets.sh
source ./_cf-common/test/export_secrets.sh ${SECRETS[*]}

# Launch function
export FUNCTION_TARGET=main_http
# php -S localhost:8080 tests/bootstrap_local.php
APP_ENV=local php -S localhost:8080 vendor/bin/router.php

source ./_cf-common/test/unset_secrets.sh ${SECRETS[*]}
