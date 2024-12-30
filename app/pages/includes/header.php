<!DOCTYPE html>
<html lang="en">
<head>
    
    <title><?=ucfirst($URL[0])?> - Music Website</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" href="<?=ROOT?>/assets/css/style.css?v=8">
</head>
<body>
<header>
    <div class="logo-holder">
        <a href="<?=ROOT?>"><img class="logo" src="<?=ROOT?>/assets/images/logo.png"></a>
    </div>

    <div class="header-div">
        <div class="main-title">ONLINE MUSIC STREAMING</div>
        <div class="main-nav">
            <div class="nav-item"><a href="<?=ROOT?>">Home</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/music">Top View Songs</a></div>
            <div class="nav-item dropdown">
            	<a href="#">Category</a>
            	<?php 
                        $query = "select * from categories order by category asc";
                        $categories = db_query($query);
                    ?>

                    <div class="dropdown-list">
                                         
                        <?php if(!empty($categories)):?>
                            <?php foreach($categories as $cat):?>
                                <div class="nav-item2"><a href="<?=ROOT?>/category/<?=$cat['category']?>"><?=$cat['category']?></a></div>
                            <?php endforeach;?>
                        <?php endif;?>
 
                    </div>
                </div>
            <div class="nav-item"><a href="<?=ROOT?>/artists">Artists</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/about">About Us</a></div>
            <div class="nav-item"><a href="<?=ROOT?>/contact">Contact Us</a></div>

            <?php if(logged_in()): ?>
            <div class="nav-item dropdown">
            	<a href="#">Hi, <?=user('username')?></a>
            	<div class="dropdown-list">
            		<div class="nav-item"> <a href="<?=ROOT?>/profile">Profile</div>
            		<div class="nav-item"> <a href="<?=ROOT?>/admin">Admin</div>
            		<div class="nav-item"> <a href="<?=ROOT?>/logout">Logout</div>	
            	</div>
            </div>
            <?php endif;?>
        </div>
    </div>
</header>
