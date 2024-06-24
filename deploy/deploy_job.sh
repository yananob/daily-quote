#!/bin/bash
set -eu

source ../common.sh

JOB_NAME="daily-quote-timer"

echo "Deploying job to region: ${REGION}, project: ${PROJECT_ID}"
gcloud run jobs deploy ${JOB_NAME} \
  --image us-west1-docker.pkg.dev/${PROJECT_ID}/daily-quote/main:latest \
  --command=php \
  --args=artisan,command:deliver-quote,nobu \
  --max-retries=3 \
  --task-timeout=10

echo "Updating job scheduler"
# MEMO: cron: Minute Hour Day Month Weekday(0-6)
# ＊updateにしてるので、初回登録時は要変更
gcloud scheduler jobs update http ${JOB_NAME} \
  --location ${REGION} \
  --schedule="10 7 * * *" \
  --time-zone=Asia/Tokyo \
  --uri="https://${REGION}-run.googleapis.com/apis/run.googleapis.com/v1/namespaces/${PROJECT_ID}/jobs/${JOB_NAME}:run" \
  --http-method POST \
  --oauth-service-account-email ${PROJECT_NO}-compute@developer.gserviceaccount.com
