<?php

return [
    // Laragon lokal biasanya: localhost / root / password kosong / bookstore
    // InfinityFree: isi persis sesuai menu MySQL Databases di control panel.
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'bookstore',

    // Dipakai hanya untuk membuat admin pertama melalui setup_admin.php.
    // Ganti dengan string acak panjang dan jangan commit file database.php.
    'setup_key' => 'GANTI_DENGAN_KUNCI_ACAK_PANJANG',
];
