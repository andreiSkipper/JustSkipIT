<?php
return [
    'adminEmail' => 'admin@example.com',
    'supportEmail' => 'support@example.com',
    'user.passwordResetTokenExpire' => 3600,
    'languages' => [
        "English" => [
            "src" => "/resources/images/flags/gb.png",
            "url" => ['/site/language?language=en-US'],
            "icon" => "flag-en"
        ],
        "Romanian" => [
            "src" => "/resources/images/flags/fr.png",
            "url" => ['/site/language?language=ro-RO'],
            'icon' => "flag-ro"
        ],
//        "French" => [
//            "src" => "/resources/images/flags/fr.png",
//            "url" => ['/site/language?language=fr-FR"']
//        ],
//        "German" => [
//            "src" => "/resources/images/flags/de.png",
//            "url" => ['/site/language?language=de-DE"']
//        ],
    ],
];
