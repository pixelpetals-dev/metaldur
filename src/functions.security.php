<?php

use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManager;

$csrf = new CsrfTokenManager();

function csrf_token(): string
{
    global $csrf;
    return $csrf->getToken('form')->getValue();
}

function csrf_guard_request(string $token): bool
{
    global $csrf;
    return $csrf->isTokenValid(new CsrfToken('form', $token));
}


function encryptMessage(string $plaintext): ?string
{
    if ($plaintext === '') return '';

    $key = hex2bin(WHATSAPP_ENC_KEY);
    $iv  = random_bytes(openssl_cipher_iv_length('aes-256-gcm'));

    $tag  = '';
    $enc  = openssl_encrypt($plaintext, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

    if ($enc === false) return null;

    return base64_encode($iv . $tag . $enc);
}

function decryptMessage(?string $ciphertext): string
{
    if (!$ciphertext) return '';

    $raw = base64_decode($ciphertext, true);
    if ($raw === false) return '';

    $key = hex2bin(WHATSAPP_ENC_KEY);
    $iv_len = openssl_cipher_iv_length('aes-256-gcm');
    $iv  = substr($raw, 0, $iv_len);
    $tag = substr($raw, $iv_len, 16);
    $enc = substr($raw, $iv_len + 16);

    $dec = openssl_decrypt($enc, 'aes-256-gcm', $key, OPENSSL_RAW_DATA, $iv, $tag);

    return $dec === false ? '' : $dec;
}
