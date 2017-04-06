<?php

/**=====================================================
 *  Config
 *=====================================================*/

// true にすると下記初期設定の内容に強制的に書き換えます。
// true のままだと管理画面内では該当箇所の設定を変更できません。
$enable_default_options = false;

//Editor CSS へのパス
add_editor_style( '/assets/css/editor-style.css' );

//投稿サムネイルの有効化
add_theme_support('post-thumbnails', array( 'post' ));
//画像のサイズ規格を追加
add_image_size('featured-image', 2000, 1500, true);



/**=====================================================
 *  初期設定
 *=====================================================*/
if( $enable_default_options ) {
  /**
   *  画像関連
   */
  //各画像サイズの設定
  set_option('large_size_w', 1280); // default: 1024
  set_option('large_size_h', 1280); // default: 1024
  set_option('medium_large_size_w', 960); // default: 768
  set_option('medium_large_size_h', 0); // default: 0
  set_option('medium_size_w', 640); // default: 300
  set_option('medium_size_h', 640); // default: 300
  set_option('thumbnail_size_w', 150); // default: 150
  set_option('thumbnail_size_h', 150); // default: 150
  //サムネイル画像を規定サイズに縮小した時、はみ出した分を切り取るか
  set_option('thumbnail_crop', 1); // default: 1
  //アップロードフォルダのパス default : wp-content/uploads
  set_option('upload_path', 'uploads');
  set_option('upload_url_path', get_option('home') . '/' . get_option('upload_path'));
  //年月日フォルダを作成して保存するか　true=1, false=0
  set_option('uploads_use_yearmonth_folders', 0);

  /**
   *  サイトの基本設定
   */
  //サイト名
  // set_option('blogname', 'Roud');
  //サイトの説明
  // set_option('blogdescription', '');
  //サイトを公開するか　true=1, false=0
  set_option('blog_public', 0);
  //サイトの文字セット
  set_option('blog_charset', 'UTF-8');

  //日付時間表示のフォーマット
  set_option('date_format', 'Y.m.d');
  set_option('time_format', 'H:i');

  //1ページに表示する最大投稿数 default: 10
  set_option('posts_per_page', 10);
  //RSSに表示する最大投稿数 default: 10
  set_option('posts_per_rss', 10);
  //コメントの返信の最大深度 default: 5
  set_option('thread_comments_depth', 3);

	//ピンバック・トラックバックを受け取るか
  set_option('default_ping_status', 0);

  /**
   *  その他
   */
  //顔文字を画像に変換するか　true=1, false=0
  set_option('use_smilies', 0);

}


/**=====================================================
 *  サポートの有効化
 *=====================================================*/

//編集ショートカットの有効化
add_theme_support('customize-selective-refresh-widgets');

//投稿・コメントのRSSフィードリンクの有効化
add_theme_support('automatic-feed-links');

//HTML5でのマークアップの許可
add_theme_support('html5', array(
    'search-form',
    'comment-form',
    'comment-list',
    'gallery',
    'caption'
));

//wp_head() でのタイトルタグ出力の有効化
add_theme_support('title-tag');
//タイトルからのキャッチフレーズの除去
add_filter( 'document_title_parts', function( $title ) {
  if( isset($title['tagline']) ) {
    unset( $title['tagline'] );
	}
	return $title;
});
//タイトルのセパレータ
add_filter( 'document_title_separator', function( $separator ) {
  return $separator = '|';
});
