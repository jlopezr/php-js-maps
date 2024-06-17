<?php

    // download a image from a url
    function download_image($url, $path) {
        $ch = curl_init($url);
        $fp = fopen($path, 'wb');
        curl_setopt($ch, CURLOPT_FILE, $fp);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_exec($ch);
        curl_close($ch);
        fclose($fp);
    }

    //download_image('https://cdn.ttc.io/i/fit/1035/0/sm/0/plain/exposingtheinvisible.org/ckeditor_assets/pictures/32/content_example_ibiza.jpg', 'ibiza.jpg');
    
    // get the exif data from a image
    function get_exif_data($path) {
        // get all the exif data from the image
        $exif = exif_read_data($path, 0, true);
        return $exif;
    }
    
    // get url parameter
    $file = $_GET['file'];
    $data = get_exif_data($file);
    
    header('Content-Type: application/json');
    echo json_encode($data);
?>