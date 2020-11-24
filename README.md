# laravel-alicloud-emas

#### 项目介绍

阿里云移动通知推送

## install

this package  for laravel

```
composer require siaoynli/laravel-alicloud-emas
```
add the   
```
Siaoynli\AliCloud\Sms\LaravelAliCloudSmsServerProvider::class   
```
to the providers array in config/app.php

```
php artisan vendor:publish --provider="Siaoynli\AliCloud\Sms\LaravelAliCloudSmsServerProvider"
```


## alias

```
 "Sms" => \Siaoynli\AliCloud\Sms\Facades\Sms::class,
```

## 使用方法

```php

use Siaoynli\AliCloud\Sms\Facades\Sms;

$message=[
  "code"=>"1234",  //code 对应模板里面的code 变量
  "product"=>"xx网", //product 对应模板里面的product 变量
];
 
          $result=Sms::to("18906715000")->signName("注册验证")->template("SMS_29010034")->send($message);
```

返回结果
```php
  "state" => 1
  "info" => array:4 [▼
    "Message" => "OK"
    "RequestId" => "A8A513E0-E631-4929-882B-7219D01F0E26"
    "BizId" => "442107374224819990^0"
    "Code" => "OK"
  ]

//或者
array:2 [▼
  "state" => 0
  "info" => array:3 [▼
    "Message" => "触发分钟级流控Permits:1"
    "RequestId" => "89BFE73F-84FC-42D7-90F8-AFFA6C42EB73"
    "Code" => "isv.BUSINESS_LIMIT_CONTROL"
  ]
]

```
