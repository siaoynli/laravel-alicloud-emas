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
    protected $app_key = "";
    protected $device = "IOS";
    protected $device_id = "";
    protected $target = "DEVICE";


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
        $this->app_key = $this->config["app_key"];
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

    public function target($target = "DEVICE")
    {
        $this->target = $target;
        return $this;
    }


    public function deviceId($device_id)
    {
        $this->device_id = $device_id;
        return $this;
    }


    public function push($title = null, $body = null, $parameters = [])
    {
        if (!$title) {
            throw  new \Exception("请传入Title参数");
        }

        if (!$body) {
            throw  new \Exception("请传入Body参数");
        }


        if (!$this->device_id) {
            throw  new \Exception("请传入TargetValue参数");
        }

        if ($this->config['dev'] && $this->device_id == "ALL") {
            throw  new \Exception("开发模式下，请传具体设备id");
        }

        $client_type = $this->config["client_type"] ?? "https";

        $query = [
            'RegionId' => $this->config["region"],
            'AppKey' => $this->app_key,
            'PushType' => $this->push_type,
            'StoreOffline' => true,
            'Title' => $title,
            'Body' => $body,
            'TargetValue' => $this->device_id,
            'Target' => $this->target,
        ];


        if ($this->device == "IOS") {
            $query['DeviceType'] = 'iOS';
            if ($this->config['dev']) {
                $query['iOSApnsEnv'] = 'DEV';
            }
            if ($parameters && is_array($parameters)) {
                $query['iOSExtParameters'] = json_encode($parameters);
            }

        } else {
            $query['DeviceType'] = "ANDROID";
            $query['AndroidNotificationChannel'] = "1";
            $query['AndroidNotificationVivoChannel'] = "1";
            $query['AndroidNotificationHuaweiChannel'] = "1";
            $query['AndroidNotificationXiaomiChannel'] = "1";
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
            throw  new \Exception("ClientException :" . $e->getErrorMessage());
        } catch (ServerException $e) {
            return $e->getErrorMessage();
            throw  new \Exception("ServerException :" . $e->getErrorMessage());
        }
    }


}
