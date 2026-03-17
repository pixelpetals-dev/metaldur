<?php
return [
    'mail_host'     => getenv('MAIL_HOST') ?: 'mail.metaldur.in',
    'mail_username' => getenv('MAIL_USERNAME') ?: 'admin@metaldur.in',
    'mail_password' => getenv('MAIL_PASSWORD') ?: '[IOp7fCrpf!.cXnl',
    'mail_port'     => (int)(getenv('MAIL_PORT') ?: 465),
    'mail_from'     => getenv('MAIL_FROM') ?: 'admin@metaldur.in',
    'mail_from_name'=> getenv('MAIL_FROM_NAME') ?: 'METALDUR CUTTING TOOLS',
    'dsn'      => getenv('DB_DSN') ?: 'mysql:host=localhost;dbname=metaldur;charset=utf8mb4',
    'username' => getenv('DB_USERNAME') ?: 'metaldur_erp',
    'password' => getenv('DB_PASSWORD') ?: 'metaldur@5253',
];
?>
