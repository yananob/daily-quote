<?php

declare(strict_types=1);

namespace App\Service;

use Google\Cloud\Firestore\FirestoreClient;

class AuthService
{
    private const COOKIE_NAME = 'auth_token';
    private const COOKIE_EXPIRATION_SECONDS = 60 * 60 * 24 * 7; // 1 week

    private FirestoreClient $firestore;

    public function __construct()
    {
        $this->firestore = new FirestoreClient();
    }

    public function isAuthenticated(): bool
    {
        if (!isset($_COOKIE[self::COOKIE_NAME])) {
            return false;
        }

        $token = $_COOKIE[self::COOKIE_NAME];
        $expectedToken = $this->getAuthToken();

        return hash_equals($expectedToken, $token);
    }

    public function attempt(string $password): bool
    {
        $storedPassword = $this->getPasswordFromFirestore();
        if ($storedPassword && password_verify($password, $storedPassword)) {
            $this->setAuthCookie();
            return true;
        }
        return false;
    }

    public function logout(): void
    {
        setcookie(self::COOKIE_NAME, '', time() - 3600, '/');
    }

    private function getPasswordFromFirestore(): ?string
    {
        $document = $this->firestore->collection('admin')->document('admin')->snapshot();
        if ($document->exists()) {
            return $document->get('password');
        }
        return null;
    }

    private function getAuthToken(): string
    {
        // A simple token based on the stored password and a secret key.
        // In a real application, use a more secure method.
        $storedPassword = $this->getPasswordFromFirestore();
        return hash('sha256', $storedPassword . ($_ENV['APP_SECRET'] ?? 'default_secret'));
    }

    private function setAuthCookie(): void
    {
        $token = $this->getAuthToken();
        $expires = time() + self::COOKIE_EXPIRATION_SECONDS;
        setcookie(self::COOKIE_NAME, $token, $expires, '/', '', true, true);
    }

    public function setPassword(string $password): void
    {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $this->firestore->collection('admin')->document('admin')->set([
            'password' => $hashedPassword,
        ]);
    }
}
