
<div id="news-area">
    <h2>What's New</h2>
    <p class="date"><?php echo date_i18n( get_option('date_format') ); ?></p>
    <ul>
        <?php foreach( $posts as $key => $val ) { ?>
        <li class="clearfix">
            <p class="image"><?php echo Helper::thumbnail($val->ID, array('url' => get_permalink($val->ID), 'alt' => $val->post_title)); ?></p>
        </li>
        <?php } ?>
    </ul>
</div>
