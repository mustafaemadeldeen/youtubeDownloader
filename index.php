<?php

require 'youtubeDownloader.php';

$url = 'https://www.youtube.com/watch?v=f7FR3eWucuI';

$downloader = new youtubeDownloader($url);

if ( !$downloader->brokenLink() ) {

    foreach ( $downloader->videoDownloadLink() as $linkLabel => $data ) {

        if ( !empty( $data->url ) ) {

            echo '<h1>'. $data->title .'</h1>';

            $mimetype   = base64_encode( $data->mimeType );
            $extension  = base64_encode( $data->extension );
            $title      = base64_encode( $data->title );
            $url        = base64_encode( $data->url );

            echo '<a href="download.php?mimetype='.$mimetype.'&extension='.$extension.'&title='.$title.'&url='.$url.'">Download Quailty '. $linkLabel .'</a><br/>';

        }

    }

} else {

    echo '<h1>The given url is broken or invalid please check it and retry</h1>';

}
