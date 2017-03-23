<?php get_header(); ?>

  <div id="primary">

    <main id="main" role="main">

        <article class="post">
          <div class="hentry">
            <header class="entry-header">
              <div class="entry-title"><?=Helper::title($post->ID, 3)?></div>
            </header>
            <footer class="entry-footer">
              <div class="entry-date">
                <?=Helper::datetime($post->ID)?>
              </div>
              <div class="post-author"><?=Helper::author($post->post_author)?></div>
              <div class="post-tags">
                <?=Helper::tags($post->ID)?>
              </div>
            </footer>
						<div class="entry-content">
							<?=Helper::content()?>
						</div>
          </div>
        </article>

    </main>

  </div><!--#primary-->

<?php get_footer();
