<?php

namespace Artec3D\Destinations;

use Artec3D\Configs;
use Artec3D\destinations\Destination as Destination;
use Google_Client as Client;
use Google_Service_YouTube as Service;
use Google_Service_YouTube_Video as Video;
use Google_Service_YouTube_VideoSnippet as VideoSnippet;
use Google_Service_YouTube_VideoStatus as VideoStatus;

// https://developers.google.com/youtube/v3/code_samples/code_snippets?apix=true
class YouTube extends Destination
{
    private $api_key;
    /**
     * @var Client
     */
    private $client;
    /**
     * @var Service
     */
    private $service;
    /**
     * @var Video
     */
    private $video;
    /**
     * @var VideoSnippet
     */
    private $video_snippet;
    /**
     * @var VideoStatus
     */
    private $video_status;
    private $category_id;

    public function __construct($name)
    {
        parent::__construct($name);
        $this->setCredentials();

        $this->client = new Client();
        $this->client->setApplicationName("YT Uploader");
        $this->client->setDeveloperKey($this->api_key);

        $this->service = new Service($this->client);

        $this->video = new Video();

        $this->video_snippet = new VideoSnippet();

        $this->videoStatus = new VideoStatus();
    }

    protected function setCredentials()
    {
        $credentials = json_decode(file_get_contents(Configs::DESTINATIONS))->{$this->getName()};

        $this->api_key = $credentials->apiKey;
        $this->category_id = $credentials->category;
    }

    public function upload($file)
    {
        $this->video_snippet->setCategoryId($this->category_id); // https://gist.github.com/dgp/1b24bf2961521bd75d6c
        $this->video_snippet->setDescription($file["name"]);
        $this->video_snippet->setTitle($file["name"]);
        $this->video->setSnippet($this->video_snippet);
//        $this->video_status->setPrivacyStatus('public');
//        $this->video->setStatus($this->video_status);

        $response = $this->service->videos->insert(
            'snippet,status',
            $this->video,
            array(
                'data' => file_get_contents($file["file_name"]),
                'mimeType' => 'video/*',
                'uploadType' => 'multipart'
            )
        );

        return $response;
    }
}