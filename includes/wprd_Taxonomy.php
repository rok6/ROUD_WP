<?php
require_once(ABSPATH . 'wp-admin/includes/template.php');

class WPRD_Taxonomy
{
	static private $domain;

	public function __construct( $domain )
	{
		self::$domain = $domain;

		// デフォルトカテゴリーに「設定しない」を追加
		$this->add_non_categorize();
		// カテゴリーの選択項目をチェックボックスからラジオボタンにする
		$this->on_radio_type_category();

	}

	private function on_radio_type_category()
	{
		add_action( 'wp_terms_checklist_args', function( $args, $post_id = null ) {
			$args['checked_ontop'] = false;
			$args['walker'] = new Walker_Taxonomy_radio();
			return $args;
		});
	}

	private function add_non_categorize()
	{
		add_filter('wp_dropdown_cats', function( $output, $r ) {
			global $pagenow;

			if( $pagenow === 'edit.php' ) {
				return $output;
			}

			$regex = '#(<option(.)*</option>)#u';
			if ( preg_match( $regex, $output ) ) {
				$output = preg_split($regex, $output, 2);
				$output[0] .= '<option class="level-0" value="-1">'.__('設定しない').'</option>';
				$output = $output[0].$output[1];
			}

			return $output;
		}, 10, 2);
	}

}


class Walker_Taxonomy_radio extends Walker_Category_Checklist
{
	// 参考：http://chaika.hatenablog.com/entry/2015/06/08/210000

	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 )
	{
		extract($args);

		if( empty($taxonomy) ) {
			$taxonomy = 'category';
		}

		if( $taxonomy === 'category' ) {
			$name = 'post_category';
		} else {
			$name = 'tax_input['.$taxonomy.']';
		}

		$class = in_array( $category->term_id, $popular_cats ) ? ' class="popular-category"' : '';

		// 先頭に「設定しない」の項目を追加
		if( $output === '' ) {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit"><input value="-1" type="radio" name="'.$name.'[]" id="no-categorize"' .
				checked( !in_array( $category->term_id, $selected_cats ), true, false ) .
				disabled( empty( $args['disabled'] ), false, false ) . ' /> 設定しない</label>';
		}

		// 子カテゴリーがある場合は選択ボタンを表示しない
		$chaild_terms = get_term_children($category->term_id, $taxonomy);
		if( !empty( $chaild_terms ) ) {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit">' . esc_html( apply_filters('the_category', $category->name )) . '</label>';
		}
		else {
			$output .= PHP_EOL."<li id=\"{$taxonomy}-{$category->term_id}\"$class>" .
				'<label class="selectit"><input value="' . $category->term_id . '" type="radio" name="'.$name.'[]" id="in-'.$taxonomy.'-' . $category->term_id . '"' .
				checked( in_array( $category->term_id, $selected_cats ), true, false ) .
				disabled( empty( $args['disabled'] ), false, false ) . ' /> ' .
				esc_html( apply_filters('the_category', $category->name )) . '</label>';
		}
	}

}
