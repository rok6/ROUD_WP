<?php
if( is_front_page() ) {
	get_template_part('modules/header/header', 'front');
}
else {
	get_template_part('modules/header/header', 'common');
}
