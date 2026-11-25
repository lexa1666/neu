<?php
error_reporting(0);

$mailToSend = 'info@moeller-innenausbau.de';
$mailToCustomerFrom = 'no-reply@moeller-innenausbau.de';

if ( $_SERVER['REQUEST_METHOD'] === 'POST' ) {
	$name       = $_POST['name'];
	$email      = $_POST['email'];
	$message    = $_POST['message'];
	$regulation = $_POST['regulation'];
    $antiSpam   = $_POST['honey'];
	$errors     = Array();
	$return     = Array();

	if ( empty( $name ) ) {
		array_push( $errors, 'name' );
	}
	if ( ! filter_var( $email, FILTER_VALIDATE_EMAIL ) ) {
		array_push( $errors, 'email' );
	}
	if ( empty( $message ) ) {
		array_push( $errors, 'message' );
	}
	if ( empty( $regulation ) ) {
		array_push( $errors, 'regulation' );
	}
    
    if (empty($antiSpam)) {
      
    } else {
        $return['status'] = 'ok';
    }

	if ( count( $errors ) > 0 ) {
		$return['errors'] = $errors;
	} else {
        
	// Subjects
	$subjectToOwner = "Anfrage an die Firma"; 
	$subjectToCustomer = "Danke für ihre Nachricht"; 

	// Set Messages
	$messageToOwner = "<table>
    <tr>
    <td valign=\"top\"><b>Name:</b>&nbsp;&nbsp;</td>
    <td>$name</td>
    </tr>    
    <tr>
     <td valign=\"top\"><b>Email:</b>&nbsp;&nbsp;</td>
    <td>$email</td>
    </tr>
    <tr>
     <td valign=\"top\"><b>Message:</b>&nbsp;&nbsp;</td>
    <td>$message</td>
    </tr>
    </table>	
	<br /><br />----- 
	<br /> Request from moeller-innenausbau.de
	<br />
	";

	$messageToCustomer = "
	<p><strong>Sehr geehrte(r) $name,</strong></p>
	<p>Vielen Dank für ihre Nachricht. Wir werden uns umgehend bei ihnen melden
</p><p>Beste Grüße,
<br /> Axel
</p> 
	 

	";

	// Set From: headers
    $ownerName = "TidyCustoms";   
	$fromToOwner =  $name . " <" . $email . ">";
	$fromToCustomer =  $ownerName . " . $mailToCustomerFrom . ";

   	// Email Headers
	$headersToOwner = "From: " . $fromToOwner . "\r\n";
	$headersToOwner .= "Reply-To: ". $email . "\r\n";
	$headersToOwner .= "MIME-Version: 1.0\r\n";
	$headersToOwner .= "Content-Type: text/html; charset=UTF-8\r\n";

	$headersToCustomer = "From: " . $fromToCustomer . "\r\n";
	$headersToCustomer .= "Reply-To: ". mailToSend . "\r\n";
	$headersToCustomer .= "MIME-Version: 1.0\r\n";
	$headersToCustomer .= "Content-Type: text/html; charset=UTF-8\r\n";

		if ( mail( $mailToSend, 'Client Request: '.$name.' ' . date( "d-m-Y"), $messageToOwner, $headersToOwner ) ) {
            $mail = mail($email, $subjectToCustomer, $messageToCustomer, $headersToCustomer);
			$return['status'] = 'ok';
		} else {
			$return['status'] = 'error';
		}
	}

	header( 'Content-Type: application/json' );
	echo json_encode( $return );
}