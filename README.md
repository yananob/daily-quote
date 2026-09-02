# 格言配信Cloud Function

このアプリケーションは、Google Cloud Functions（PHP runtime）上で動作する格言管理Web UIおよびLINE格言配信Cloud Functionです。

Firestoreに保存された格言の登録・編集・削除・一覧表示（統計情報付き）の管理機能と、イベントトリガーによるLINE Messaging APIへの自動格言配信機能を提供します。

---

## アーキテクチャと特徴

- **ランタイム**: PHP 8.2+ (Google Cloud Functions Framework for PHP)
- **データベース**: Google Cloud Firestore (Native Mode)
- **テンプレートエンジン**: BladeOne (`views/` ディレクトリ内のBladeテンプレートを使用)
- **日付処理**: `Carbon\Carbon` を使用
- **静的解析**: PHPStan (Level 5)

---

## エントリポイント (`index.php`)

`index.php` は2つの主要な Cloud Functions エントリポイントを提供します:

1. **`main_http` (HTTP Functions)**
   - 格言の一覧表示、新規作成、編集、更新、削除を行うWeb UIコントローラー（`App\Http\QuotesController`）にリクエストをルーティングします。
2. **`main_event` (Cloud Events)**
   - 定期実行イベント（Pub/Subトリガー等）で呼び出され、配信優先度の高い格言を1件抽出し、LINE Messaging API経由でプッシュ送信します。

---

## セットアップ

### 1. 依存関係のインストール

プロジェクトのルートディレクトリで以下のコマンドを実行し、必要なライブラリをインストールします。

```bash
composer install
```

### 2. 環境変数の設定

アプリケーションの設定は環境変数を通じて行います。

#### 主要な環境変数

- `APP_ENV`: 実行環境を指定します (`production`, `test`, `development`)。未指定時のデフォルトは `development` です。
- `LINE_TOKENS_N_TARGETS`: LINE Messaging APIのチャネルアクセストークンと送信先ターゲットIDをJSON形式で設定します。
- `FIREBASE_SERVICE_ACCOUNT`: ローカル開発や外部環境等でサービスアカウント認証情報を明示的に渡す場合にJSON形式で指定します。

#### `AppConfig` クラスによる設定管理

主要な設定は `App\AppConfig` クラスで管理されています。`APP_ENV` の値に応じ、Firestoreコレクション名 (`daily-quotes` / `daily-quotes-test`) や配信ターゲットが自動的に決定されます。

---

## 格言の選択ロジック

配信される格言が偏らないように、以下のロジックで選択されます:

1. 各格言には「配信回数 (`delivered_count`)」が記録されています。
2. 配信時には、全格言の中で最も配信回数が少ない格言のみが候補として抽出されます。
3. 抽出された候補の中から、実行時の日本標準時 (Asia/Tokyo) 日時（`YmdHisu`）をシードとした乱数によって1つが選ばれます。
4. 配信が完了すると、選ばれた格言の配信回数がインクリメントされます。

新規登録された格言は配信回数 0 として初期化されるため、既存の格言よりも優先的に配信対象となります。

---

## 静的解析（PHPStan）の実行

コードの品質維持のため、PHPStanによる静的解析が導入されています。

```bash
./vendor/bin/phpstan analyze src --level=5 --memory-limit=1G
```
