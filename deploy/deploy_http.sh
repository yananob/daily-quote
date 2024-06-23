#!/bin/bash
set -eu

source ../common.sh

echo "Deploying to region: ${REGION}, project: ${PROJECT_ID}"

pushd ..

docker-compose build
docker tag daily-quote:latest ${REGION}-docker.pkg.dev/${PROJECT_ID}/daily-quote/main

docker push ${REGION}-docker.pkg.dev/${PROJECT_ID}/daily-quote/main

gcloud run deploy daily-quote-container --image us-west1-docker.pkg.dev/nobu5-393106/daily-quote/main:latest

popd
