<?php

class youtubeDownloader {

    // https://www.youtube.com/get_video_info?video_id=".$this->extractVideoId($this->video_url)."&cpn=CouQulsSRICzWn5E&eurl&el=adunit

    /**
     * youtube video ID
     *
     * @var string $id
     */
    private $id;

    /**
     * youtube video url
     *
     * @var string $url
     */
    private $url;

    /**
     * Constructor require a param {$video_url}
     * analyiss video url to get video ID
     *
     * @param string $url
     * @return void
     */
    public function __construct(string $url)
    {
        $this->url  = $url;
        $this->id   = $this->videoID( $this->url );
    }

    /**
     * Getting video ID from video ur;
     *
     * @param string $url
     * @return string
     */
    private function videoID(string $url):string
    {
        $url_query = explode('&', parse_url($url, PHP_URL_QUERY));
        return str_ireplace('v=', '', $url_query[0]);
    }

    /**
     * Getting video info
     *
     * @param string $id
     * @return object
     */
    public function videoInfo()
    {
        parse_str(file_get_contents("https://www.youtube.com/get_video_info?video_id={$this->id}"), $information);
        return (object)$information;
    }

    /**
     * Getting player response
     *
     * @param mixed $player_response
     * @return object
     */
    private function playerResponse():object
    {
        return json_decode($this->videoInfo()->player_response);
    }

    /**
     * Getting link ststus
     *
     * @return bool
     */
    public function brokenLink():bool
    {
        return $this->videoInfo()->status === 'ok' || !filter_var($this->url, FILTER_VALIDATE_URL) ? false : true;
    }

    /**
     * Create Download link
     *
     * @return object
     */
    public function videoDownloadLink():object
    {
        $video_details      = (object)$this->playerResponse()->videoDetails;
        $streaming_data     = (object)$this->playerResponse()->streamingData;
        // Just two video qualities { meduim && hd720 } speed && non-broken videos
        $streaming_formats  = $streaming_data->formats;
        // More download options slow && broken videos
        $streaming_formats  = $streaming_data->adaptiveFormats;

        $data_array = array();

        foreach ( $streaming_formats as $format ) {
            $data                           = $format;
            $data->title                    = $video_details->title;
            $data->mimeType                 = explode(';', $data->mimeType)[0];
            $data->extension                = explode('/', $data->mimeType)[1];
            $data_array[$data->quality]     = $data;
        }
        return (object)$data_array;
    }

}
