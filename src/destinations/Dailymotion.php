<?php

namespace Artec3D\destinations;

use Artec3D\Configs;
use Artec3D\destinations\Destination as Destination;
use Dailymotion as DailymotionSDK;

// https://developer.dailymotion.com/tools/
class Dailymotion extends Destination
{
    /**
     * @var DailymotionSDK
     */
    private $api;

    // Credentials
    private $api_key;
    private $api_secret;
    private $user;
    private $password;
    private $channel;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setCredentials();

        $scopes = array(
            'manage_videos',
        );
        $api = new DailymotionSDK();
        try {
            $api->setGrantType(
                DailymotionSDK::GRANT_TYPE_PASSWORD,
                $this->api_key,
                $this->api_secret,
                $scopes,
                array(
                    'username' => $this->user,
                    'password' => $this->password,
                ));
            $this->api = $api;
        } catch (\DailymotionAuthRequiredException $e) {

        }
    }

    protected function setCredentials(): void
    {
        $credentials = json_decode(file_get_contents(Configs::DESTINATIONS))->{$this->getName()};

        $this->api_key = $credentials->apiKey;
        $this->api_secret = $credentials->apiSecret;
        $this->user = $credentials->user;
        $this->password = $credentials->password;
        $this->channel = $credentials->channel;
    }

    public function upload($file)
    {
        try {
            $url = $this->api->uploadFile($file['file_name']);
            $prepared = [
                'url'       => $url,
                'title'     => $file["name"],
                'tags'      => '',
                'channel'   => $this->channel,
                'published' => true,
            ];
            $res = $this->api->post(
                '/videos',
                $prepared
            );
            return $res;
        } catch (\DailymotionApiException $e) {
            $e->getCode();
        }

    }
}