# 格言配信Cloud Function

このCloud Functionは、Firestoreからランダムに格言を取得し、指定されたLINEユーザーまたはグループに送信します。

## セットアップ

### 1. 依存関係のインストール

プロジェクトのルートディレクトリで以下のコマンドを実行し、必要なライブラリをインストールします。

```bash
composer install
```

### 2. 環境変数の設定

このアプリケーションの設定は、Google Cloud Functionsの環境変数を通じて行います。

#### 必須の環境変数

- `APP_ENV`: アプリケーションの動作環境を指定します。 `production`、`test`のいずれかを設定します。
- `LINE_TOKENS_N_TARGETS`: LINE Messaging APIのチャネルアクセストークンと送信先IDをJSON形式で設定します。
- `GOOGLE_APPLICATION_CREDENTIALS`: Firestoreへのアクセスに必要なサービスアカウントの認証情報を設定します。通常、Cloud Functionsの実行サービスアカウントに適切なIAMロールを付与することで自動的に設定されます。

#### `AppConfig`クラスによる設定管理

アプリケーションの主要な設定は`App\AppConfig`クラスで一元管理されています。`APP_ENV`の値に応じて、Firestoreのコレクション名やLINEの送信先ターゲットが自動的に切り替わります。

- `APP_ENV=production`: 本番環境用の設定が使用されます。
- `APP_ENV=test`: テスト環境用の設定が使用されます。

