#!/bin/bash
set -eu

if [ $# -ne 2 ]; then
    echo "Insufficient parameters."
    echo "  $0 [env: local/production]"
    exit 1
fi

TARGET_ENV=$1
TARGET_CMD=$2
echo "Target: $TARGET_ENV, $TARGET_CMD"

echo "Starting command..."
php artisan command:transfer-db restore --env=$TARGET_ENV
php artisan command:$TARGET_CMD --env=$TARGET_ENV
