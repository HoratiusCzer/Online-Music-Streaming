<?php require page('includes/header'); ?>

<!-- Profile Hero Section -->
<section class="hero-section">
    <img class="hero" src="<?= ROOT ?>/assets/images/hero.jpg" alt="Profile Banner">
</section>

<!-- User Profile Section -->
<div class="section-title">User Profile</div>

<section class="content">
    <?php 
        // Assume the logged-in user's ID is stored in the session
        $userId = $_SESSION['USER']['id'] ?? null;

        if (!$userId) {
            echo "Error: User not logged in!";
            exit;
        }

        // Fetch user information from the `users` table
        $user = db_query_one("SELECT * FROM users WHERE id = :id", ['id' => $userId]);

        if (!$user) {
            echo "Error: User profile not found!";
            exit;
        }

        // User profile details
        $username = $user['username'] ?? "Guest User";
        $listeningTime = $user['listening_time'] ?? "0 hrs"; // Ensure this column exists in the `users` table

        // Top Artist Query
        $topArtistQuery = db_query("
            SELECT s.artist_id, COUNT(s.id) AS play_count 
            FROM songs AS s 
            WHERE s.user_id = :user_id 
            GROUP BY s.artist_id 
            ORDER BY play_count DESC 
            LIMIT 1", 
            ['user_id' => $userId]
        );

        $topArtistId = $topArtistQuery[0]['artist_id'] ?? null;
        $topArtistName = $topArtistId ? get_artist($topArtistId) : "No top artist data";

        // Top Category (Genre) Query
        $topCategoryQuery = db_query("
            SELECT c.category, COUNT(s.id) AS play_count 
            FROM songs AS s 
            JOIN categories AS c ON s.category_id = c.id 
            WHERE s.user_id = :user_id 
            GROUP BY c.id 
            ORDER BY play_count DESC 
            LIMIT 1", 
            ['user_id' => $userId]
        );

        $topCategoryName = $topCategoryQuery[0]['category'] ?? "No category data";
    ?>

    <!-- User Profile Details -->
    <div class="profile-container">
        <div class="profile-header">
            <div class="profile-info">
                <h1><?= htmlspecialchars($username) ?></h1>
            </div>
        </div>

        <!-- User Stats -->
        <div class="user-stats">
            <div class="stat-box">
                <strong>Total Listening Time:</strong> <br> <?= htmlspecialchars($listeningTime) ?>
            </div>
            <div class="stat-box">
                <strong>Top Artist:</strong> <br> <?= htmlspecialchars($topArtistName) ?>
            </div>
            <div class="stat-box">
                <strong>Top Genre:</strong> <br> <?= htmlspecialchars($topCategoryName) ?>
            </div>
        </div>
    </div>

    <!-- Recently Played Songs -->
    <div class="section-title">Recently Played Songs</div>

    <section class="recent-songs-content">
        <?php 
            // Fetch recently played songs
            $recentSongs = db_query("
                SELECT s.*, a.name AS artist_name, c.category AS category_name 
                FROM songs AS s
                JOIN artists AS a ON s.artist_id = a.id 
                JOIN categories AS c ON s.category_id = c.id 
                WHERE s.user_id = :user_id 
                ORDER BY s.date DESC 
                LIMIT 10", 
                ['user_id' => $userId]
            );
        ?>

        <?php if (!empty($recentSongs)): ?>
            <?php foreach ($recentSongs as $song): ?>
                <div class="song-item">
                    <div class="song-image">
                        <img src="<?= ROOT ?>/<?= htmlspecialchars($song['image']) ?>" alt="<?= htmlspecialchars($song['title']) ?>">
                    </div>
                    <div class="song-info">
                        <strong><?= htmlspecialchars($song['title']) ?></strong><br>
                        <small>by <?= htmlspecialchars($song['artist_name']) ?></small><br>
                        <small>Category: <?= htmlspecialchars($song['category_name']) ?></small>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="m-2">No recently played songs</div>
        <?php endif; ?>
    </section>
</section>

<?php require page('includes/footer'); ?>


<!-- Styling for Profile Page -->
<style>
    /* Profile Section */
    .hero-section {
        margin-bottom: 20px;
    }
    .hero {
        width: 100%;
        height: auto;
    }

    .profile-container {
        text-align: center;
        margin: 30px 0;
        background-color: #f7f7f7;
        padding: 20px;
        border-radius: 8px;
    }

    .profile-header {
        margin-bottom: 20px;
    }

    .profile-info h1 {
        font-size: 30px;
        color: #333;
    }

    /* User Stats */
    .user-stats {
        display: flex;
        justify-content: space-around;
        margin-top: 20px;
    }

    .stat-box {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        width: 25%;
        text-align: center;
    }

    .stat-box strong {
        display: block;
        font-size: 18px;
        color: #444;
    }

    /* Recently Played Songs Section */
    .recent-songs-content {
        display: flex;
        flex-direction: column;
        gap: 20px;
    }

    .song-item {
        display: flex;
        align-items: center;
        gap: 20px;
        padding: 10px;
        background-color: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .song-item .song-image img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    .song-item .song-info {
        font-size: 14px;
        color: #555;
    }

    .song-item .song-info strong {
        font-size: 16px;
        color: #333;
    }

    .section-title {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 20px;
        color: #444;
    }
</style>
