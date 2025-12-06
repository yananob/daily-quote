#!/bin/bash
set -eu

source ./tests/secrets.sh
source ./_cf-common/test/export_secrets.sh ${SECRETS[*]}

# Launch function
# export PHP_CLI_SERVER_WORKERS=2

# pushd cloud-functions

export FUNCTION_TARGET=main_http
composer start

# popd

source ./_cf-common/test/unset_secrets.sh ${SECRETS[*]}
