<?php


namespace Artec3D;


class Downloader
{
    private $videos_store;

    /**
     * Downloader constructor.
     * @param string $video_store
     */
    public function __construct(string $video_store)
    {
        $this->videos_store = $video_store;
    }

    public function getDownloadLink($yt_link)
    {
        parse_str($this->getVideoInfo($yt_link), $data);
        $video_title = json_decode($data["player_response"])->videoDetails->title;
        $streaming_data = $this->getStreamData($yt_link);
        $streaming_map = [];

        foreach ($streaming_data->formats as $stream){
            $stream_data["video_id"] = $yt_link;
            $stream_data["title"] = $video_title;
            $stream_data["quality"] = $stream->quality;
            $stream_data["url"] = $stream->url;
            $stream_data["mime"] = $stream->mimeType;
            $mime_type = explode(";", $stream_data["mime"]);
            $stream_data["mime"] = $mime_type[0];
            $start = stripos($mime_type[0], "/");
            $format = ltrim(substr($mime_type[0], $start), "/");
            $stream_data["format"] = $format;
            unset($stream_data["type"]);

            $streaming_map[] = $stream_data;
        }
        return $streaming_map;
    }

    private function getVideoInfo($video_id)
    {
        return file_get_contents("https://www.youtube.com/get_video_info?video_id=".$video_id."&cpn=CouQulsSRICzWn5E&eurl&el=adunit");
    }

    private function getStreamData($video_id)
    {
        parse_str($this->getVideoInfo($video_id), $data);
        return json_decode($data["player_response"])->streamingData;
    }

    public function download(array $prepared_links)
    {
        $video_format = $prepared_links[0]['format'];
        $video_id = $prepared_links[0]['video_id'];
        //$video_file_name = strtolower(str_replace(' ', '_', $video_id)).'.'.$video_format;
        $source = $prepared_links[0]['url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec ($ch);
        $error = curl_error($ch);
        curl_close ($ch);

        $destination = $this->videos_store."/".$video_id.".".$video_format;
        $file = fopen($destination, "wb");
        fwrite($file, $data);
        fclose($file);

        return $video_id;
    }
}