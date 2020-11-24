<?php

namespace Siaoynli\AliCloud\EMas;

use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Illuminate\Config\Repository;


class EMas
{

    protected $config;
    protected $client;
    protected $push_type = "NOTICE";
    protected $device = "IOS";
    protected $device_id = "ALL";


    public function __construct(Repository $config)
    {
        $this->config = $config->get("alicloud-emas");

        try {
            $this->client = AlibabaCloud::accessKeyClient($this->config["key"], $this->config["secret"])
                ->regionId($this->config["region"])
                ->asDefaultClient();
        } catch (ClientException $e) {
            throw  new ClientException("ClientException :" . $e->getMessage());
        }
    }


    public function pushType($push_type = "NOTICE")
    {
        $this->push_type = $push_type;
        return $this;
    }

    public function device($device = "IOS")
    {
        $this->device = $device;
        return $this;
    }


    public function deviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }


    public function push($title = null, $body = null, $parameters = null)
    {
        if (!$title) {
            throw  new \Exception("请传入Title参数");
        }

        if (!$body) {
            throw  new \Exception("请传入Body参数");
        }

        $client_type = $this->config["client_type"] ?? "http";
        if ($this->device == "IOS") {
            $query = [
                'RegionId' => $this->config["region"],
                'AppKey' => $this->config["emas_app_key"],
                'PushType' => $this->push_type,
                'DeviceType' => "iOS",
                'StoreOffline' => true,
                'Body' => $title,
                'Title' => $body,

                'TargetValue' => $this->config["ios_device_id"],
            ];

            if ($this->config['dev']) {
                $query['iOSApnsEnv'] = 'DEV';
                $query['Target'] = 'DEVICE';
            } else {
                $query['Target'] = 'ALL';
            }

            if ($parameters && is_array($parameters)) {
                $query['iOSExtParameters'] = json_encode($parameters);
            }

        } else {

            $query = [
                'RegionId' => $this->config["region"],
                'AppKey' => $this->config["emas_app_key"],
                'PushType' => $this->push_type,
                'DeviceType' => "ANDROID",
                'StoreOffline' => true,
                'Body' => $title,
                'Title' => $body,
                'TargetValue' => $this->config["android_device_id"],
                'AndroidNotificationChannel' => "1",
                'AndroidNotificationVivoChannel' => "1",
                'AndroidNotificationHuaweiChannel' => "1",
                'AndroidNotificationXiaomiChannel' => "1",
            ];

            if ($this->config['dev']) {
                $query['Target'] = 'DEVICE';
            } else {
                $query['Target'] = 'ALL';
            }
            if ($parameters && is_array($parameters)) {
                $query['AndroidExtParameters'] = json_encode($parameters);
            }

        }

        try {
            $result = AlibabaCloud::rpc()
                ->product('Push')
                ->scheme($client_type)
                ->version('2016-08-01')
                ->action('Push')
                ->method('POST')
                ->host('cloudpush.aliyuncs.com')
                ->options([
                    'query' => $query,
                ])
                ->request();

            $result = $result->toArray();
            if (isset($result["MessageId"])) {
                return ["state" => 1, "info" => $result];
            } else {
                return ["state" => 0, "info" => $result];
            }
        } catch (ClientException $e) {
            throw  \Exception("ClientException :" . $e->getErrorMessage());
        } catch (ServerException $e) {
            return $e->getErrorMessage();
            throw  \Exception("ServerException :" . $e->getErrorMessage());
        }
    }


}
