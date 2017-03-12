<?php get_header(); ?>

<div id="wrapper">

	<main>

		<?php component('post')->render(); ?>

		<?php component('news')->render(); ?>

	</main>

</div>

<?php get_footer();
