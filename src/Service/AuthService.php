<?php

declare(strict_types=1);

namespace App\Service;

use App\AppConfig;
use App\Service\FirestoreService;

class AuthService extends BaseService
{
    private const COOKIE_NAME = 'auth_token';
    private const COOKIE_EXPIRATION_SECONDS = 60 * 60 * 24 * 7; // 1 week

    public function __construct()
    {
        parent::__construct();
        $this->logger->info('[AuthService] __construct called.');
    }

    public function isAuthenticated(): bool
    {
        $this->logger->info('[AuthService] isAuthenticated called.');
        if (!isset($_COOKIE[self::COOKIE_NAME])) {
            $this->logger->info('[AuthService] isAuthenticated: COOKIE_NAME not set, returning false.');
            return false;
        }

        $token = $_COOKIE[self::COOKIE_NAME];
        $expectedToken = $this->getAuthToken();
        $isAuthenticated = hash_equals($expectedToken, $token);
        $this->logger->info('[AuthService] isAuthenticated: Token comparison result: ' . ($isAuthenticated ? 'true' : 'false'));
        return $isAuthenticated;
    }

    public function attempt(string $password): bool
    {
        $this->logger->info('[AuthService] attempt called.');
        $storedPassword = $this->getPasswordFromFirestore();
        if ($storedPassword && password_verify($password, $storedPassword)) {
            $this->logger->info('[AuthService] attempt: Password verified successfully. Setting auth cookie.');
            $this->setAuthCookie();
            return true;
        }
        $this->logger->info('[AuthService] attempt: Password verification failed.');
        return false;
    }

    public function logout(): void
    {
        $this->logger->info('[AuthService] logout called.');
        setcookie(self::COOKIE_NAME, '', time() - 3600, '/');
        $this->logger->info('[AuthService] logout: Auth cookie cleared.');
    }

    private function getPasswordFromFirestore(): ?string
    {
        $this->logger->info('[AuthService] getPasswordFromFirestore called.');
        $document = FirestoreService::getClient()
            ->collection(AppConfig::getFirestoreRootCollection())
            ->document('admin')
            ->snapshot();
        if ($document->exists()) {
            $password = $document->get('password');
            $this->logger->info('[AuthService] getPasswordFromFirestore: Document exists, password retrieved.');
            return $password;
        }
        $this->logger->info('[AuthService] getPasswordFromFirestore: Document does not exist, returning null.');
        return null;
    }

    private function getAuthToken(): string
    {
        $this->logger->info('[AuthService] getAuthToken called.');
        // A simple token based on the stored password and a secret key.
        // In a real application, use a more secure method.
        $storedPassword = $this->getPasswordFromFirestore();
        $token = hash('sha256', $storedPassword . ($_ENV['APP_SECRET'] ?? 'default_secret'));
        $this->logger->info('[AuthService] getAuthToken: Token generated.');
        return $token;
    }

    private function setAuthCookie(): void
    {
        $this->logger->info('[AuthService] setAuthCookie called.');
        $token = $this->getAuthToken();
        $expires = time() + self::COOKIE_EXPIRATION_SECONDS;
        setcookie(self::COOKIE_NAME, $token, $expires, '/', '', true, true);
        $this->logger->info('[AuthService] setAuthCookie: Auth cookie set. Expires: ' . date('Y-m-d H:i:s', $expires));
    }

    public function setPassword(string $password): void
    {
        $this->logger->info('[AuthService] setPassword called.');
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        FirestoreService::getClient()
            ->collection(AppConfig::getFirestoreRootCollection())
            ->document('admin')
            ->set([
                'password' => $hashedPassword,
            ]);
        $this->logger->info('[AuthService] setPassword: Password hashed and stored in Firestore.');
    }
}
