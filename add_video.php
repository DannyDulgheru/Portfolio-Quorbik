<?php
include 'config.php';
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $youtube_url = $_POST['youtube_url'];

    // Validate and extract YouTube video ID
    if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $youtube_url, $matches)) {
        $video_id = $matches[1];
        
        // Attempt to get the max resolution thumbnail, fallback to hqdefault if unavailable
        $thumbnail_url = "https://img.youtube.com/vi/$video_id/maxresdefault.jpg";

        // Check if the maxresdefault thumbnail exists
        $headers = get_headers($thumbnail_url, 1);
        if ($headers[0] != 'HTTP/1.1 200 OK') {
            // Fallback to the HQ thumbnail if maxresdefault is not available
            $thumbnail_url = "https://img.youtube.com/vi/$video_id/hqdefault.jpg";
        }

        // Save the thumbnail to the local directory
        $thumbnail_path = "assets/thumbnails/$video_id.jpg";
        file_put_contents($thumbnail_path, file_get_contents($thumbnail_url));

        // Insert video data into the database
        $stmt = $db->prepare("INSERT INTO videos (title, youtube_url, thumbnail_path) VALUES (?, ?, ?)");
        $stmt->execute([$title, $youtube_url, $thumbnail_path]);

        // Redirect to dashboard after success
        header("Location: dashboard.php?success=1");
        exit;
    } else {
        $error = "Invalid YouTube URL!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Video</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-800 text-white p-6">
    <h1 class="text-center text-3xl mb-6">Add New Video</h1>
    <?php if (isset($error)): ?>
        <p class="text-red-500 text-center mb-4"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>
    <form method="POST" class="max-w-md mx-auto bg-gray-900 p-4 rounded">
        <label class="block mb-2">Video Title</label>
        <input type="text" name="title" required class="w-full mb-4 p-2 rounded bg-gray-700">
        <label class="block mb-2">YouTube URL</label>
        <input type="url" name="youtube_url" required class="w-full mb-4 p-2 rounded bg-gray-700">
        <button type="submit" class="w-full bg-blue-500 p-2 rounded text-white">Add Video</button>
    </form>
    <a href="dashboard.php" class="block text-center mt-4 text-blue-400">Back to Dashboard</a>
</body>
</html>
