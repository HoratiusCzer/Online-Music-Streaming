<!--start music card-->
<div class="music-card-full" style="max-width: 500px;">
	
	<h2 class="card-title"><?=esc($row['name'])?></h2>

	<div style="overflow: hidden;">
		<img src="<?=ROOT?>/<?=$row['image']?>">
	</div>
	<div class="card-content">
		<div><?=esc($row['bio'])?></div>

		<div><center><b>Artist's Songs:</b></center></div>
		<div style="display: flex;flex-wrap: wrap;justify-content: center;">
			<?php 

				$query ="select * from songs where artist_id = :artist_id order by views desc limit 20";
				$songs = db_query($query,['artist_id'=>$row['id']]);

			?>

			<?php if(!empty($songs)):?>
				<?php foreach($songs as $row):?>
					<?php include page('includes/song')?>
				<?php endforeach;?>
			<?php endif;?>
		</div>
	</div>
</div>
<!--end music card-->

<style>/* General reset */
body, h2, h3, p, div {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: Arial, sans-serif;
}

/* Music card container */
.music-card-full {
    max-width: 500px;
    background-color: #fff;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    margin: 20px auto;
    padding: 20px;
    border: 1px solid #ddd;
}

/* Card title */
.card-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 15px;
    color: #333;
    text-align: center;
}

/* Artist image */
.artist-image {
    overflow: hidden;
    margin-bottom: 15px;
}

.artist-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

/* Card content section */
.card-content {
    padding: 10px;
    line-height: 1.5;
    color: #555;
}

/* Artist bio */
.artist-bio {
    margin-bottom: 20px;
    font-size: 14px;
    color: #666;
    line-height: 1.6;
    text-align: justify;
}

/* Songs section */
.songs-section h3 {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 10px;
    color: #444;
    text-align: center;
}

/* Songs list */
.songs-list {
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 10px;
}

.songs-list img {
    max-width: 100px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
}

/* Individual song item */
.song-card {
    text-align: center;
    margin-bottom: 10px;
    font-size: 12px;
    color: #333;
    background-color: #f8f8f8;
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 8px;
    width: 100px;
}

.song-card img {
    width: 100%;
    height: auto;
    border-radius: 5px;
    margin-bottom: 8px;
}

/* Adjust text in song card */
.song-title {
    font-size: 14px;
    font-weight: bold;
    color: #555;
    margin-bottom: 5px;
}

.song-artist {
    font-size: 12px;
    color: #777;
}
</style>