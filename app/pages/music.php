<?php require page('includes/header'); ?>

<div class="section-title"><center>Recommended Songs</center></div>

<section class="content">
    <?php
        // Define the number of songs per page
        $limit = 4;
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Get the current page from the URL (default to 1)
        $offset = ($page - 1) * $limit; // Calculate the offset for pagination

        // Get the total number of songs for pagination purposes
        $totalSongs = db_query_one("SELECT COUNT(*) as total FROM songs");
        $totalSongsCount = $totalSongs['total'];
        
        // Calculate the total number of pages
        $totalPages = ceil($totalSongsCount / $limit);
        
        // Calculate the previous and next page numbers
        $prev_page = $page > 1 ? $page - 1 : 1;
        $next_page = $page < $totalPages ? $page + 1 : $totalPages;

        // Query to fetch songs, calculating the popularity score based on views
        $rows = db_query("
            SELECT *, 
                   views AS popularity_score
            FROM songs
            ORDER BY popularity_score DESC
            LIMIT $limit OFFSET $offset");
    ?>

    <!-- Loop through the rows of songs and display them -->
    <?php if (!empty($rows)): ?>
        <?php foreach ($rows as $row): ?>
            <?php include page('includes/song'); ?>
        <?php endforeach; ?>
    <?php else: ?>
        <div class="m-2">No songs found to display.</div>
    <?php endif; ?>

</section>

<!-- Pagination Controls -->
<div class="mx-2">
    <a href="<?= ROOT ?>/music?page=<?= $prev_page ?>">
        <button class="btn bg-orange">Prev</button>
    </a>
    <a href="<?= ROOT ?>/music?page=<?= $next_page ?>">
        <button class="float-end btn bg-orange">Next</button>
    </a>
</div>

<?php require page('includes/footer'); ?>
