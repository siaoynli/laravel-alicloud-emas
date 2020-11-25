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
Siaoynli\AliCloud\EMas\LaravelAliCloudEMasServerProvider::class   
```
to the providers array in config/app.php

```
php artisan vendor:publish --provider="Siaoynli\AliCloud\EMas\LaravelAliCloudEMasServerProvider"
```


## alias

```
 "EMas" => \Siaoynli\AliCloud\EMas\Facades\EMas::class,
```

## 使用方法

```php

use Siaoynli\AliCloud\EMas\Facades\EMas;
//单客户端
EMas::deviceId("f72fb02413304ad8a16c017c3a")->push("测试","测试包");
//所有客户端
EMas::deviceId("ALL")->push("测试","测试包");
```

返回结果
```php
//成功
{
"state": 1,
"info": {
          "RequestId": "4AC202AB-87CE-4641-A02E-5FBCF99DF336",
          "MessageId": "2386074847883392"
        }
}
//失败
{
"state": 0,
"info": ""
}



```

