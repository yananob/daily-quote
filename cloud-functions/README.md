# Deliver Quote Cloud Function

This Cloud Function fetches a random quote from Firestore and sends it to a specified LINE user or group.

## Setup

1.  **Install Dependencies:**
    ```bash
    composer install
    ```

2.  **Set Environment Variables:**
    Create a `.env.yaml` file in this directory with the following content:
    ```yaml
    LINE_BOT_CHANNEL_ACCESS_TOKEN: "your_line_bot_channel_access_token"
    LINE_BOT_CHANNEL_SECRET: "your_line_bot_channel_secret"
    MYAPP_DELIVER_TARGET: "your_line_user_or_group_id"
    ```
    Replace the placeholder values with your actual LINE Bot credentials and the target ID.

## Deployment

Deploy the function to Google Cloud Functions using the `gcloud` command-line tool:

```bash
gcloud functions deploy deliverQuote \\
  --runtime php81 \\
  --trigger-topic daily-quote-trigger \\
  --entry-point deliverQuote \\
  --env-vars-file .env.yaml
```
This command deploys the `deliverQuote` function, setting it to trigger on messages published to the `daily-quote-trigger` Pub/Sub topic. The required environment variables are loaded from the `.env.yaml` file.
