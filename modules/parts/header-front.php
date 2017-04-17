<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js no-svg">
<head>
<meta charset="UTF-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<?=Helper::description()?>
<?php wp_head(); ?>
<?=Helper::robots()?>
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel='stylesheet' media='all' href='<?=get_template_directory_uri()?>/assets/css/style.css' />
</head>
<body>

<div id="wrapper">

<header id="header" class="h">
	<div class="title h-item">
		<?=Helper::logo(true)?>
	</div>
	<nav class="nav-container h-item">
		<ul>
			<li><a href="<?=home_url()?>/weblog/">WEBLOG</a></li>
			<li><a href="<?=home_url()?>/news/">NEWS</a></li>
			<li><a href="<?=home_url()?>/wordpress/">WordPress</a></li>
		</ul>
	</nav>
	<div class="search h-item">
		<?php get_search_form(); ?>
		
	</div>
</header>
