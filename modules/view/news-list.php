

<main id="main" role="main">

  <div class="news">
      <h2 class="headding">What's New</h2>
      <ul class="container"><?php foreach( $posts as $key => $data ) : ?>

          <li class="row post-list"><!--<?=esc_html($data->ID)?>-->
            <div class="hentry">
              <header class="entry-header">
                <div class="entry-title"><?=Helper::title($data->ID, 3)?></div>
                <div class="post-thumbnail"><?=Helper::thumbnail($data->ID, array(
                  'url' => get_permalink($data->ID),
                  'alt' => $data->post_title,
                ))?></div>
              </header>
              <footer class="entry-footer">
                <div class="entry-date">
                  <?=Helper::datetime($data->ID)?>
                </div>
                <div class="post-author"><?=Helper::author($data->post_author)?></div>
                <div class="post-tags">
                  <?=Helper::tags($data->ID)?>
                </div>
              </footer>
            </div>
          </li><?php endforeach; ?>

      </ul>
  </div>

</main>
