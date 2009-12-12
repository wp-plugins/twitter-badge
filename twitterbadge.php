<?php

/*
Plugin Name: Twitter Badge
Version: 0.1
Plugin URI: http://neo22s.com/wptwitterbadge
Description: Generate a Twitter Badge for your site based on http://lab.neo22s.com/twitterBadge/
Author: Chema Garrido
Author URI: http://garridodiaz.com
License   : http://creativecommons.org/licenses/GPL/2.0/

/*
    Copyright 2009  chema garrido

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

/*

TODO:
 - Allow to choose right or left
 - Allow to choose different images

*/


// defaults
define('key_badge_twitter_default'	, 'neo22s');
define('key_badge_text_default'		, 'Follow&nbsp;me');
define('key_badge_color_default'	, '#237ab2');
define('key_badge_text_color_default', '#FFF');


// add options
add_option(key_badge_twitter	, key_badge_twitter_default	, 'Twitter account');
add_option(key_badge_text	, key_badge_text_default	, 'Text to display in the badge');
add_option(key_badge_color	, key_badge_color_default	, 'Background color for the badge');
add_option(key_badge_text_color	, key_badge_text_color_default	, 'Color for the text');


// adding actions
add_action('wp_footer', 'twitter_badge_add');
add_action('admin_menu', 'add_twitter_badge_options_page');




if (get_option(key_badge_twitter)==key_badge_twitter_default && !isset($_POST['submit']) ) {
	function setup_warning() {
		echo "
		<div class='updated fade'><p><strong>".__('Twitter Badge is almost ready.')."</strong> ".sprintf(__('Please <a href="%1$s">review the configuration</a>.'), "options-general.php?page=twitterbadge.php")."</p></div>
		";
	}
	add_action('admin_notices', 'setup_warning');
	return;
}


function add_twitter_badge_options_page(){
	global $wpdb;
	add_options_page('Twitter Badge Options', 'Twitter Badge', 8, basename(__FILE__), 'twitter_badge_options_page');
}

function twitter_badge_options_page(){
	// if postback, store options
	if (isset($_POST['info_update'])){
		check_admin_referer();


		update_option(key_badge_twitter, $_POST["tac"]);
		update_option(key_badge_text, str_replace(" ","&nbsp;",$_POST["bt"]));
		update_option(key_badge_color, $_POST["bc"]);
		update_option(key_badge_text_color, $_POST["tc"]);

		// update notification
		echo "<div class='updated'><p><strong>Twitter Badge options updated</strong></p></div>";
	}

	// output the options page

?>
<script src="<?php echo plugins_url('twitter-badge'); ?>/DHTMLcolors/201a.js" type="text/javascript"></script>
<h1>Twitter Badge generator v0.1</h1>
<br />Generate a Twitter Badge  for your site like the one in the right side.
<div id="colorpicker201" class="colorpicker201"></div>


<form method="post" action="options-general.php?page=<?php echo basename(__FILE__); ?>">
<table>
<tr><td>Twitter account:</td><td><input type="text" id="tac" name="tac" value="<?php echo stripslashes(get_option(key_badge_twitter));?>" /></td> </tr>
<tr><td>Badge text:</td><td> <input type="text" id="bt" name="bt" value="<?php echo stripslashes(get_option(key_badge_text));?>" /> </td> </tr>
<tr><td>Color:</td><td> <input onclick="showColorGrid2('bc','bc');" type="text" id="bc" name="bc" value="<?php echo stripslashes(get_option(key_badge_color));?>" /> 
<img  id="bcc" name="bcc" src="<?php echo plugins_url('twitter-badge'); ?>/DHTMLcolors/sel.gif" />
</td> </tr>
<tr><td>Text color:</td><td> <input onclick="showColorGrid2('tc','tc');" type="text" id="tc" name="tc" value="<?php echo stripslashes(get_option(key_badge_text_color));?>" /> 
<img id="tcc" name="tcc" src="<?php echo plugins_url('twitter-badge'); ?>/DHTMLcolors/sel.gif" />
</td> </tr>
<tr><td colspan=2 align="right"><input type='submit' name='info_update' value='Update Options' /></td></tr>
</table>
</form>
  		

<?php
 twitter_badge_add();
}


function twitter_badge_add()
{	
	$taccount	= stripslashes(get_option(key_badge_twitter));
	$ttext		= stripslashes(get_option(key_badge_text));
	$bcolor		= stripslashes(get_option(key_badge_color));
	$tcolor		= stripslashes(get_option(key_badge_text_color));
	

	$twitter_badge ='
	<!--wp-twitterbadge by neo22s-->
	<style>.twitterBadge{ position:fixed;top:300px;right:0;width:30px;font-size:20px; font-family:Verdana, Geneva, sans-serif;-webkit-transform: rotate(-90deg);-moz-transform: rotate(-90deg);}
	.twitterBadge  a{background-image:url("http://lab.neo22s.com/twitterBadge/twittbird.png");background-repeat:no-repeat;background-position:top right;text-decoration:none;background-color:'.$bcolor.';color:'.$tcolor.';padding:0 35px 5px 10px;}</style>
	<!--[if IE]><style>.twitterBadge {top:200px;writing-mode: tb-rl; filter: flipv fliph;}.twitterBadge a {background-image:url("http://lab.neo22s.com/twitterBadge/twittbird-ie.png");background-position:left;padding:7px 0 32px 0}</style><![endif]-->
	<div class="twitterBadge">
		<a target="_blank" title="'.$taccount.' on Twitter" href="http://twitter.com/'.$taccount.'">'.$ttext.'</a>
	</div>
	<!--end wp-twitterbadge-->';

	echo $twitter_badge;
	return;

}

?>
