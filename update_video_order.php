<?php
include 'config.php';

// Check if the request is a POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);

    // Check if order data is present
    if (isset($data['order']) && is_array($data['order'])) {
        $order = $data['order'];
        
        // Update the video order in the database
        try {
            $db->beginTransaction();
            
            foreach ($order as $index => $video_id) {
                // Prepare and execute the update statement
                $stmt = $db->prepare("UPDATE videos SET `order` = :order WHERE id = :id");
                $stmt->execute([':order' => $index + 1, ':id' => $video_id]);
            }

            // Commit the transaction
            $db->commit();

            // Return success response
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            // Rollback in case of error
            $db->rollBack();
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid order data']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
