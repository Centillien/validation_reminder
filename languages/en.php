<?php
/**
 * 	en.php - English language variables
 */
$english = array(
	'validation_reminder:first_message_input' => "First reminder will be send in:",
	'validation_reminder:second_message_input' => "Second reminder will be send in:",
	'validation_reminder:remove_input' => "Account will be removed after:",
	'validation_reminder:Xdays' => "%s days",
	'validation_reminder:1day' => "1 day",

	'validation_reminder:validate:token:subject' => "%s, please validate your email %s!",
	'validation_reminder:validate:token:body' => "%s,
	
%s day(s) ago you registerd to %s.
Before you can start you using your account, you must confirm your email address.
	
Please confirm your email address by entering the following code into our app: %s

When you closed the app, you\'re also able to validate your account by clicking the following link: %s

There are %s days remaining before your account will be removed automaticaly.
%s
%s
"
);

add_translation("en", $english);
