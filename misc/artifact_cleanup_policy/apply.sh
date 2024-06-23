#!/bin/bash
set -eu

source ../../../common.sh

gcloud artifacts repositories set-cleanup-policies daily-quote \
    --project=${PROJECT_ID} \
    --location=us-west1 \
    --policy=policy.json \
    --no-dry-run
    # --dry-run

    # --overwrite \
    # --verbosity debug \

gcloud artifacts repositories list-cleanup-policies daily-quote \
    --project=${PROJECT_ID} \
    --location=us-west1
