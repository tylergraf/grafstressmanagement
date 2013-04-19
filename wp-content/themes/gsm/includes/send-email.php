<?php
require_once( '../../../../wp-load.php' );
$todayis = date("l, F j, Y, g:i a") ;

if(isset($_POST["name"]) && $_POST["name"] && isset($_POST["email"]) && $_POST["email"] && isset($_POST["question"]) && $_POST["question"]){
	$name=urldecode(stripcslashes($_POST["name"]));
	echo($name);
	$subject = "A question from ".$name;
	
	$notes = urldecode(stripcslashes($_POST["question"]));
	
	$message = " $todayis [EST] \n
	
	Question: $notes \n
	
	";
	
	$from = "From: ".$_POST["email"];
	
	$emailToSend=get_opt('_email');
	mail($emailToSend, $subject, $message, $from);
}
?>
