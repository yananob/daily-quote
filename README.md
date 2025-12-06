# 格言配信Cloud Function

このCloud Functionは、Firestoreからランダムに格言を取得し、指定されたLINEユーザーまたはグループに送信します。

## セットアップ

### 1. 依存関係のインストール

プロジェクトのルートディレクトリで以下のコマンドを実行し、必要なライブラリをインストールします。

```bash
composer install
```

### 2. 環境変数の設定

このアプリケーションは、動作環境に応じて異なる方法で環境変数を読み込みます。

#### ローカル開発環境

ローカルで開発やテストを行う場合、`.env`ファイルを使用します。

1.  `.env.sample`ファイルをコピーして`.env`ファイルを作成します。
    ```bash
    cp .env.sample .env
    ```
2.  `.env`ファイルを開き、各環境変数を設定します。

    ```dotenv
    # LINE Messaging APIのチャネルアクセストークン
    LINE_TOKENS_N_TARGETS="your_LINE_TOKENS_N_TARGETS"
    # LINEメッセージを送信する対象のユーザーIDまたはグループID
    LINE_DELIVER_TARGET="your_line_user_or_group_id"
    ```

#### `APP_ENV` 環境変数について

`APP_ENV`環境変数を使用することで、読み込む`.env`ファイルを切り替えることができます。例えば、`APP_ENV=testing`と設定されている場合、アプリケーションは`.env.testing`と`.env`の両方のファイルを読み込みます（`.env.testing`が優先されます）。これにより、テスト環境などで異なる設定を簡単に利用できます。

#### Google Cloud Functions 環境

Cloud Functionsにデプロイする際は、`.env`ファイルを使用して環境変数を設定します。

```
# .env
LINE_TOKENS_N_TARGETS=your_LINE_TOKENS_N_TARGETS
LINE_DELIVER_TARGET=your_line_user_or_group_id
```

このファイルを`gcloud functions deploy`コマンドの`--env-vars-file`オプションで指定します。

## ブートストラップ処理

アプリケーションの初期化処理（ブートストラップ）は、実行されるコンテキストに応じて異なるファイルで処理されます。

### アプリケーション本体の初期化

Cloud Functions環境で実行されるアプリケーション本体の初期化は、エントリポイントである`index.php`ファイル内の`initialize()`関数が担当します。この関数は、環境変数の読み込みや必須設定の検証などを行います。

### テスト実行時の初期化

PHPUnitによるテストを実行する際には、`tests/bootstrap_phpunit.php`ファイルが使用されます。このファイルは、テスト専用の環境変数（`.env.testing`など）を読み込み、テストに必要な初期化処理を行います。

### ローカルHTTPサーバー実行時の初期化

ローカル環境でPHPのビルトインサーバーを起動して動作確認を行う場合、`tests/bootstrap_http.php`が使用されます。このファイルは、Google Cloud Functions Frameworkのルーターを読み込み、ローカルでのリクエストを処理できるようにします。

## デプロイ

`gcloud`コマンドラインツールを使用して、このファンクションをGoogle Cloud Functionsにデプロイします。

```bash
gcloud functions deploy main_event \\
  --runtime php82 \\
  --trigger-topic daily-quote-trigger \\
  --entry-point main_event \\
  --env-vars-file .env.yaml
```
