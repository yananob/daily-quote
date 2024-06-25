#!/bin/bash
set -eu

if [ $# -ne 1 ]; then
    echo "$0 [env: local/production]"
    exit 1
fi

TARGET_ENV=$1
echo "Target environment: $TARGET_ENV"

start() {
    echo "Starting server..."
    php artisan command:transfer-db restore --env=$TARGET_ENV
    php artisan serve --host 0.0.0.0 --port 8080 --env=$TARGET_ENV &
    SERVER_PID=$!
    echo "Server started. PID: $SERVER_PID"
}

stop() {
    echo "Stopping server..."
    kill $SERVER_PID
    echo "Server stopped. Cleaning up..."
    # clean up
    php artisan command:transfer-db store --env=$TARGET_ENV
    echo "Cleaning done."
}

trap stop SIGINT SIGTERM

start
wait $SERVER_PID
