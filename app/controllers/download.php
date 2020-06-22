<?php
if (isset($filters['id'])){
	$upload=Upload::find($filters['id']);

	if (!isset($upload->id)){
		die(header ("Location: /"));
	}

	header('Content-type: application/octet-stream');
	header("Content-Disposition: attachment; filename=".$upload->filename);


	echo fread(fopen($upload->full_path, "rb"), filesize($upload->full_path));

}else{
	die(header ("Location: /"));
}

die();
