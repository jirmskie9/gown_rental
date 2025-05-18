<?php
// Prevent any output before JSON response
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Start output buffering
ob_start();

try {
    require_once 'config.php';
    
    // Test database connection
    if (!$conn) {
        throw new Exception("Database connection failed");
    }

    // Set proper JSON header
    header('Content-Type: application/json');

    // Simple test query first
    $sql = "SELECT COUNT(*) as count FROM payments";
    $result = $conn->query($sql);
    
    if (!$result) {
        throw new Exception("Database query failed: " . $conn->error);
    }

    $row = $result->fetch_assoc();
    $data = [
        'labels' => ['Test'],
        'sales' => [$row['count']]
    ];

    // Clear any output buffer
    ob_end_clean();

    // Return JSON response
    echo json_encode($data);
    exit;

} catch (Exception $e) {
    // Clear any output buffer
    ob_end_clean();
    
    // Log the error
    error_log("Sales data error: " . $e->getMessage());
    
    // Return error response
    http_response_code(500);
    echo json_encode([
        'error' => true,
        'message' => $e->getMessage()
    ]);
    exit;
}
?> 