<?php
require 'db.php';

$stmt = $pdo->query("SELECT country FROM speaking_map WHERE status = 'speaking'");
$current_speaker = $stmt->fetchColumn();

$country_coords = [
    'Albania' => [41.1533, 20.1683],
    'Algeria' => [28.0339, 1.6596],
    'Bosnia and Herzegovina' => [43.9159, 17.6791],
    'Croatia' => [45.1, 15.2],
    'Cyprus' => [35.1264, 33.4299],
    'Egypt' => [26.8206, 30.8025],
    'France' => [46.6034, 1.8883],
    'Greece' => [39.0742, 21.8243],
    'Israel' => [31.0461, 34.8516],
    'Italy' => [41.8719, 12.5674],
    'Lebanon' => [33.8547, 35.8623],
    'Libya' => [26.3351, 17.2283],
    'Malta' => [35.9375, 14.3754],
    'Monaco' => [43.7384, 7.4246],
    'Montenegro' => [42.7087, 19.3744],
    'Morocco' => [31.7917, -7.0926],
    'Slovenia' => [46.1512, 14.9955],
    'Spain' => [40.4637, -3.7492],
    'Syria' => [34.8021, 38.9968],
    'Tunisia' => [33.8869, 9.5375],
    'Turkey' => [38.9637, 35.2433],
];

$coords = $current_speaker && isset($country_coords[$current_speaker])
    ? $country_coords[$current_speaker]
    : [0, 0]; 
?>

<!DOCTYPE html>
<html>
<head>
    <title>Interactive Speaking Map</title>
    <style>
        #map { height: 500px; width: 100%; margin-top: 20px; }
        .info { text-align: center; margin-top: 20px; font-size: 20px; }
    </style>
    <!-- Include Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
</head>
<body>
    <h1>Speaking Map</h1>
    <div class="info">
        <?php if ($current_speaker): ?>
            The current speaker is: <strong><?php echo htmlspecialchars($current_speaker); ?></strong>
        <?php else: ?>
            No country is currently speaking.
        <?php endif; ?>
    </div>
    <div id="map"></div>

    <script>
        // Initialize the map
        var map = L.map('map').setView([<?php echo $coords[0]; ?>, <?php echo $coords[1]; ?>], 3);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Add marker for the current speaking country
        <?php if ($current_speaker): ?>
        var marker = L.marker([<?php echo $coords[0]; ?>, <?php echo $coords[1]; ?>]).addTo(map);
        marker.bindPopup("<strong><?php echo htmlspecialchars($current_speaker); ?></strong>").openPopup();
        <?php endif; ?>
    </script>
</body>
</html>
