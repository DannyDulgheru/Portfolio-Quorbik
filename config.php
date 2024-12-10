<?php
// Database configuration
try {
    // Use absolute path to avoid relative path issues
    $dbPath = __DIR__ . '/db/database.sqlite';
    
    // Check if database file exists
    if (!file_exists($dbPath)) {
        throw new Exception("Database file not found at: $dbPath");
    }
    
    // Create PDO instance
    $db = new PDO("sqlite:$dbPath");
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // Output error and exit script
    die("Database connection failed: " . $e->getMessage());
}

// Admin credentials
define('ADMIN_USERNAME', 'admin');
define('ADMIN_PASSWORD', 'Test12345');
?>
