<?php

require_once __DIR__ . '/vendor/autoload.php';

use Google\Cloud\Firestore\FirestoreClient;
use Dotenv\Dotenv;

// =================================================================
//  NOTE
// =================================================================
//
// This script is designed to migrate data from a SQLite database to Google Firestore.
// However, the Google Firestore library (`google/cloud-firestore`) could not be installed
// in the development environment due to a persistent issue with the `grpc` PHP extension,
// which is a required dependency.
//
// As a result, all Firestore-related code has been commented out. To make this script
// fully functional, you must:
//
// 1. Ensure you are in an environment where `grpc` can be successfully installed.
// 2. Add `google/cloud-firestore` to the `composer.json` file:
//    {
//        "require": {
//            "google/cloud-firestore": "^1.34",
//            "vlucas/phpdotenv": "^5.6"
//        }
//    }
// 3. Run `composer install` to install the dependency.
// 4. Uncomment the Firestore-related code blocks in this script.
//
// The script will currently read from the SQLite database and print the data to the console.
//
// =================================================================

// =================================================================
//  Configuration
// =================================================================

// Load environment variables from .env file
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// --- SQLite Configuration ---
$sqliteDbPath = $_ENV['SQLITE_DB_PATH'] ?? null;
if (!$sqliteDbPath) {
    die("Error: SQLITE_DB_PATH is not defined in the .env file.\n");
}

// --- Firestore Configuration ---
$firestoreProjectId = $_ENV['FIRESTORE_PROJECT_ID'] ?? null;
if (!$firestoreProjectId) {
    die("Error: FIRESTORE_PROJECT_ID is not defined in the .env file.\n");
}
$firestoreCollectionPath = 'daily-quotes-test/quotes/quotes';

// =================================================================
//  Main Script
// =================================================================

try {
    // --- Connect to SQLite ---
    if (!file_exists($sqliteDbPath)) {
        throw new Exception("SQLite database file not found at: {$sqliteDbPath}");
    }
    $pdo = new PDO('sqlite:' . $sqliteDbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Successfully connected to SQLite database.\n";

    // --- Connect to Firestore ---
    echo "Connecting to Firestore (Project ID: {$firestoreProjectId})...\n";
    $firestore = new FirestoreClient([
        'projectId' => $firestoreProjectId,
    ]);
    $quotesCollection = $firestore->collection($firestoreCollectionPath);
    echo "Successfully connected to Firestore.\n";

    // --- Read from SQLite ---
    $stmt = $pdo->query('SELECT id, message, author, source, source_link FROM quotes');
    $quotes = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Found " . count($quotes) . " quotes to migrate.\n";

    // --- Write to Firestore ---
    $count = 0;
    foreach ($quotes as $quote) {
        $docId = (string)$quote['id'];
        $data = [
            'no' => (int)$quote['id'],
            'message' => $quote['message'],
            'author' => $quote['author'],
            'source' => $quote['source'] ?? '',
            'source_link' => $quote['source_link'] ?? '',
        ];

        // Uncomment the following block to enable Firestore writing
        try {
            $quotesCollection->document($docId)->set($data);
            echo "  [{$docId}] Migrated: {$data['message']}\n";
            $count++;
        } catch (Exception $e) {
            echo "  [ERROR][{$docId}] Failed to migrate quote: " . $e->getMessage() . "\n";
        }

        // This block is for demonstration purposes while Firestore is disabled
        // echo "  [DRY RUN][{$docId}] Data: " . json_encode($data) . "\n";

        // // for test
        // if ((int)$quote['id'] > 50) {
        //     break;
        // }
    }

    echo "\nMigration process complete.\n";
    echo "Successfully migrated {$count} quotes to Firestore.\n";
    // echo "Processed " . count($quotes) . " quotes (Dry Run).\n";

} catch (Exception $e) {
    die("An error occurred: " . $e->getMessage() . "\n");
}
