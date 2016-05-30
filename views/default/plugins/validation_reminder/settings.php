<?php
/**
 * Plugin settings
 */
$days_options = array(
	"1" => elgg_echo("1 dag"),
	"2" => elgg_echo("2 dagen"),
	"3" => elgg_echo("3 dagen"),
	"4" => elgg_echo("4 dagen"),
	"5" => elgg_echo("5 dagen"),
	"6" => elgg_echo("6 dagen"),
	"7" => elgg_echo("7 dagen")
);

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