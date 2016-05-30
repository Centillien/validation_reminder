<?php

/**
 * Reminder plugin for unvalidated accounts
 *
 */

elgg_register_event_handler('init', 'system', 'validation_reminder_init');

/**
 *  Init validation_reminder plugin
 */
function validation_reminder_init()
{
    elgg_register_plugin_hook_handler('cron', 'daily', 'clean_unvalidate');

}

function clean_unvalidate($vars)
{
    $days_till_first_reminder = elgg_get_plugin_setting("validation_reminder_first_message") * 1;
    $days_till_second_reminder = elgg_get_plugin_setting("validation_reminder_second_message") * 1;
    $days_till_removal = elgg_get_plugin_setting("validation_reminder_remove") * 1;

    access_show_hidden_entities(TRUE);
    $proviousIgnoreAccess = elgg_set_ignore_access(true);
    $dbprefix = elgg_get_config('dbprefix');

    // @var $users ElggUser[]
    $users = elgg_get_entities_from_metadata([
        'type' => 'user',
        'limit' => false,
        'metadata_name_value_pair' => array(
            array(
                'name' => 'validated',
                'value' => false
            )
        )
    ]);


    foreach ($users as $user) {
        $validate_reminder_start_date_data = array(
            'guid' => $user->guid,
            'metadata_name' => "validate_reminder_start_date"
        );
        if ($validate_reminder_start_date = elgg_get_metadata($validate_reminder_start_date_data)) {
            $validate_reminder_start_date = $validate_reminder_start_date[0]->value;

            if (time() - $validate_reminder_start_date >= $days_till_removal * 24 * 60 * 60) {
                $user->delete();
                echo 'account removed ';
            } else if (time() - $validate_reminder_start_date >= $days_till_second_reminder * 24 * 60 * 60) {
                send_validation_reminder_mail($user,$days_till_removal,$days_till_second_reminder);
                echo 'send second reminder send';
            } else if (time() - $validate_reminder_start_date >= $days_till_first_reminder * 24 * 60 * 60) {
                send_validation_reminder_mail($user,$days_till_removal,$days_till_first_reminder);
                echo 'send first reminder send';
            } else {
                echo 'waiting for validation';
            }
        } else {
            $user->validate_reminder_start_date = time();
            $user->save();
            echo 'checkprofile createrd';
        }

        echo ' for user: ' . $user->getGUID() . '<br>';
    }

    elgg_set_ignore_access($proviousIgnoreAccess);

}

function send_validation_reminder_mail($user,$enddate,$pastdays)
{
    $daysleft = $enddate-$pastdays;
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
            $site->name,
            $pastdays,
            $user->token,
            $link,
            $site->name,
            $site->url,
            $daysleft
        ),
        $user->language
    );

    // Send validation email
    notify_user($user->guid, $site->guid, $subject, $body, array(), 'email');
}