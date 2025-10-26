<?php

function config($key, $default = null)
{
    $config = [
        'google_cloud_project' => getenv('GOOGLE_CLOUD_PROJECT'),
        'myapp_deliver_target' => getenv('MYAPP_DELIVER_TARGET'),
        'auth_password' => getenv('AUTH_PASSWORD') ?: 'password',
    ];

    return $config[$key] ?? $default;
}