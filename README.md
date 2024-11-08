# daily-quote

IMPORTANT: artisan serve使ってるうちは、公開しない！

## SQLiteDB

- Google Storageに保管
- 起動時にGStorageから取得 → Terminate時にGStorageに戻す　としている
  - command/serve.sh で制御している（Laravel自体はいじってない）
