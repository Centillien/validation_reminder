<?php
/**
 * Plugin settings
 */
$maxdays = 15;

$days_options = array(
	"1" => elgg_echo("validation_reminder:1day")
);

for($i=2;$i<=$maxdays;$i++){
	$days_options[$i] = elgg_echo("validation_reminder:Xdays",array($i));
}

$validation_reminder_first_message = $vars['entity']->validation_reminder_first_message;
$validation_reminder_second_message = $vars['entity']->validation_reminder_second_message;
$validation_reminder_remove = $vars['entity']->validation_reminder_remove;

echo elgg_echo('validation_reminder:first_message_input');
echo '<br>';
echo elgg_view("input/dropdown", array("name" => "params[validation_reminder_first_message]", "value" => $validation_reminder_first_message, "options_values" => $days_options));
echo '<br>';
echo '<br>';

echo elgg_echo('validation_reminder:second_message_input');
echo '<br>';
echo elgg_view("input/dropdown", array("name" => "params[validation_reminder_second_message]", "value" => $validation_reminder_second_message, "options_values" => $days_options));
echo '<br>';
echo '<br>';

echo elgg_echo('validation_reminder:remove_input');
echo '<br>';
echo elgg_view("input/dropdown", array("name" => "params[validation_reminder_remove]", "value" => $validation_reminder_remove, "options_values" => $days_options));
echo '<br>';
echo '<br>';