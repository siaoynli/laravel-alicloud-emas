<?php
return [
    "key" => env("ACCESS_KEY_ID"),
    "secret" => env("ACCESS_KEY_SECRET"),
    "region" => env("REGION_ID"),
    "app_key" => env("EMAS_APP_KEY", "23629708"),
    "dev" => env("EMAS_DEV", true),
];

