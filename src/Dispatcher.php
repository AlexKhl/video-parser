<?php

namespace Artec3D;

use Artec3D\DestinationPool as DestPool;
use Artec3D\destinations\Destination;
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
        //// Parser
        // Define parser
        $parser = new Parser();
        // Parse links from a page
        $yt_links = $parser->getYTLinks($this->url);
        //// Downloader
        // Define downloader
        $downloader = new Downloader(Configs::VIDEO_STORE);
        $downloaded_files_meta = [];
        // Download videos
        foreach ($yt_links as $yt_link){
            $prepared_links = $downloader->getDownloadLink($yt_link);
            $downloader->download($prepared_links);
            $downloaded_files_meta[$yt_link]["video_id"] = $yt_link;
            $downloaded_files_meta[$yt_link]["name"] = $prepared_links[0]["title"];
            $downloaded_files_meta[$yt_link]["file_name"] = Configs::VIDEO_STORE."/".$yt_link.".".$prepared_links[0]["format"];
        }

        //// Objects pool
        // Define pool of destination objects
        $pool = new DestinationPool();
        $destinations_list = json_decode(file_get_contents(Configs::DESTINATIONS));
        foreach ($destinations_list as $name => $credents){
            $pool->getDestination($name);
        }

        // Uploader
        $output = new Output();
        foreach ($destinations_list as $name => $credents){
            foreach ($downloaded_files_meta as $file_id => $file){
                $dest = $pool->getDestination($name);
                $result = $dest->upload($file);
                $output->stdOutput($result);
            }
        }
    }
}