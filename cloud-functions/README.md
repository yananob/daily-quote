# 格言配信Cloud Function

このCloud Functionは、Firestoreからランダムに格言を取得し、指定されたLINEユーザーまたはグループに送信します。

## セットアップ

1.  **依存関係のインストール:**
    ```bash
    composer install
    ```

2.  **環境変数の設定:**
    このディレクトリに`.env.yaml`ファイルを作成し、以下の内容を記述します。
    ```yaml
    LINE_BOT_CHANNEL_ACCESS_TOKEN: "your_line_bot_channel_access_token"
    LINE_BOT_CHANNEL_SECRET: "your_line_bot_channel_secret"
    MYAPP_DELIVER_TARGET: "your_line_user_or_group_id"
    ```
    プレースホルダーの値を、実際のLINE Botの認証情報とターゲットIDに置き換えてください。

## デプロイ

`gcloud`コマンドラインツールを使用して、このファンクションをGoogle Cloud Functionsにデプロイします。

```bash
gcloud functions deploy deliverQuote \\
  --runtime php81 \\
  --trigger-topic daily-quote-trigger \\
  --entry-point deliverQuote \\
  --env-vars-file .env.yaml
```
このコマンドは`deliverQuote`ファンクションをデプロイし、`daily-quote-trigger`というPub/Subトピックにメッセージが発行されるたびにトリガーされるように設定します。必要な環境変数は`.env.yaml`ファイルから読み込まれます。
