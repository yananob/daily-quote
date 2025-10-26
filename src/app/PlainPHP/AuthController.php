<?php

namespace App\PlainPHP;

require_once __DIR__ . '/../../config.php';

class AuthController
{
    public function showLoginForm()
    {
        require __DIR__ . '/../../../resources/views/auth/login.php';
    }

    public function login()
    {
        $password = $_POST['password'] ?? '';

        if ($password === config('auth_password')) {
            $token = bin2hex(random_bytes(30));
            setcookie('auth_token', $token, time() + (86400 * 90), "/"); // 90 days
            header('Location: /');
            exit;
        }

        $errors = ['password' => 'Invalid password'];
        require __DIR__ . '/../../../resources/views/auth/login.php';
    }

    public function logout()
    {
        setcookie('auth_token', '', time() - 3600, "/");
        header('Location: /login');
        exit;
    }
}