<?php

/**
 * Reminder plugin for unvalidated accounts
 *
 */

elgg_register_event_handler('init', 'system', 'validation_reminder_init');

/**
 *  Init validation_reminder plugin
 */
$days_till_first_reminder;
$days_till_second_reminder;
$days_till_removal;

function validation_reminder_init() {
    global $days_till_first_reminder;
    global $days_till_second_reminder;
    global $days_till_removal;

    $days_till_first_reminder = elgg_get_plugin_setting("validation_reminder_first_message");
    $days_till_second_reminder = elgg_get_plugin_setting("validation_reminder_second_message");
    $days_till_removal = elgg_get_plugin_setting("validation_reminder_remove");

    elgg_register_plugin_hook_handler('cron', 'daily', 'clean_unvalidate');

}

function clean_unvalidate() {
    global $days_till_first_reminder;
    global $days_till_second_reminder;
    global $days_till_removal;

    access_show_hidden_entities(TRUE);
    $proviousIgnoreAccess = elgg_set_ignore_access(true);
    $dbprefix = elgg_get_config('dbprefix');

    // @var $users ElggUser[]
	$users = elgg_get_entities([
		'type' => 'user',
		'limit' => false,
		'joins' => [
			'JOIN ' . $dbprefix . 'users_entity ue ON e.guid = ue.guid',
			'JOIN ' . $dbprefix . 'metadata n_table1 on e.guid = n_table1.entity_guid',
			'JOIN ' . $dbprefix . 'metastrings msn1 on n_table1.name_id = msn1.id',
			'JOIN ' . $dbprefix . 'metastrings msv1 on n_table1.value_id = msv1.id '
		]
	]);


	foreach ($users as $user) {
        if(!elgg_get_user_validation_status($user->getGUID())) {
            $validate_reminder_start_date_data = array('guid' => $user->guid,
                'metadata_name' => validate_reminder_start_date);
            if ($validate_reminder_start_date = elgg_get_metadata($validate_reminder_start_date_data)) {

                $validate_reminder_start_date = $validate_reminder_start_date[0]->value;

                if (time() - $validate_reminder_start_date >= $days_till_removal * 24 * 60 * 60) {
                    echo 'account removed ';
                    if($user->delete()){
                        echo "deleted";
                    }else{
                        echo "error";
                    }
                }

                else if (time() - $validate_reminder_start_date >= $days_till_second_reminder * 24 * 60 * 60) {
                    echo 'send second reminder send';
                    send_validation_reminder_mail($user,$days_till_second_reminder);
                }

                else if (time() - $validate_reminder_start_date >= $days_till_first_reminder * 24 * 60 * 60) {
                    echo 'send first reminder send';
                    send_validation_reminder_mail($user,$days_till_first_reminder);
                }

                else{
                    echo 'waiting for validation';
                }
                echo ' for user: ' . $user->getGUID() . '<br>';
            }

            else{
                $user->validate_reminder_start_date = time();
                $user->save();
                echo 'start date made';
            }
        }
	}

	elgg_set_ignore_access($proviousIgnoreAccess);

}

function send_validation_reminder_mail($user,$pastdays){
    global $days_till_removal;
    echo $pastdays;
    $site = elgg_get_site_entity();

    $code = uservalidationbyemail_generate_code($user->getGUID(), $user->email);
    $link = $site->url . 'uservalidationbyemail/confirm?u=' . $user->getGUID() . '&c=' . $code;

    $subject = elgg_echo(
        'validation_reminder:validate:token:subject',
        array(
            $user->name,
            $site->name
        ),
        $user->language
    );

    $body = elgg_echo(
        'validation_reminder:validate:token:body',
        array(
            $user->name,
            $pastdays,
            $site->name,
            $user->token,
            $link,
            $site->name,
            $site->url,
            $days_till_removal
        ),
        $user->language
    );

    // Send validation email
    notify_user($user->guid, $site->guid, $subject, $body, array(), 'email');
}