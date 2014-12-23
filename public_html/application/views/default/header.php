<?php
	if(!isset($title))
	{
		$title = "Untitled Page";
	}

	if(!isset($_GET['s'])){
		$search = "";
	}
	else{
		$search = $_GET['s'];
	}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		 <meta name="viewport" content="width=device-width, initial-scale=1">
		 <link href='//fonts.googleapis.com/css?family=Raleway:400,300,600' rel='stylesheet' type='text/css'>
		 <link rel="stylesheet" href="/assets/css/normalize.css">
 		 <link rel="stylesheet" href="/assets/css/skeleton.css">
		<link rel="stylesheet" href="/assets/css/app.css">
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
		<title><?=$title?> | Open Tor X</title>
	</head>
<body>
<nav>
  <ul>
    <li><a href="/">Home</a></li>
    <li><a href="/share">Share</a></li>
    <li><a href="/about">About</a></li>
    <li><form action="/search" class="menu-form"><input type="search" name="s" value="<?=$search?>" placeholder="Search"><input type="submit" class="button-primary" value="Search!"></form></li>
  </ul>
</nav>

<h1>Open Tor X</h1>
<p>Welcome to Open Tor X! An Open Source Torrent Website!</p>

<div class="row container">
<div class="one">
