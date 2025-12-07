<?php

declare(strict_types=1);

namespace App;

use Google\Cloud\Firestore\DocumentReference;
use Google\Cloud\Firestore\FirestoreClient;

/**
 * アプリケーション環境に基づいて設定値を提供します。
 * 環境は`APP_ENV`環境変数によって決定されます。
 *
 * サポートされる環境: 'production', 'test', 'development'。
 * `APP_ENV`が設定されていない場合、デフォルトは'development'です。
 */
class AppConfig
{
    /**
     * 現在のアプリケーション環境を取得します。
     *
     * @return string 現在の環境 ('production', 'test', または 'development')。
     */
    public static function getEnvironment(): string
    {
        return getenv('APP_ENV');
    }

    /**
     * Firestoreのルートコレクション名を取得します。
     *
     * @return string Firestoreコレクションの名前。
     */
    public static function getFirestoreRootCollection(): string
    {
        return match (self::getEnvironment()) {
            'production' => 'daily-quotes',
            'test', => 'daily-quotes-test',
            default => 'daily-quotes-test',
        };
    }

    /**
     * LINEメッセージ配信のターゲットとなるユーザー/グループIDを取得します。
     *
     * @return string LINEターゲットID。
     */
    public static function getLineDeliverTarget(): string
    {
        return match (self::getEnvironment()) {
            'production' => 'stnb',
            'test' => 'nobu',
            default => 'nobu',
        };
    }

    /**
     * アプリケーションのベースパスを取得します。
     *
     * @return string ベースパス。
     */
    public static function getBasePath(): string
    {
        return match (self::getEnvironment()) {
            'production' => '/daily-quote',
            'test' => '/daily-quote-test',
            default => '',
        };
    }

    /**
     * Firestoreクライアントのインスタンスを取得します。
     *
     * @return FirestoreClient Firestoreクライアント。
     */
    public static function getFirestoreClient(): FirestoreClient
    {
        static $firestoreClient = null;
        if ($firestoreClient === null) {
            $firestoreClient = new FirestoreClient();
        }
        return $firestoreClient;
    }

    /**
     * 管理者設定ドキュメントへの参照を取得します。
     *
     * @return DocumentReference 管理者ドキュメントへの参照。
     */
    public static function getAdminDocument(): DocumentReference
    {
        $firestore = self::getFirestoreClient();
        return $firestore->collection(self::getFirestoreRootCollection())
            ->document('admin');
    }
}
