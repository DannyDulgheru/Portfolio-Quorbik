<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Fetch the video thumbnail path from the database
    $stmt = $db->prepare("SELECT thumbnail_path FROM videos WHERE id = ?");
    $stmt->execute([$id]);
    $video = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($video) {
        // Delete the thumbnail file from the server
        if (file_exists($video['thumbnail_path'])) {
            unlink($video['thumbnail_path']);
        }

        // Delete the video record from the database
        $stmt = $db->prepare("DELETE FROM videos WHERE id = ?");
        $stmt->execute([$id]);

        // Redirect to the dashboard with a success message
        header("Location: dashboard.php?deleted=1");
        exit;
    }
}

// Redirect to the dashboard with an error message if deletion failed
header("Location: dashboard.php?error=1");
exit;
?>
