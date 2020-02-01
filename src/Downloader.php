<?php


namespace Artec3D;


class Downloader
{
    private $videos_store;

    private $video_id;
    private $video_title;
    private $video_format;

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
        $this->video_id = $yt_link;
        parse_str($this->getVideoInfo(), $data);
        $this->video_title = json_decode($data["player_response"])->videoDetails->title;
        $streaming_data = $this->getStreamData();
        $streaming_map = [];

        foreach ($streaming_data->formats as $stream){
            $stream_data["title"] = $this->video_title;
            $stream_data["quality"] = $stream->quality;
            $stream_data["url"] = $stream->url;
            $stream_data["mime"] = $stream->mimeType;
            $mime_type = explode(";", $stream_data["mime"]);
            $stream_data["mime"] = $mime_type[0];
            $start = stripos($mime_type[0], "/");
            $format = ltrim(substr($mime_type[0], $start), "/");
            $stream_data["format"] = $format;
            $this->video_format = $stream_data["format"];
            unset($stream_data["type"]);

            $streaming_map[] = $stream_data;
        }
        return $streaming_map;
    }

    private function getVideoInfo()
    {
        return file_get_contents("https://www.youtube.com/get_video_info?video_id=".$this->video_id."&cpn=CouQulsSRICzWn5E&eurl&el=adunit");
    }

    private function getStreamData()
    {
        parse_str($this->getVideoInfo(), $data);
        $streaming_data = json_decode($data["player_response"])->streamingData;
        return $streaming_data;
    }

    public function isVideoExisted(){
        $valid = true;
        parse_str($this->getVideoInfo(), $data);
        if($data["status"] == "fail"){
            $valid = false;
        }
        return $valid;
    }

    public function getVideoId()
    {
        return $this->video_id;
    }

    public function download(array $prepared_links)
    {
        $video_format = $prepared_links[0]['format'];
        $video_file_name = strtolower(str_replace(' ', '_', $this->video_id)).'.'.$video_format;
        $source = $prepared_links[0]['url'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $source);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
//        curl_setopt($ch, CURLOPT_SSLVERSION,3);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        $data = curl_exec ($ch);
        $error = curl_error($ch);
//        var_dump(curl_getinfo($ch));
//        var_dump($error);
        curl_close ($ch);

        $destination = $this->videos_store."/".$this->video_id.".".$video_format;
        $file = fopen($destination, "wb");
        fwrite($file, $data);
        fclose($file);

        return $this->video_id;
    }

    public function getVideoName()
    {
        return $this->video_title;
    }

    public function getFileName(){
        return $this->video_id.".".$this->video_format;
    }
}