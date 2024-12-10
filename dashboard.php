<?php
include 'config.php';
session_start();

// Check if the admin is logged in
if (!isset($_SESSION['admin'])) {
    header("Location: admin.php");
    exit;
}

// Fetch the current admin details
$stmt = $db->query("SELECT email, phone FROM admin_details WHERE id = 1");
$admin_details = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Update the contact information
    if (!empty($_POST['email']) && !empty($_POST['phone'])) {
        $new_email = $_POST['email'];
        $new_phone = $_POST['phone'];

        $update_stmt = $db->prepare("UPDATE admin_details SET email = :email, phone = :phone WHERE id = 1");
        $update_stmt->execute([':email' => $new_email, ':phone' => $new_phone]);

        header("Location: dashboard.php?updated=1");
        exit;
    }

    // Update video order if the form is submitted
    if (isset($_POST['update_order']) && isset($_POST['video_order'])) {
        $video_order = $_POST['video_order'];  // Array of ordered video IDs

        foreach ($video_order as $order => $video_id) {
            $update_order_stmt = $db->prepare("UPDATE videos SET `order` = :order WHERE id = :id");
            $update_order_stmt->execute([':order' => $order + 1, ':id' => $video_id]);
        }

        header("Location: dashboard.php?order_updated=1");
        exit;
    }
}

// Fetch all videos sorted by order
$stmt = $db->query("SELECT * FROM videos ORDER BY `order` ASC");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.14.0/Sortable.min.js"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var videoList = document.getElementById('video-list');
            
            // Initialize Sortable.js
            new Sortable(videoList, {
                handle: '.video-handle',
                onEnd: function(evt) {
                    var order = [];
                    var videoItems = document.querySelectorAll('.video-item');
                    videoItems.forEach(function(item, index) {
                        order.push(item.dataset.id);  // Store video IDs in order
                    });
                    updateVideoOrder(order);
                }
            });
        });

        // Function to send the updated order to the server
        function updateVideoOrder(order) {
            fetch('update_video_order.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order: order })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    console.log('Order updated successfully');
                } else {
                    console.error('Error updating order');
                }
            });
        }
    </script>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Fixed button at bottom right */
        .fixed-button {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #3b82f6;
            color: white;
            padding: 1rem 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            font-size: 1.1rem;
            font-weight: bold;
        }

        .video-item {
            height: auto; /* Reduce height of video items */
        }
        .video-item:hover {
            background: rgb(55 65 81 / var(--tw-bg-opacity, 1));
        }
    </style>
</head>
<body class="bg-gray-800 text-white p-6">

    <div class="max-w-7xl mx-auto p-6">
        <h1 class="text-center text-3xl mb-6 font-semibold">Admin Dashboard</h1>

        <!-- Display Success or Error Messages -->
        <?php if (isset($_GET['updated']) && $_GET['updated'] == 1): ?>
            <p class="text-green-500 text-center mb-4">Contact information updated successfully.</p>
        <?php endif; ?>

        <?php if (isset($_GET['order_updated']) && $_GET['order_updated'] == 1): ?>
            <p class="text-green-500 text-center mb-4">Video order updated successfully.</p>
        <?php endif; ?>

        <!-- Portfolio (Video List) -->
        <div class="bg-gray-800 p-4 rounded-lg">
            <h2 class="text-2xl mb-4">Portfolio (Video List)</h2>
            <form method="POST">
                <table class="min-w-full bg-gray-900 rounded-lg overflow-hidden shadow-md">
                    <thead class="bg-gray-700">
                        <tr>
                            <th class="p-4 text-left text-xl font-semibold">Order</th>
                            <th class="p-4 text-left text-xl font-semibold">Title</th>
                            <th class="p-4 text-left text-xl font-semibold">Thumbnail</th>
                            <th class="p-4 text-left text-xl font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="video-list">
                        <?php foreach ($videos as $index => $video): ?>
                            <tr class="video-item" data-id="<?= $video['id'] ?>" class="hover:bg-gray-700 transition-colors duration-200">
                                <td class="p-4">
                                    <div class="video-handle cursor-move bg-gray-600 p-2 rounded-full">
                                        <span class="text-white">☰</span>
                                    </div>
                                </td>
                                <td class="p-4"><?= htmlspecialchars($video['title']) ?></td>
                                <td class="p-4">
                                    <img src="<?= htmlspecialchars($video['thumbnail_path']) ?>" alt="Thumbnail" class="w-24 h-14 rounded-lg shadow-sm">
                                </td>
                                <td class="p-4">
                                    <a href="delete_video.php?id=<?= htmlspecialchars($video['id']) ?>" class="text-red-500 hover:text-red-700 font-semibold">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <button type="submit" name="update_order" class="w-full bg-blue-500 hover:bg-blue-600 p-2 rounded text-white mt-4">Update Order</button>
            </form>
        </div>

        <!-- Update Contact Information Block -->
        <div class="bg-gray-800 p-4 rounded-lg mt-8">
            <h2 class="text-2xl mb-4">Update Contact Information</h2>
            <form method="POST" class="space-y-4">
                <div>
                    <label for="email" class="block mb-2">Email</label>
                    <input type="email" name="email" id="email" class="w-full p-2 rounded bg-gray-700" value="<?= htmlspecialchars($admin_details['email']) ?>" required>
                </div>

                <div>
                    <label for="phone" class="block mb-2">Phone</label>
                    <input type="text" name="phone" id="phone" class="w-full p-2 rounded bg-gray-700" value="<?= htmlspecialchars($admin_details['phone']) ?>" required>
                </div>

                <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 p-2 rounded text-white">Update</button>
            </form>
        </div>

    </div>

    <!-- Fixed Add Video Button -->
    <a href="add_video.php" class="fixed-button">Add New Video</a>

    <!-- Add Logout Button -->
    <div class="mt-6 flex justify-center">
    <a href="logout.php" class="bg-blue-500 hover:bg-blue-600 text-white py-2 px-6 rounded-lg shadow-md text-lg font-semibold">
        Logout
    </a>
</div>


</body>
</html>
