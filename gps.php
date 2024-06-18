<?php

    // Function to convert GPS coordinates from EXIF data to float
    function gpsToFloat($gps, $hemisphere) {
        // Degrees, minutes, seconds
        $d = count($gps) > 0 ? gps2Num($gps[0]) : 0;
        $m = count($gps) > 1 ? gps2Num($gps[1]) : 0;
        $s = count($gps) > 2 ? gps2Num($gps[2]) : 0;

        $float = $d + ($m / 60) + ($s / 3600);

        // If the hemisphere is South or West, make the float negative
        if ($hemisphere == 'S' || $hemisphere == 'W') {
            $float = -$float;
        }

        return $float;
    }

    // Function to convert GPS component to float
    function gps2Num($coordPart) {
        $parts = explode('/', $coordPart);
        if (count($parts) <= 0)
            return 0;

        if (count($parts) == 1)
            return $parts[0];

        return floatval($parts[0]) / floatval($parts[1]);
    }

    // get the exif data from a image
    function get_exif_data($path) {
        // get all the exif data from the image
        $exif = exif_read_data($path, 0, true);
        return $exif;
    }
    
    // get url parameter
    $file = $_GET['file'];
    $data = get_exif_data($file);

    $longitude = gpsToFloat($data['GPS']['GPSLongitude'], $data['GPS']['GPSLongitudeRef']);
    $latitude = gpsToFloat($data['GPS']['GPSLatitude'], $data['GPS']['GPSLatitudeRef']);    

    echo "<html><head><title>GPS Data</title></head><body>";
    echo "<h1>GPS Data</h1>";
    echo "<p>Latitude: $latitude</p>";
    echo "<p>Longitude: $longitude</p>";
    echo "</body></html>";
?>
