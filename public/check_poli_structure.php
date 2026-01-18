<?php

// Check poli table structure
try {
    $dbPath = __DIR__ . '/../writable/data/puskesmas.db';
    if (!file_exists($dbPath)) {
        die("Database file not found at: $dbPath");
    }

    $db = new SQLite3($dbPath);

    // Get table info
    $results = $db->query("PRAGMA table_info(poli)");
    $columns = [];

    while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
        $columns[] = $row;
    }

    echo "<h3>Table Structure 'poli':</h3>";
    echo "<table border='1' cellspacing='0' cellpadding='5'>";
    echo "<tr><th>cid</th><th>name</th><th>type</th><th>notnull</th><th>dflt_value</th><th>pk</th></tr>";
    
    foreach ($columns as $col) {
        echo "<tr>";
        foreach ($col as $val) {
            echo "<td>" . htmlspecialchars($val ?? 'NULL') . "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";

    $db->close();

} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
