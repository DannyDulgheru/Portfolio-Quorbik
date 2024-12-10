<?php
include 'config.php';

// Fetch all videos from the database ordered by the 'order' column
$stmt = $db->query("SELECT * FROM videos ORDER BY `order` ASC");
$videos = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Fetch the admin contact details (email and phone)
$stmt_contact = $db->query("SELECT * FROM admin_details LIMIT 1"); // assuming only one record
$contact = $stmt_contact->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quorbik Works</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background-color: #111827; color: #f9fafb; margin: 0; }

        /* Full width background header */
        .header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 60vh;
            background-color: #1f2937;
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: -1; /* Send to the background */
            padding-top: 4rem; /* Add padding on top */
        }
        
        /* Adjust to two columns on medium screens */
        @media (min-width: 768px) {
            .header {
                height: 50vh;
            }
        }

        /* Adjust to three columns on large screens */
        @media (min-width: 1024px) {
            .header {
                height: 30vh;
            }
        }

        .header h1 {
            font-size: 5rem;
            font-weight: bold;
            color: rgba(255, 255, 255, 0.1); /* Light text for background effect */
            text-align: center;
        }

        /* Main content */
        .main-content {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            padding-top: 31rem; /* Padding for the content */
        }

        /* Video grid layout with responsiveness */
        .video-grid {
            display: grid;
            grid-template-columns: repeat(1, 1fr); /* One column by default */
            gap: 1.5rem;
        }

        /* Adjust to two columns on medium screens */
        @media (min-width: 768px) {
            .video-grid {
                grid-template-columns: repeat(2, 1fr); /* Two columns */
            }
        }

        /* Adjust to three columns on large screens */
        @media (min-width: 1024px) {
            .video-grid {
                grid-template-columns: repeat(2, 1fr); /* Two columns */
            }
        }

        /* Video item setup with smooth transition */
        .video-item {
            position: relative;
            overflow: hidden;
            border-radius: 8px;
            cursor: pointer;
            height: 20vh; /* 30% height of the viewport */
            transition: transform 0.5s ease, box-shadow 0.5s ease; /* Smooth transition for hover */
        }

        /* Grey effect on all video items by default */
        .video-item img {
            filter: grayscale(100%); /* Convert image to grayscale */
            transition: filter 0.5s ease; /* Smooth transition for grayscale effect */
            width: 100%; /* Ensure images fill the container */
            height: 100%; /* Ensure images fill the container */
            object-fit: cover; /* Ensure aspect ratio is maintained */
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        /* Color effect on hover (restore color) */
        .video-item:hover img {
            filter: grayscale(0%); /* Remove grayscale on hover */
            transform: scale(1.05); /* Slight scale up effect */
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2); /* Add shadow on hover */
            transition: transform 0.5s ease, box-shadow 0.5s ease;
        }

        /* Text overlay at the bottom of the image */
        .video-title {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: rgba(0, 0, 0, 0.5);
            color: white;
            padding: 0.5rem;
            font-size: 1.1rem;
            text-align: center;
            opacity: 0;
            transition: opacity 0.5s ease; /* Smooth transition for opacity */
        }

        /* Show the title on hover */
        .video-item:hover .video-title {
            opacity: 1;
        }

        /* Modal Styles */
        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 50;
        }

        .modal-dialog {
            width: 80%;  /* Increase width of modal */
            max-width: 100%;
            background: transparent;
            padding: 0;
            height: 80%;  /* Adjust height of modal */
        }

        .modal-content {
            position: relative;
            height: 100%;
            width: 100%;
        }

        .modal-body {
            width: 100%;
            height: 100%;
            padding: 0;
        }

        /* Full width iframe for the video */
        .modal-body iframe {
            width: 100%;
            height: 100%;
        }

        /* Close button outside of video frame */
        .close-btn {
            position: absolute;
            top: 10px;
            right: 10px;
            background-color: #333; /* Dark background for close button */
            color: white;
            border-radius: 10%;
            padding: 0.5rem;
            cursor: pointer;
            z-index: 100; /* Ensure it's above everything */
            font-size: 1rem;
            font-weight: bold;
        }

        /* Close button hover effect */
        .close-btn:hover {
            background-color: #555;
        }

        /* Footer styling */
        footer {
            background-color: #1f2937;
            padding: 2rem 1rem;
            color: #f9fafb;
        }

        footer p {
            font-size: 1.2rem;
            margin: 0.5rem 0;
        }

        footer a {
            text-decoration: none;
            color: #60a5fa;
            font-weight: bold;
            margin: 0 0.5rem;
        }
    </style>
</head>
<body class="flex flex-col min-h-screen">

    <!-- Full-width background header -->
    <div class="header">
        <h1>Quorbik Works</h1>
    </div>

    <div class="main-content">
        <main class="flex-grow container mx-auto p-6">
            <div class="video-grid">
                <?php
                foreach ($videos as $video) {
                    echo "<div class='video-item'>
                            <img src='{$video['thumbnail_path']}' alt='{$video['title']}' onclick='openVideo(\"{$video['youtube_url']}\")'>
                            <div class='video-title'>{$video['title']}</div>
                          </div>";
                }
                ?>
            </div>
        </main>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-900 p-6 text-center">
        <p class="text-lg">
            <a href="mailto:<?= htmlspecialchars($contact['email']) ?>" class="text-blue-500"><?= htmlspecialchars($contact['email']) ?></a> | 
            <a href="tel:<?= htmlspecialchars($contact['phone']) ?>" class="text-blue-500"><?= htmlspecialchars($contact['phone']) ?></a>
        </p>
    </footer>

    <!-- Video Modal -->
    <div id="video-modal" class="overlay">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <iframe id="video-frame" src="" frameborder="0" allowfullscreen></iframe>
                    <button onclick="closeVideo()" class="close-btn">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openVideo(url) {
            document.getElementById('video-frame').src = url.replace('watch?v=', 'embed/');
            document.getElementById('video-modal').style.display = 'flex';
        }

        function closeVideo() {
            document.getElementById('video-modal').style.display = 'none';
            document.getElementById('video-frame').src = '';
        }
    </script>

</body>
</html>
