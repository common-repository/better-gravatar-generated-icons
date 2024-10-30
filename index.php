<?php
/*
Plugin Name: Better Gravatar generated icons
Plugin URI: http://www.barattalo.it/
Description: Bored by Identicon and MonsterId? Here is a way to add new automatic generated avatars, such as Flathash or Unicorns
Version: 1
Author: Giulio Pons
Author URI: http://codecanyon.net/user/ginoplusio?ref=ginoplusio
*/

// Localization
add_action('init', 'betavat_plugin_init');
function betavat_plugin_init() {
	wp_register_style('betavat_css', plugins_url('style.css',__FILE__ ));
	wp_enqueue_style('betavat_css');
	wp_register_script( 'betavat', plugins_url('main.js',__FILE__ ),  array( 'jquery'), null, true );
	wp_enqueue_script('betavat');

} 




add_filter( 'get_avatar','betavat_get_avatar' , 10, 6 );
function betavat_get_avatar($avatar, $author, $size, $default, $alt, $args) {
  // -------------------------------------------
  // handle user passed by email or by id or obj
  if(isset($author->comment_ID)){
    $code = $author->comment_author_email;
  } else {
    if(stristr($author,"@")) $code = $author;
      else {
        $autore = get_user_by('ID', $author);
        $code =$autore->user_email;
      }
  }
  $code = md5( strtolower( trim($code)));
  // use flathash avatar instead of gravatar
  $tipo = get_option('BETAVAT_TYPE');
  if($tipo=="flathash") {
	  $newavatar = "//flathash.com/". $code."?s=".$size;
  }
  if($tipo=="unicorn") {
	  $newavatar = "//unicornify.appspot.com/avatar/".$code."?s=".$size;
  }
  if($tipo=="robots") {
	  $newavatar = "//robohash.org/".$code."?size=".$size."x".$size;
  }
  if($tipo=="robots2") {
	  $newavatar = "//robohash.org/".$code."?set=set2&size=".$size."x".$size;
  }
  if($tipo=="robots3") {
	  $newavatar = "//robohash.org/".$code."?set=set3&size=".$size."x".$size;
  }
 
  return "<img class='avatar' alt=\"".$alt."\" src='".$args['url']."' data-rel='".$newavatar."' width='".$size."' />";
}


add_action('admin_menu', 'betavat_settings');
function betavat_settings() {
	// create new top-level menu
	add_options_page( 'Better Avatars Settings', 'Better Avatar', 'manage_options', "betavat-menu-settings", 'betavat_settings_page');



	// call register settings function
	add_action( 'admin_init', 'register_betavat_settings' );
}

function register_betavat_settings() {
	register_setting( 'betavat-settings-group', 'BETAVAT_TYPE' );
}



function betavat_settings_page() { ?>
	<div class="wrap">
	<?php
	global $wpdb;

	

	?>

	<h2>Better Avatar Settings</h2>
	<form method="post" action="options.php">
		<?php settings_fields( 'betavat-settings-group' ); ?>
		<?php do_settings_sections( 'betavat-settings-group' );


		?>
		<p class="description">
			Choose your preferred avatar style from these services:
		</p>
		<table class="form-table">
			<tr valign="top">
			<th scope="row">Type</th>
			<td><?php $tipo = get_option('BETAVAT_TYPE');?>
				<select name="BETAVAT_TYPE">
					<option <?php echo $tipo=="flathash" ? "selected='selected'" : "";?> value="flathash">Flat Hash - by flathash.com</option>
					<option <?php echo $tipo=="unicorn" ? "selected='selected'" : "";?> value="unicorn">Unicorns - by unicornify.appspot.com</option>
					<option <?php echo $tipo=="robots" ? "selected='selected'" : "";?> value="robots">Robots - by robohash.org</option>
					<option <?php echo $tipo=="robots2" ? "selected='selected'" : "";?> value="robots2">Monster - by robohash.org</option>
					<option <?php echo $tipo=="robots3" ? "selected='selected'" : "";?> value="robots3">Robots head - by robohash.org</option>
				</select> 
			</tr>
		</table>
		<?php submit_button(); ?>
	</form>


	</div>
	<?php
}

?>