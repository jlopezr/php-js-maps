# Información geografica en MYSQL

## Crear una tabla 

```sql
CREATE DATABASE geolocation;
USE geolocation;

CREATE TABLE points_of_interest (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    location POINT NOT NULL,
    SPATIAL INDEX(location)
);
```

## Insertar datos

```sql
INSERT INTO points_of_interest (name, description, location)
VALUES ('Eiffel Tower', 'Famous landmark in Paris', ST_GeomFromText('POINT(2.2945 48.8584)'));

INSERT INTO points_of_interest (name, description, location)
VALUES ('Statue of Liberty', 'Iconic statue in New York City', ST_GeomFromText('POINT(-74.0445 40.6892)

INSERT INTO points_of_interest (name, description, location)
VALUES ('Vilanova i la Geltrú', 'A city in the province of Barcelona, Catalonia, Spain', ST_GeomFromText('POINT(1.7259 41.2230)'));
'));
```

## Consultar datos

```sql
SELECT id, name, description, ST_AsText(location) as location
FROM points_of_interest;
```

## Consultar datos II

| id  | name                  | description                                      | location                  |
| --- | --------------------- | ------------------------------------------------ | ------------------------- |
| 1   | Vilanova i la Geltrú  | A city in the province of Barcelona, Catalonia, Spain | POINT(1.7259 41.2230)    |


## Consultar datos cercanos

```sql
SET @lat = 48.8584;
SET @lon = 2.2945;
SET @distance = 10000; -- Distance in meters

SELECT id, name, description, ST_AsText(location) as location,
    ST_Distance_Sphere(location, ST_GeomFromText(CONCAT('POINT(', @lon, ' ', @lat, ')'))) as distance
FROM points_of_interest
WHERE ST_Distance_Sphere(location, ST_GeomFromText(CONCAT('POINT(', @lon, ' ', @lat, ')'))) <= @distance;
```

## Consultar datos dentro de una bounding box

```sql
SELECT id, name, description, ST_AsText(location) as location
FROM points_of_interest
WHERE MBRContains(ST_GeomFromText('POLYGON((2.0 48.0, 2.0 49.0, 3.0 49.0, 3.0 48.0, 2.0 48.0))'), location);
```

## Obtener longitud y latitud por separado

```sql
SELECT id, name, description, ST_X(location) as lon, ST_Y(location) as lat
FROM points_of_interest;
```

## Generar en php un json con los datos de puntos de interes

```php
<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "geolocation";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, name, description, ST_X(location) as lon, ST_Y(location) as lat
        FROM points_of_interest";

$result = $conn->query($sql);

$features = array();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $feature = array(
            'type' => 'Feature',
            'geometry' => array(
                'type' => 'Point',
                'coordinates' => array($row['lon'], $row['lat'])
            ),
            'properties' => array(
                'id' => $row['id'],
                'name' => $row['name'],
                'description' => $row['description']
            )
        );
        array_push($features, $feature);
    }
}

$geojson = array(
    'type' => 'FeatureCollection',
    'features' => $features
);

echo json_encode($geojson);

$conn->close();

?>
```

## Ejemplo de geojson

```json
{
    "type": "FeatureCollection",
    "features": [
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [2.2945, 48.8584]
            },
            "properties": {
                "id": 1,
                "name": "Eiffel Tower",
                "description": "Famous landmark in Paris"
            }
        },
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [-74.0445, 40.6892]
            },
            "properties": {
                "id": 2,
                "name": "Statue of Liberty",
                "description": "Iconic statue in New York City"
            }
        },
        {
            "type": "Feature",
            "geometry": {
                "type": "Point",
                "coordinates": [1.7259, 41.2230]
            },
            "properties": {
                "id": 3,
                "name": "Vilanova i la Geltrú",
                "description": "A city in the province of Barcelona, Catalonia, Spain"
            }
        }
    ]
}
```

## Mostrar en un leaflet los datos del geojson

```html
<!DOCTYPE html>
<html>
<head>
    <title>Leaflet Map</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        #map {
            height: 400px;
        }
    </style>
</head>

<body>
    <div id="map"></div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map').setView([48.8584, 2.2945], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
        }).addTo(map);

        fetch('geojson.php')
            .then(response => response.json())
            .then(data => {
                L.geoJSON(data, {
                    onEachFeature: function (feature, layer) {
                        layer.bindPopup('<h3>' + feature.properties.name + '</h3><p>' + feature.properties.description + '</p>');
                    }
                }).addTo(map);
            });
    </script>
</body>
</html>
```
