
	<article class="single container">
		<div class="hentry">
			<header class="entry-header">
				<div class="entry-title">
					<?=Helper::title($post->ID, 1)?>
				</div>
			</header>
			<footer class="entry-footer">
				<div class="entry-date">
					<?=Helper::datetime($post->ID)?>
				</div>
				<div class="post-taxonomies">
					<?=Helper::taxonomies($post->ID)?>
				</div>
			</footer>
			<div class="entry-content">
				<?=Helper::content($post)?>
			</div>
		</div>
	</article>

	<?=Helper::page_links()?>
