<?php
/*
Plugin Name: Training Booking Form
Plugin URI: http://webranz.com
Description: Simple non-bloated WordPress Contact Form for training booking
Version: 1.0
Author: Jeevanantham Ganesan
*/

function html_form_code() {
	echo '<form action="' . esc_url( $_SERVER['REQUEST_URI'] ) . '" method="post">';
	echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.0/jquery.js" type="text/javascript" charset="utf-8"></script>';
    echo '<p>';
	echo 'Select the training (required) : <br/>';
	echo '<select id ="traininglist" name="traininglist">';
    echo '<option value=""> ------------ </option>';
    echo '<option value="Tableau Fundamentals training">Tableau Fundamentals training</option>';
    echo '<option value="Tableau Advanced training">Tableau Advanced training</option>';
    echo '<option value="Alteryx training">Alteryx training</option>';
    echo '</select>';
    echo '</p>';
    echo '<p>';
    echo 'Select the date : <br/>';
    echo '<select name="" id="" title="">';
    echo '<option value="option" > ------------- </option>';
    echo '</select>';
    echo '<select name="Tableau Fundamentals training" id="" title="">';
    echo '<option value="Feb22nd-23rd" >Feb 22nd - 23rd</option>';
    echo '<option value="Apr5th-6th" >Apr 5th - 6th</option>';
    echo '<option value="Oct19th-20th" >Oct 19th - 20th</option>';
    echo '</select>';
    echo '<select name="Tableau Advanced training" id="" title="">';
    echo '<option value="Feb25th-26th" >Feb 25th - 26th</option>';
    echo '<option value="May18th-19th" >May 18th - 19th</option>';
    echo '<option value="Aug9th-10th" >Aug 9th - 10th</option>';
    echo '</select>';
    echo '<select name="Alteryx training" id="" title="">';
    echo '<option value="DatesOnApplication" >Dates on application</option>';
    echo '</select>';
    echo '</p>';
    echo '<script type="text/javascript" charset="utf-8">
	$(\'select[name!="traininglist"]\').hide();
	$(\'select[name="\' + $(\'select[name="traininglist"]\').val() + \'"]\').show();
	$(\'select[name="traininglist"]\').change(function(){
    	$(\'select[name!="traininglist"]\').hide();
    	$(\'select[name="\' + $(this).val() + \'"]\').show();
	});    
    </script>';
	echo '<p>';
	echo 'Name :<br/>';
	echo '<input type="text" name="cf-name" pattern="[a-zA-Z0-9 ]+" required value="' . ( isset( $_POST["cf-name"] ) ? esc_attr( $_POST["cf-name"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Email :<br/>';
	echo '<input type="email" name="cf-email" required value="' . ( isset( $_POST["cf-email"] ) ? esc_attr( $_POST["cf-email"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Phone number: <br/>';
	echo '<input type="tel" name="cf-phnum" required value="' . ( isset( $_POST["cf-phnum"] ) ? esc_attr( $_POST["cf-phnum"] ) : '' ) . '" size="40" />';
	echo '</p>';
	echo '<p>';
	echo 'Dietary requirements: <br/>';
	echo '<textarea rows="3" cols="35" name="cf-message">' . ( isset( $_POST["cf-message"] ) ? esc_attr( $_POST["cf-message"] ) : '' ) . '</textarea>';
	echo '</p>';
	echo '<div style="text-align: center">';
    echo '<input type="submit" name="cf-submitted" value="Pay on invoice" style="margin-right: 120px">';
    echo '<input type="image" name="cf-submitted-pay" src = "https://www.paypalobjects.com/en_AU/i/btn/btn_paynow_LG.gif" value="Pay now" onClick="this.form.submit();"> *3.4% Surcharge will apply.';
    echo '</div>';
	echo '</form>';
}



function deliver_mail() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = "Training requested, want to pay through invoice";
		$phonenum = sanitize_text_field( $_POST["cf-phnum"] );
		$message = esc_textarea( $_POST["cf-message"] );
		$selectedOption = sanitize_text_field($_POST['traininglist']);
		$mailBody ="Name: $name\nEmail: $email\nPhone number : $phonenum\nTraining requested: $selectedOption\nDietary requirements: $message";

		// get the website administrator's email address
		#$to = get_option( 'admin_email' );
		$to = 'info@webranz.com';

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $mailBody, $headers ) ) {
			echo '<div>';
			echo '<p>Thanks for contacting us, expect a response soon.</p>';
			echo '</div>';
		} else {
			echo 'An unexpected error occurred';
		}
	}
}

function deliver_mail_paypal() {

	// if the submit button is clicked, send the email
	if ( isset( $_POST['cf-submitted-pay_x'], $_POST['cf-submitted-pay_y'] ) ) {

		// sanitize form values
		$name    = sanitize_text_field( $_POST["cf-name"] );
		$email   = sanitize_email( $_POST["cf-email"] );
		$subject = "Training requested and paying through paypal";
		$phonenum = sanitize_text_field( $_POST["cf-phnum"] );
		$message = esc_textarea( $_POST["cf-message"] );
		$selectedOption = sanitize_text_field($_POST['traininglist']);
		$mailBody ="Name: $name\nEmail: $email\nPhone number : $phonenum\nTraining requested: $selectedOption\nDietary requirements: $message";

		// get the website administrator's email address
		#$to = get_option( 'admin_email' );
		$to = 'info@webranz.com';

		$headers = "From: $name <$email>" . "\r\n";

		// If email has been process for sending, display a success message
		if ( wp_mail( $to, $subject, $mailBody, $headers ) ) {
			wp_redirect("https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=74AGMR7L9UY76");
		} else {
			echo 'An unexpected error occurred';
		}
	}
}

function cf_shortcode() {
	ob_start();
	deliver_mail();
	deliver_mail_paypal();
	html_form_code();

	return ob_get_clean();
}

add_shortcode( 'webranz_training_booking_form', 'cf_shortcode' );

?>
