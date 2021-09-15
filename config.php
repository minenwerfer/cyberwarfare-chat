<?php

// timezone
define('DEFAULT_TMZ', 'America/Sao_Paulo');

// date formating
define('DATE_FORMAT', 'j/n/Y G:i\\h');

// where to store settings
define('SETTINGS_PATH', __DIR__ .'/settings');

// where to store sessions
define('STORE_PATH', __DIR__ .'/chats');

// crypto
define('CRYPTO_IV', 'e8957b96915c9266');
define('CRYPTO_SALT', '99c5c58430c349c7d3647b69cda857e7');

// no need to change below
define('BASE_DIR', __DIR__);
define('BASE_URI', dirname($_SERVER['SCRIPT_NAME']));
define('SANITY_STR', 'sanity_ok');
