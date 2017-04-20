
	<div class="search-result post-list container">
		<div class="headding">
			<h2>"<?=Helper::headline()?>" の検索結果<span class="result_count"><?=$found_posts?>件</span></h2>
		</div>
		<ul><?php foreach( $post as $key => $data ) : ?>

			<li class="row"><!--<?=esc_html($data->ID)?>-->
				<div class="hentry entry-container">
					<header class="entry-header">
						<div class="entry-title">
							<?=Helper::title($data->ID, 3, true)?>
						</div>
					</header>
					<footer class="entry-footer">
						<div class="entry-date">
							<?=Helper::datetime($data->ID)?>
						</div>
					</footer>
				</div>
			</li><?php endforeach; ?>

		</ul>
	</div>

	<?=Helper::post_paginations()?>
