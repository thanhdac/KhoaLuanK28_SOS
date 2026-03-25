#!/usr/bin/env php
<?php
/**
 * Quick Database Structure Verification
 *
 * Usage: php check_db_structure.php
 */

// Simple config - adjust if needed
$dbConfig = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'k28_be',
];

try {
    $conn = new mysqli(
        $dbConfig['host'],
        $dbConfig['user'],
        $dbConfig['password'],
        $dbConfig['database']
    );

    if ($conn->connect_error) {
        die("❌ Connection failed: " . $conn->connect_error);
    }

    echo "✅ Connected to database: {$dbConfig['database']}\n\n";

    // Check tables exist
    $tables = ['admin', 'nguoi_dung', 'thanh_vien_doi'];

    foreach ($tables as $table) {
        $result = $conn->query("SHOW TABLES LIKE '$table'");
        if ($result->num_rows > 0) {
            echo "✅ Table '{$table}' exists\n";

            // Show column count
            $col_result = $conn->query("SHOW COLUMNS FROM $table");
            echo "   Columns: " . $col_result->num_rows . "\n";

            // Show row count
            $row_result = $conn->query("SELECT COUNT(*) as cnt FROM $table");
            $row = $row_result->fetch_assoc();
            echo "   Records: " . $row['cnt'] . "\n\n";
        } else {
            echo "❌ Table '{$table}' NOT FOUND\n\n";
        }
    }

    // Check sample data
    echo "📋 Sample Admin Data:\n";
    $result = $conn->query("SELECT id_admin, ho_ten, email FROM admin LIMIT 3");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['ho_ten']} ({$row['email']})\n";
    }
    echo "\n";

    echo "📋 Sample NguoiDung Data:\n";
    $result = $conn->query("SELECT id_nguoi_dung, ho_ten, email FROM nguoi_dung LIMIT 3");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['ho_ten']} ({$row['email']})\n";
    }
    echo "\n";

    echo "📋 Sample ThanhVienDoi Data:\n";
    $result = $conn->query("SELECT id_thanh_vien_doi, ho_ten, email, vai_tro_trong_doi FROM thanh_vien_doi LIMIT 3");
    while ($row = $result->fetch_assoc()) {
        echo "   - {$row['ho_ten']} ({$row['vai_tro_trong_doi']}) - {$row['email']}\n";
    }

    $conn->close();
    echo "\n✅ Database structure verified successfully!\n";

} catch (Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    exit(1);
}
