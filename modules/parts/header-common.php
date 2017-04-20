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

<header id="header">

	<div class="container">
		<div class="title h-items">
			<?=Helper::logo(true)?>
		</div>
		<nav class="nav-container h-items">
			<?=Helper::navgation_menu(['location' => 'primary'])?>
		</nav>
		<div class="search h-items">
			<?php get_search_form(); ?>
		</div>
	</div>
	<div class="breadcrumb" itemscope itemtype="http://schema.org/BreadcrumbList">
		<?=Helper::breadcrumb()?>
	</div>
</header>
