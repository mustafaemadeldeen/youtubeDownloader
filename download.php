<?php

	session_start();

	$data	= (object)array(
		'mimeType'	=> base64_decode( $_GET['mimetype'] ),
		'title'			=> base64_decode( $_GET['title'] ),
		'extension'	=> base64_decode( $_GET['extension'] ),
		'url'				=> base64_decode( $_GET['url'] ),
	);

	header('Content-Type: "' . $data->mimeType . '"');
	header('Content-Disposition: attachment; filename="' . $data->title .".". $data->extension . '"');
	header("Content-Transfer-Encoding: binary");
	header('Expires: 0');
	// header('Content-Length: '.$size);
	header('Pragma: no-cache');

	readfile( $data->url );
