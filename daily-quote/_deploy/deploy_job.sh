#!/bin/bash
set -eu

source ../../common.sh

JOB_NAME="daily-quote-products-job"

gcloud run jobs deploy ${JOB_NAME} \
  --image us-west1-docker.pkg.dev/${PROJECT_ID}/daily-quote/products:latest \
  --command=php \
  --args=artisan,migrate:status \
  --max-retries=1 \
  --task-timeout=1

# ＊updateにしてるので、初回登録時は要変更？
gcloud scheduler jobs update http ${JOB_NAME}-scheduler-trigger \
  --location ${REGION} \
  --schedule="0 0 1 1 1" \
  --time-zone=Asia/Tokyo \
  --uri="https://${REGION}-run.googleapis.com/apis/run.googleapis.com/v1/namespaces/${PROJECT_ID}/jobs/${JOB_NAME}:run" \
  --http-method POST \
  --oauth-service-account-email ${PROJECT_NO}-compute@developer.gserviceaccount.com
