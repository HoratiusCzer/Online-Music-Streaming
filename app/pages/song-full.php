<?php 

// Increment the song's view count
db_query("update songs set views = views + 1 where id = :id limit 1", ['id' => $row['id']]);

// Fetch the current song details
$current_song = $row;

// Fetch all other songs from the database
$all_songs_query = "SELECT * FROM songs WHERE id != :id";
$all_songs = db_query($all_songs_query, ['id' => $row['id']]);

// Define the similarity function
function calculateSimilarity($current_song, $song) {
    $similarity_details = [
        'artist' => 0,
        'category' => 0,
        'title' => 0,
        'lyrics' => 0
    ];

    // 1. Artist similarity (weight: 0.25)
    if ($current_song['artist_id'] == $song['artist_id']) {
        $similarity_details['artist'] = 0.25;
    }

    // 2. Category similarity (weight: 0.25)
    if ($current_song['category_id'] == $song['category_id']) {
        $similarity_details['category'] = 0.25;
    }

    // 3. Title similarity using common keywords (weight: 0.25)
    $current_title_keywords = explode(" ", strtolower($current_song['title']));
    $song_title_keywords = explode(" ", strtolower($song['title']));
    $common_title_keywords = array_intersect($current_title_keywords, $song_title_keywords);
    $similarity_details['title'] = (count($common_title_keywords) / max(count($current_title_keywords), 1)) * 0.25;

    // 4. Lyrics similarity using common keywords (weight: 0.25)
    $current_lyrics_keywords = explode(" ", strtolower($current_song['lyrics']));
    $song_lyrics_keywords = explode(" ", strtolower($song['lyrics']));
    $common_lyrics_keywords = array_intersect($current_lyrics_keywords, $song_lyrics_keywords);
    $similarity_details['lyrics'] = (count($common_lyrics_keywords) / max(count($current_lyrics_keywords), 1)) * 0.25;

    $total_similarity = array_sum($similarity_details);

    return [
        'total_similarity' => $total_similarity,
        'details' => $similarity_details
    ];
}

// Calculate similarity for each song and store recommendations
$recommendations = [];

foreach ($all_songs as $song) {
    $similarity_data = calculateSimilarity($current_song, $song);

    // Only include songs with non-zero similarity
    if ($similarity_data['total_similarity'] > 0) {
        $recommendations[] = [
            'song' => $song,
            'similarity' => $similarity_data['total_similarity'],
            'details' => $similarity_data['details']
        ];
    }
}

// Sort recommendations by similarity in descending order
usort($recommendations, function($a, $b) {
    return $b['similarity'] <=> $a['similarity'];
});

// Get the top 5 recommendations
$top_recommendations = array_slice($recommendations, 0, 5);

?>

<!--start music card-->
<div class="music-card-full" style="max-width: 800px;">
    
    <h2 class="card-title"><?=esc($row['title'])?></h2>
    <div class="card-subtitle">by: <?=esc(get_artist($row['artist_id']))?></div>

    <div style="overflow: hidden;">
        <a href="<?=ROOT?>/song/<?=$row['slug']?>"><img src="<?=ROOT?>/<?=$row['image']?>"></a>
    </div>
    <div class="card-content">
        <audio controls style="width: 100%">
            <source src="<?=ROOT?>/<?=$row['file']?>" type="audio/mpeg">
        </audio>

        <div>Views: <?=$row['views']?></div>
        <div>Date added: <?=get_date($row['date'])?></div>

        <a href="<?=ROOT?>/download/<?=$row['slug']?>">
            <button class="btn bg-orange">Download</button>
        </a>
    </div>
</div>
<!--end music card-->

<!--start recommendations-->
<div class="recommendations" style="margin-top: 30px;">
    <h3>You May Also Like This</h3>
    <?php if (!empty($top_recommendations)): ?>
        <div class="recommendations-list">
            <?php foreach ($top_recommendations as $recommendation): 
                $recommended_song = $recommendation['song'];
                $similarity_percentage = round($recommendation['similarity'] * 100, 2);
                $details = $recommendation['details'];
            ?>
                <div class="recommendation-item" style="margin-bottom: 20px;">
                    <a href="<?=ROOT?>/song/<?=$recommended_song['slug']?>" style="text-decoration: none; color: inherit;">
                        <div style="display: flex; align-items: center;">
                            <img src="<?=ROOT?>/<?=$recommended_song['image']?>" alt="<?=esc($recommended_song['title'])?>" style="width: 80px; height: 80px; object-fit: cover; margin-right: 15px;">
                            <div>
                                <div style="font-weight: bold;"><?=esc($recommended_song['title'])?></div>
                                <div>by: <?=esc(get_artist($recommended_song['artist_id']))?></div>
                            </div>
                        </div>
                    </a>
                    <!-- View Button -->
                    <button class="btn bg-blue view-btn" onclick="showSimilarity(this)" 
                            data-similarity="Total: <?=$similarity_percentage?>%, Artist: <?=round($details['artist'] * 100, 2)?>%, 
                                              Category: <?=round($details['category'] * 100, 2)?>%, 
                                              Title: <?=round($details['title'] * 100, 2)?>%, 
                                              Lyrics: <?=round($details['lyrics'] * 100, 2)?>%">
                        View
                    </button>
                    <!-- Hidden similarity info -->
                    <span class="similarity-info" style="display: none; font-size: 14px; color: gray; margin-left: 10px;">
                        Similarity: Total: <?=$similarity_percentage?>%, Artist: <?=round($details['artist'] * 100, 2)?>%, 
                                    Category: <?=round($details['category'] * 100, 2)?>%, 
                                    Title: <?=round($details['title'] * 100, 2)?>%, 
                                    Lyrics: <?=round($details['lyrics'] * 100, 2)?>%
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>No recommendations available.</p>
    <?php endif; ?>
</div>
<!--end recommendations-->

<script>
    // Function to toggle the similarity info
    function showSimilarity(button) {
        const similarityInfo = button.nextElementSibling; // Get the next sibling element (span)
        if (similarityInfo.style.display === "none") {
            similarityInfo.style.display = "inline"; // Show the similarity info
        } else {
            similarityInfo.style.display = "none"; // Hide the similarity info
        }
    }
</script>

<style>
/* Container for the entire music card and recommendations */
.music-container {
    display: flex;
    justify-content: center;
    gap: 20px;
}

/* Styles for the main song card */
.music-card-full {
    width: 40%;
    max-width: 100px;
    margin-right: 20px;
}

/* Container for the recommendations section */
.recommendations {
    width: 30%;
    max-width: 300px;
}

/* Styles for the individual recommended song items */
.recommendation-item {
    display: flex;
    flex-direction: column;
    margin-bottom: 20px;
    align-items: flex-start; /* Align content to the left */
}

/* Styles for recommendation images */
.recommendation-item img {
    width: 80px;
    height: 80px;
    object-fit: cover;
    margin-bottom: 5px;
}

/* Optional: Styling for the View button */
.btn.bg-blue {
    background-color:#715764;
    color: white;
    border: none;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    cursor: pointer;
}

.btn.bg-blue:hover {
    background-color: red;
}
</style>
