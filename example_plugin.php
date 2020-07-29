
<?php
//To show the plug in wordpress plugins website->wp-content->plugins->{name of folder for plugin}->plugin.php
/**
*Plugin Name: Idea Pro Example Plugin
*Description: This is just an example plugin
**/

	function ideapro_example_function()
	{
		$content = "This is a very basic plugin."; //simple variable
		$content .= "<div>This is a div</div>"; //.= add the variable
		$content .= "<p>This is a block of paragraph text.</p>";

		return $content; //return it
	}
	add_shortcode('example','ideapro_example_function'); //add element to shortcode option in wordpress


	function ideapro_admin_menu_option()
	{
		add_menu_page('Header & Footer Scripts','Site Scripts','manage_options','ideapro-admin-menu','ideapro_scripts_page','',200);
        //wordpress function Add a top-level menu page. Hook it to ideapro_scripts_page. https://developer.wordpress.org/reference/functions/add_menu_page/
	}

	add_action('admin_menu','ideapro_admin_menu_option'); //hook ideapro(a callback function) to admin_menu in wordpress

	function ideapro_scripts_page()
	{

		if(array_key_exists('submit_scripts_update',$_POST)) //check if in the post form have values when clicked
		{
			update_option('ideapro_header_scripts',$_POST['header_scripts']); //update the ideapro_header_scripts in DB using post-> take it from the ['header_scripts'] form
			update_option('ideapro_footer_scripts',$_POST['footer_script']);

			?>
			<div id="setting-error-settings-updated" class="updated settings_error notice is-dismissible"><strong>Settings have been saved.</strong></div>
			<?php
            //wordpress notice in the class https://digwp.com/2016/05/wordpress-admin-notices/

		}

		$header_scripts = get_option('ideapro_header_scripts','none');//make inside the database ideapro_header_scripts and take the value from there, none = value if its false (no value)
		$footer_scripts = get_option('ideapro_footer_scripts','none');


		?>// HTML part ->form with post action
		<div class="wrap"> 
			<h2>Update Scripts</h2>
			<form method="post" action="">
			<label for="header_scripts">Header Scripts</label>
			<textarea name="header_scripts" class="large-text"><?php print $header_scripts;//take header_scripts from database ?></textarea>
			<label for="footer_scripts">Footer Scripts</label>
			<textarea name="footer_script" class="large-text"><?php print $footer_scripts; ?></textarea>
			<input type="submit" name="submit_scripts_update" class="button button-primary" value="UPDATE SCRIPTS">
			</form>
		</div>	
		<?php
        //
	}


	function ideapro_display_header_scripts()
	{
		$header_scripts = get_option('ideapro_header_scripts','none');

		print $header_scripts;
	}
	add_action('wp_head','ideapro_display_header_scripts'); //hook to wp_head (Prints scripts or data in the head tag on the front end.) print $header_script from DB

	function ideapro_display_footer_scripts()
	{
		$footer_scripts = get_option('ideapro_footer_scripts','none');
		print $footer_scripts;
	}
	add_action('wp_footer','ideapro_display_footer_scripts');//hook to wp_footer (Prints scripts or data in the head tag on the front end.) print $footer_scripts from DB

	/* Part 3 of the plugin tutorial */

	function ideapro_form()
	{
		/* content variable */
		$content = '';
        //contact form with html
		$content .= '<form method="post" action="https://www.ideapro.com/example/thank-you/">';

			$content .= '<input type="text" name="full_name" placeholder="Your Full Name" />';
			$content .= '<br />';

			$content .= '<input type="text" name="email_address" placeholder="Email Address" />';
			$content .= '<br />';

			$content .= '<input type="text" name="phone_number" placeholder="Phone Number" />';
			$content .= '<br />';

			$content .= '<textarea name="comments" placeholder="Give us your comments"></textarea>';
			$content .= '<br />';

			$content .= '<input type="submit" name="ideapro_submit_form" value="SUBMIT YOUR INFORMATION" />';

		$content .= '</form>';

		return $content;
	}
	add_shortcode('ideapro_contact_form','ideapro_form');//ideapro_contact_form whats called in client side to show on page with shortcode [ideapro_contact_form] -> most be unique 


	function set_html_content_type()
	{
		return 'text/html';

	}



	function ideapro_form_capture()
	{
		global $post,$wpdb;

		if(array_key_exists('ideapro_submit_form',$_POST))//if array key exists in the post method 
		{
			$to = "support@ideapro.com";//send to email
			$subject = "Idea Pro Example Site Form Submission";
			$body = '';

			$body .= 'Name: '.$_POST['full_name'].' <br /> ';//capture the information -> <br/> html code force line breaks to look nice in mail
			$body .= 'Email: '.$_POST['email_address'].' <br /> ';
			$body .= 'Phone: '.$_POST['phone_number']. ' <br /> ';
			$body .= 'Comments: '.$_POST['comments'].' <br /> ';


			add_filter('wp_mail_content_type','set_html_content_type');//make the mail use the html use set_html_content_type function
			
			wp_mail($to,$subject,$body);

			remove_filter('wp_mail_content_type','set_html_content_type');//remove the filer -> back to normal

			/* Insert the information into a comment */

			$time = current_time('mysql');

			$data = array(
			    'comment_post_ID' => $post->ID, //get post if from mysql
			    'comment_content' => $body,
			    'comment_author_IP' => $_SERVER['REMOTE_ADDR'],//server array where the user coming from
			    'comment_date' => $time, //mysql time
			    //'comment_approved' => 1,
			);

			wp_insert_comment($data);//inserd the comment in wp admin menu under comments section

			/* add the submission to the database using the table we created  */
			$insertData = $wpdb->get_results(" INSERT INTO ".$wpdb->prefix."form_submissions (data) VALUES ('".$body."') ");
		}//$wpdb->prefix use to get the wp_ prefix

	}
	add_action('wp_head','ideapro_form_capture');//wp_head -> loads automatic when wordpress page load





?>