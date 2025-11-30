<?php

declare(strict_types=1);

// Composerのオートローダーはテスト時にも必要やで
require dirname(__DIR__) . '/vendor/autoload.php';

use Dotenv\Dotenv;

// 1. PHPUnitによって APP_ENV="testing" がセットされてるはずやけど、念の為チェック！
$environment = $_ENV['APP_ENV'] ?? 'development'; 

// 2. ルートディレクトリ（.envがある場所）を指定する
$rootPath = dirname(__DIR__); 

// 3. どのファイルを読み込むか決める
$files = ['.env']; // まずデフォルトをリストに入れる

// 4. testing環境なら .env.testing をリストの先頭に追加する
if ($environment === 'testing') {
    // リストの先頭に追加して優先的に読み込ませるで！
    array_unshift($files, '.env.testing');
}

// 5. Dotenvをロードする
$dotenv = Dotenv::createImmutable($rootPath, $files);

// .envファイルが存在しない場合でも、エラーを出さずに読み込みをスキップしてくれる
// $dotenv->safeLoad();
$dotenv->load();

// これで、テストコード内で $_ENV['DB_NAME'] が "my_app_test" になってるはずや！

// さらに、もし必要ならテスト用のDB接続やアプリケーションサービスの初期化をここに書くんや...
// 例: \App\TestDatabaseManager::resetDatabase();
