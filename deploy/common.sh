#!/bin/bash

REGION="us-west1"
PROJECT_ID=`gcloud config get core/project`
PROJECT_NO=`gcloud projects describe $PROJECT_ID --format="value(projectNumber)"`
