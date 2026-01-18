<?php

// Check poli data directly from SQLite
try {
    $dbPath = __DIR__ . '/../writable/data/puskesmas.db';
    if (!file_exists($dbPath)) {
        die("Database file not found at: $dbPath");
    }

    $db = new SQLite3($dbPath);

    $results = $db->query("SELECT * FROM poli");
    $data = [];

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $data[] = $row;
    }

    echo "<h3>Raw Data (Count: " . count($data) . "):</h3>";
    echo "<pre>";
    print_r($data);
    echo "</pre>";

    echo "<h3>JSON Encode Test:</h3>";
    $json = json_encode($data);
    if ($json === false) {
        echo "JSON Encode Error: " . json_last_error_msg();
    } else {
        echo "JSON Encode Success: " . htmlspecialchars($json);
    }

    $db->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
