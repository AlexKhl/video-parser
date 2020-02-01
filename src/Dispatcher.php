<?php

namespace Artec3D;

use Artec3D\DestinationPool as DestPool;
use Artec3D\Logs as Logs;
use Artec3D\destinations\Dailymotion as Dailymotion;

class Dispatcher
{
    private $url;

    public function __construct($url)
    {
        $this->url = $url;
    }

    public function handle()
    {
        $parser = new Parser();
        $yt_links = $parser->getYTLinks($this->url);

        $downloader = new Downloader(Configs::VIDEO_STORE);
        $downloaded_files_meta = [];
        foreach ($yt_links as $yt_link){
            $prepared_links = $downloader->getDownloadLink($yt_link);
            $downloaded_files_meta[$downloader->getVideoId()]["video_id"] = $downloader->download($prepared_links);
            $downloaded_files_meta[$downloader->getVideoId()]["name"] = $downloader->getVideoName();
            $downloaded_files_meta[$downloader->getVideoId()]["file_name"] = $downloader->getFileName();
        }

        $pool = new DestinationPool();
        $destinations_list = json_decode(file_get_contents(Configs::DESTINATIONS));
        foreach ($destinations_list as $name => $address){
            $dest = $pool->getDestination($name);
        }

        $uploader = new Uploader();
        foreach ($destinations_list as $name => $address){
            $dest = $pool->getDestination($name);
            foreach ($downloaded_files_meta as $file){
                $uploader->uploadToDestination($dest->setStream());
            }
        }
    }
}