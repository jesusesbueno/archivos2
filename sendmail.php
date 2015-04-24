<?php

/**
 * Define the from email
 */ 
 
// email
define('TO_EMAIL', 'jesus.borreguero@gmail.com'); 
define('TOO_EMAIL', 'jesus@esbueno.org'); 
define('FROM_EMAIL', 'jesus.borreguero@gmail.com');  
define('FROM_NAME', 'Nando - Web '); 





/** 


 * define the body of the email. You can add some shortcode, with this format: %ID%
 * 
 * ID = the id have you insert on the html markup.
 * 
 * e.g.
 * <input type="text" name="email" />
 *       
 * You can add on BODY, this:
 * email: %email%   
 */ 
define( 'BODY', '%message%<br /><br /><small>email enviado  por %name%, <br/>email %email%.</small>' );
define( 'SUBJECT', 'Email enviado desde su sitio web ' );

// here the redirect, when the form is submitted
define( 'ERROR_URL', 'contatti_error.html' );
define( 'SUCCESS_URL', 'contatti_success.html' ); 
define( 'NOTSENT_URL', 'contatti_notsent.html' );           

// the message feedback of ajax request
$msg = array(
    'error' => '<p class="error">Rellena correctamente los campos marcados </p>',
    'success' => '<p class="success">Su email, se ha enviado correctemante. Gracias!</p>    <script type="text/javascript">  
    window.location="https://plus.google.com/117070888700958934746/posts ";  
    </script>  ',
    'not-sent' => '<p class="error">Ocurrió un error, inténtelo de nuevo. Gracias.</p>'
);         
    
// the field required, by name
$required = array( 'name', 'email', 'message' );

/**
 * Send the email.
 * 
 * SERVER-SIDE: the functions redirect to some URL, in base of result of control and send.
 * The urls must be set in the constants above: ERROR_URL, SUCCESS_URL, NOTSENT_URL
 * 
 * CLIENT-SIDE: in js/contact.js, there is already script for real-time checking of fields
 * and for ajax request of send email, that request in this page (sendmail.php) and echo the feedback message.    
 */   
sendemail();
    
// NO NEED EDIT
function sendemail() 
{
    global $msg, $required;
    
    if ( isset( $_POST['ajax'] ) )
        $ajax = $_POST['ajax'];
    else
        $ajax = false;
    
	if ( isset( $_POST['action'] ) AND $_POST['action'] == 'sendmail' ) 
	{
	    $body = BODY;
	    
	    $post_data = array_map( 'stripslashes', $_POST );
	    
// 	    print_r($post_data);
// 	    die;
	    
	    foreach ( $required as $id_field ) {
    	    if( $post_data[$id_field] == '' || is_null( $post_data[$id_field] ) ) {
    	        if ( $ajax )
    	           end_ajax( $msg['error'] );
    	        else
    	    	   redirect( ERROR_URL );
    	    }                       
    	}
	    
	    if( !is_email( $post_data['email'] ) OR $post_data['email'] == '' ) 
	        if ( $ajax )
	           end_ajax( $msg['error'] );
	        else
    	       redirect( ERROR_URL );
	    
	    foreach( $post_data as $id => $var )
	    {
	    	if( $id == 'message' ) $var = nl2br($var);
			$body = str_replace( "%$id%", $var, $body );	
		}
	    
		$headers  = 'MIME-Version: 1.0' . "\r\n";
		$headers .= 'Content-type: text/html; charset=UTF-8' . "\r\n";

		$headers .= 'From: '.FROM_NAME.' <'.FROM_EMAIL.'>' . "\r\n" ;
		$headers .='Bcc: '.TOO_EMAIL."\r\n".'Reply-To: ' . $post_data['email'];
	
	    $sendmail = mail( TO_EMAIL, SUBJECT, $body, $headers );
	         
		if ( $sendmail ) 
	        if ( $ajax )
	           end_ajax( $msg['success'] );
	        else
    	       redirect( SUCCESS_URL );
	    else
	        if ( $ajax )
	           end_ajax( $msg['not-sent'] );
	        else
    	       redirect( NOTSENT_URL );
	} 
}

function is_email($email) 
{
    if (!preg_match("/[a-z0-9][_.a-z0-9-]+@([a-z0-9][0-9a-z-]+.)+([a-z]{2,4})/" , $email))
    {
        return false;
    }
    else
    {
        return true;
    }
}             

function end_ajax( $msg = '' ) {
    echo $msg;
    die;
}           

function redirect( $redirect = '' ) {
    header( 'Location: ' . $redirect );
    die;
}      

?>
