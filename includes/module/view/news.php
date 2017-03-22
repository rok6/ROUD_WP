
<section class="news">
  <h2>NEWS</h2>
  <p class="date"><?php echo date_i18n( get_option('date_format') ); ?></p>
  <ul itemscope itemtype="http://schema.org/News">
    <?php foreach( $posts as $key => $val ) { ?>
    <li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">
			<header class="h">
				<div class="title"><?php echo Helper::title( $val->ID, array('url' => get_permalink($val->ID)) ); ?></div>
			</header>
			<div class="body">
				<div class="content"><?php echo Helper::content( $val->post_content ); ?></div>
			</div>
			<footer class="f">
				<p class="date"><?php echo Helper::publish_date( $val->ID ); ?></p>
			</footer>
    </li>
    <?php } ?>
  </ul>
</section>
