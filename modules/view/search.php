
	<div class="search-result post-list container">
			<div class="headding">
				<h2>"<?=get_search_query()?>" の検索結果<span class="result_count"><?=$found_posts?>件</span></h2>
			</div>
		<ul class="container"><?php foreach( $post as $key => $data ) : ?>

			<li class="row"><!--<?=esc_html($data->ID)?>-->
				<div class="hentry">
					<header class="entry-header">
						<div class="entry-title"><?=Helper::title($data->ID, 3, true)?></div>
						<div class="post-thumbnail"><?=Helper::thumbnail($data->ID, array(
							'url' => get_permalink($data->ID),
							'alt' => $data->post_title,
							))?></div>
					</header>
					<footer class="entry-footer">
						<div class="entry-date">
							<?=Helper::datetime($data->ID)?>
						</div>
						<div class="post-author">
							<?=Helper::author($data->post_author)?>
						</div>
						<div class="post-tags">
							<?=Helper::tags($data->ID)?>
						</div>
					</footer>
				</div>
			</li><?php endforeach; ?>

		</ul>
	</div>

	<?=Helper::paginations()?>
