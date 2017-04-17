
		<form role="search" method="get" class="search-form" action="<?=esc_url( home_url('/') )?>">
			<label class="search-label">
				<input class="search-field" type="search" name="s" placeholder="<?=esc_attr_x('キーワードを入力', 'placeholder')?>" value="<?=get_search_query()?>" />
				<button type="submit" class="button search-submit"><span><?=_x('Search', 'submit button')?></span></button>
			</label>
		</form>
