# SQLite to Firestore 移行スクリプト

このスクリプトは、SQLiteデータベースの`quotes`テーブルからデータを読み取り、Google Firestoreに移行するために使用します。

## 注意事項

現在、開発環境において、スクリプトの依存関係である`google/cloud-firestore`ライブラリのインストールに問題が確認されています。これは、`google/cloud-firestore`が必要とする`grpc`というPHP拡張機能のインストールが正常に完了しないためです。

この問題により、スクリプト内のFirestoreへの書き込み処理は、デフォルトでコメントアウトされています。スクリプトを完全な状態で実行するには、まず`grpc`拡張機能と`google/cloud-firestore`ライブラリをインストールできる環境を準備し、スクリプト内のコメントアウトを解除する必要があります。

現在のバージョンでは、スクリプトはSQLiteデータベースからデータを読み取り、コンソールにその内容を出力する「ドライラン」モードで動作します。

## セットアップ手順

### 1. 依存関係のインストール

スクリプトを実行する前に、必要なPHPライブラリをComposerを使用してインストールします。

```bash
cd misc
php composer.phar install
```

### 2. 環境変数の設定

スクリプトは、`.env`ファイルからデータベースのパスなどの設定を読み込みます。`.env.example`をコピーして`.env`ファイルを作成し、内容を編集してください。

```bash
cp .env.example .env
```

次に、`.env`ファイルをテキストエディタで開き、各変数を設定します。

- `SQLITE_DB_PATH`: 移行元のSQLiteデータベースファイルへのパスを指定します。
- `FIRESTORE_PROJECT_ID`: データの移行先であるGoogle CloudプロジェクトのIDを指定します。 (現在は未使用)

**`.env`ファイルの設定例:**

```
SQLITE_DB_PATH=../src/database/database.sqlite
FIRESTORE_PROJECT_ID=my-gcp-project-id
```

### 3. スクリプトの実行

設定が完了したら、以下のコマンドで移行スクリプトを実行します。

```bash
php migrate_sqlite_to_firestore.php
```

## Firestoreへの移行を有効にする方法 (上級者向け)

`grpc`拡張機能が利用可能な環境では、以下の手順でFirestoreへのデータ移行を有効にできます。

1.  `misc/composer.json`に`google/cloud-firestore`ライブラリを追加します。

    ```json
    {
        "require": {
            "google/cloud-firestore": "^1.34",
            "vlucas/phpdotenv": "^5.6"
        }
    }
    ```

2.  依存関係を再インストールします。

    ```bash
    composer install
    ```

3.  `migrate_sqlite_to_firestore.php`を開き、Firestoreに関連するコードブロックのコメントアウトを解除します。

    - `use Google\Cloud\Firestore\FirestoreClient;`
    - Firestoreの接続設定とクライアント初期化の部分
    - `foreach`ループ内のFirestoreへの書き込み処理 (`$quotesCollection->document($docId)->set($data);`)

4.  再度スクリプトを実行すると、データがFirestoreに書き込まれます。
