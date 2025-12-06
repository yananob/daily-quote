#!/bin/bash
set -eu

gcloud pubsub topics publish daily-quote2-event --message='{"command": ""}'
