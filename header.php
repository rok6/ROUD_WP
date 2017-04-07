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
<link type='text/css' rel='stylesheet' media='all' href='<?=get_template_directory_uri()?>/assets/css/style.css' />
</head>
<body>

<div id="wrapper">

<header id="header" class="h">
	<div class="title">
		<?=Helper::logo(true)?>
	</div>
	<nav class="nav-container">
		<?php wp_nav_menu( array(
			'menu'					=> 'main_menu',
			'container'			=> '',
			'indent'				=> 1,
			'walker'				=> new Roud_Walker,
		)); ?>
	</nav>
</header>
