<?php
/* TIMESHEET EDITING */

add_action('wp_ajax_save_time_entry', 'handle_save_time_entry_form');
add_action('wp_ajax_nopriv_save_time_entry', 'handle_save_time_entry_form');

function handle_save_time_entry_form()
{

    if (isset($_POST['taskId'])) {
        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['taskId']));

        //validate if user has access to edit this timesheet
        if (!isset($_POST['editNonce']) || !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)) {
            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {
            // if user has access to edit this timesheet
            // get ID of time-entry - if it's a new entry, the ID is 'new'
            $timelog_id = sanitize_text_field($_POST['id']);
            $timesheet = creator_get_task_id($task_id)[0];
            $time_entries = creator_get_time_entry_by_event_id($task_id);

            $utc = new DateTimeZone('+01:00');
            $london = new DateTimeZone('Europe/London');

            $start_date = DateTime::createFromFormat('D M d Y H:i:s T ', explode('(', sanitize_text_field($_POST['startDate']))[0]);
            $end_date = DateTime::createFromFormat('D M d Y H:i:s T ', explode('(', sanitize_text_field($_POST['endDate']))[0]);

            // $start_date->setTimezone($utc);
            // $end_date->setTimezone($utc);

            // foreach ($timesheet->time_entries as $time_entry) {
            //     $timezone = explode('+', $time_entry->start_datetime)[1];
            //     $time_entry->start_date = DateTime::createFromFormat('Y-m-d\TH:i:s\+' . $timezone, $time_entry->start_datetime, $utc);
            //     //$time_entry->start_date->setTimezone($london);
            //     if ($timelog_id == $time_entry->time_entry_id || $start_date->format('Y-m-d') !== $time_entry->start_date->format('Y-m-d')) {
            //         continue;
            //     } else {
            //         $time_entry->end_date = DateTime::createFromFormat('Y-m-d\TH:i:s\+01:00', $time_entry->end_datetime, $utc);
            //         //$time_entry->end_date->setTimezone($london);
            //         if (($start_date <= $time_entry->start_date && $end_date <= $time_entry->start_date) || ($start_date >= $time_entry->start_date && $start_date >= $time_entry->end_date)) {
            //             continue;
            //         } else {
            //             // if not, send error
            //             wp_send_json_error([
            //                 'currentStart' => $start_date,
            //                 'currentEnd' => $end_date,
            //                 'errorStart' => $time_entry->start_date,
            //                 'errorEnd' => $time_entry->end_date,
            //                 'errorStartString' => $time_entry->start_datetime,
            //                 'message' => 'Time entries cannot overlap.'
            //             ]);
            //             wp_die();
            //         }
            //     }
            // }

            // check if timelog is new or if user has access to editing an existing one
            if ($timelog_id === 'new' || creator_get_time_entry_id($timelog_id)[0]->Event_ID == $task_id) {

                // set up timelog data
                $timelog = [];
                $timelog_creator = [];

                $timelog_creator['Time_Entry_ID'] = $timelog_id == 'new' ? '' : $timelog_id;
                $timelog_creator['Event_ID'] = $task_id;
                $timelog_creator['Tasks'] = creator_get_task_id($task_id)[0]->ID;
                $timelog_creator['is_completed'] = 0;
                $timelog_creator['is_confirmed'] = 0;
                $timelog_creator['Time_entry_Type'] = 'task';
                
                //sanitize stuff
                $is_planned = (sanitize_text_field($_POST['isPlanned']) == 'true');
                $timelog_creator['Activity_ID'] = SCORO_CLIENT_WORK_ACTIVITY_ID;
                $timelog_creator['Title'] = sanitize_text_field($_POST['activityTitle']);
                $timelog_creator['Start_Date_Time'] = $start_date->format('d-M-Y H:i:s');
                $timelog_creator['End_Date_time'] = $end_date->format('d-M-Y H:i:s');
                $quote_id = sanitize_text_field($_POST['quoteId']);
                $timelog['quote_id'] = $quote_id === 'false' ? false : $quote_id;

                $date_diff = $start_date->diff($end_date);
                $timelog_creator['Duration'] = str_pad($date_diff->h, 2, '0', STR_PAD_LEFT) . ':' . $date_diff->i . ':00';


                // check if the activity is billable
                if (!$is_planned) {

                    // if its unplanned, its always non-billable
                    $timelog_creator['is_billable'] = 0;
                    $timelog_creator['Billable_Duration'] = '00:00:00';

                    // we need to sort out activity codes if it's client work
                    $timelog_creator['Description'] = sanitize_key($_POST['activity']);
                    $timelog_creator['Activity_ID'] = SCORO_CLIENT_WORK_ACTIVITY_ID;
                    // if ($timelog['quote_id'] > 0) {
                    //     $timelog['description'] = $timelog['activity_id'];
                    //     $timelog['activity_id'] = SCORO_CLIENT_WORK_ACTIVITY_ID;
                    //     $quote = scoro_get_quote($timelog['quote_id']);
                    //     $quote = creator_get_quote($timelog['quote_id']);
                    // } else {
                    //     $timelog['description'] = 'Other';
                    //     $timelog_creator['Description'] = 'Other';
                    // }
                } else {
                    $timelog_creator['Billable_Duration'] = $timelog_creator['Duration'];

                    // if its planned, we'll have to check the role (passed as activity ID)
                    $role = creator_get_roles_by_id(explode('-', $_POST['activity'])[1])[0];
                    $week_start_date = new DateTime($start_date->format('Y-m-d'));
                    if ($week_start_date->format('D') !== 'Mon') {
                        $week_start_date = new DateTime($start_date->format('Y-m-d') . ' 00:00:00 last Monday');
                    }
                    $week_end_date = new DateTime($week_start_date->format('Y-m-d') . ' 23:59:59');
                    $week_end_date->modify('+6 days');

                    // get date period
                    $timelog_week = iterator_to_array(
                        new DatePeriod(
                            $week_start_date,
                            new DateInterval('P1D'),
                            $week_end_date
                        )
                    );

                    // check if duration doesn't overrun allocated planned time
                    $fte = (float) $role->FTE;
                    if ($role->Permanent_Role) {
                        $days_to_work_this_week = 5;
                    } else {
                        // if FTE is not 100%, check if role/placement active (needs logged time) this week
                        $role_dates = get_each_date_from_periods($role->Dates);
                        $days_to_work_this_week = 0;
                        $max_duration = 0;
                        foreach ($role_dates as $role_date) {
                            if (in_array($role_date, $timelog_week)) {
                                $days_to_work_this_week++;
                            }
                        }
                        if ($days_to_work_this_week > 5) $days_to_work_this_week = 5;
                    }
                    $max_duration = $days_to_work_this_week * 8 * 60 * $fte;

                    if (intval($_POST['roleDuration']) > $max_duration) {
                        // throw error
                        wp_send_json_error([
                            'message' => 'You can only log ' . floor($max_duration / 60) . ' hours of planned time for this role.',
                            'week' => $timelog_week,
                            'day' => $week_start_date->format('D')
                        ]);
                        wp_die();
                    }

                    $timelog_creator['is_billable'] = (int) $role->is_Billable;
                    $timelog_creator['Description'] = sanitize_text_field($_POST['activity']);
                    // set 'Planned Activity' for the Scoro activity field
                    $timelog_creator['Activity_ID'] = SCORO_PLANNED_ACTIVITY_ID;
                }

                // validate that start date is earlier than end date
                if ($start_date < $end_date) {

                    // progress with processing and set up scoro array from object
                    // unset($timelog['is_planned']);
                    // unset($timelog['quote_id']);

                    // create or edit new timelog
                    $timelog_creator = array('data' => $timelog_creator);
                    if ($timelog_id == 'new') {
                        $creator_id = 'new';
                        $time_entry_creator = creator_modify_time_entry($timelog_creator, $creator_id);
                        $new_time_entry_id = $time_entry_creator->data->ID;
                        $req_body = ['data' => ['Time_Entry_ID' => $new_time_entry_id]];
                        $time_entry_creator = creator_modify_time_entry($req_body, $new_time_entry_id);
                    }
                    else {
                        $creator_id = creator_get_time_entry_id($timelog_id);
                        $creator_id = $creator_id[0]->ID;
                        $time_entry_creator = creator_modify_time_entry($timelog_creator, $creator_id);
                    }

                    //send success response
                    wp_send_json_success([
                        'message' => $timelog_id === 'new' ? 'Timelog added.' : 'Timelog updated.',
                        'timelog' => $time_entry_creator,
                        'json_timelog' => $timelog_creator,
                        'maxduration' => $max_duration
                    ]);
                } else {
                    // throw error
                    wp_send_json_error([
                        'message' => 'End time needs to be after start time.'
                    ]);
                }
            } else {
                // if not, send error
                wp_send_json_error([
                    'message' => 'Time entry validation failed.'
                ]);
            }
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Task validation failed.'
        ]);
    }
    wp_die();
}

add_action('wp_ajax_delete_time_entry', 'handle_delete_time_entry_form');
add_action('wp_ajax_nopriv_delete_time_entry', 'handle_delete_time_entry_form');

function handle_delete_time_entry_form()
{

    if (isset($_POST['taskId'])) {
        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['taskId']));

        //validate if user has access to edit this timesheet
        if (!isset($_POST['editNonce']) || !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)) {
            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {
            // if user has access to edit this timesheet
            // get ID of time-entry - if it's a new entry, the ID is 'new'
            $timelog_id = sanitize_text_field($_POST['id']);

            // check if timelog is new or if user has access to editing an existing one
            $event_id = creator_get_time_entry_id($timelog_id)[0]->Event_ID;
            if ($event_id == $task_id) {

                // $time_entry = scoro_delete_time_entry($timelog_id);
                $time_entry = creator_delete_time_entry($timelog_id);

                //send success response
                wp_send_json_success([
                    'message' => 'Timelog deleted.',
                    'response' => $time_entry
                ]);
            } else {
                // if not, send error
                wp_send_json_error([
                    'message' => 'Time entry validation failed'
                ]);
            }
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Task validation failed'
        ]);
    }
    wp_die();
}

add_action('wp_ajax_save_subtimesheet', 'handle_save_subtimesheet_form');
add_action('wp_ajax_nopriv_save_subtimesheet', 'handle_save_subtimesheet_form');

function handle_save_subtimesheet_form()
{

    if (isset($_POST['partnerId']) && isset($_POST['mainTaskId'])) {
        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['mainTaskId']));
        // get partner ID
        $partner_id = intval(sanitize_key($_POST['partnerId']));

        //validate if user has access to edit this timesheet
        if (!isset($_POST['partnerNonce']) || !wp_verify_nonce($_POST['partnerNonce'], 'partner_permission_' . $partner_id) || !isset($_POST['editNonce']) || !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)) {
            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {
            // set up task array that we are going to send to scoro
            $task = [];
            // compile misc. data, rating, comments
            $type_and_id = explode('-', sanitize_text_field($_POST['id']));
            $task['status'] = 'task_status2';
            $task['custom_fields'] = array(
                'c_partner' => $partner_id,
                'c_partner_rating' => sanitize_key($_POST['rating']),
                'c_sow' => sanitize_text_field($_POST['sow']),
                'c_parent_task' => $task_id,
                'c_partner_comment' => sanitize_text_field($_POST['comments']),
                'c_is_billable' => 1
            );
            if ($type_and_id[0] === 'role') {
                $task['custom_fields']['c_linked_role'] = $type_and_id[1];
                $scoro_role = scoro_get_role($type_and_id[1]);
                $timesheet_approver = $scoro_role->c_timesheet_approver;
                $role_name = $scoro_role->c_service;
                $company_name = $scoro_role->c_companyname;
            } else {
                $task['quote_id'] = $type_and_id[1];
                $scoro_quote = scoro_get_quote($task['quote_id']);
                // $timesheet_approver = scoro_get_partner($partner_id)->c_timesheet_approver;
                $timesheet_approver = scoro_get_custom_field($scoro_quote, 'c_timesheet_approver');
                $role_name = 'Unplanned activity';
                $company_name = $scoro_quote->company_name;
            }
            $company_name = strlen($company_name > 1) ? $company_name : 'Project Partners';
            $task['company_name'] = $company_name;
            if (strlen($task['custom_fields']['c_sow']) < 1) {
                $task['event_name'] = $task['company_name'] . '-' . $partner_id;
            } else {
                $task['event_name'] = $task['custom_fields']['c_sow'] . '-' . $task['company_name'] . '-' . $partner_id;
            }

            $task['person_id'] = $timesheet_approver;
            $task['custom_fields']['c_rolename'] = $role_name;
            $task['custom_fields']['c_companyname'] = $company_name;
            $task['is_completed'] = false;
            // compile timelogs
            $timelogs = [];

            $post_timelogs = json_decode(stripslashes($_POST['timelogs']), true);
            foreach ($post_timelogs as $post_timelog) {
                $timelog = [];

                $start_date = DateTime::createFromFormat('Y-m-d\TH:i:s.000Z', $post_timelog['startDate']);
                $end_date = DateTime::createFromFormat('Y-m-d\TH:i:s.000Z', $post_timelog['endDate']);

                $timelog['is_completed'] = 0;
                //sanitize stuff
                $timelog['is_planned'] = (sanitize_text_field($post_timelog['isPlanned']) === 'true');
                $timelog['activity_id'] = sanitize_key($post_timelog['activity']);
                $timelog['title'] = sanitize_text_field($post_timelog['activityTitle']);
                $timelog['start_datetime'] = $start_date->format('c');
                $timelog['end_datetime'] = $end_date->format('c');

                // check if the activity is billable
                if ($type_and_id[0] === 'role') {
                    $timelog['description'] = $timelog['activity_id'];
                    $timelog['activity_id'] = SCORO_CLIENT_WORK_ACTIVITY_ID;
                } else {
                    $timelog['description'] = $timelog['activity_id'];
                    // set 'Planned Activity' for the Scoro activity field
                    $timelog['activity_id'] = SCORO_PLANNED_ACTIVITY_ID;
                }
                if (!isset($task_start_date)) {
                    $task_start_date = $timelog['start_datetime'];
                    $task_end_date = $timelog['end_datetime'];
                } else {
                    $task_start_date = ($task_start_date > $timelog['start_datetime']) ? $timelog['start_datetime'] : $task_start_date;
                    $task_end_date = ($task_end_date > $timelog['end_datetime']) ? $timelog['end_datetime'] : $task_end_date;
                }

                $timelogs[] = $timelog;
            }
            $task['time_entries'] = $timelogs;
            $task['start_datetime'] = $task_start_date;
            $task['end_datetime'] = $task_end_date;

            // check if subtask exists
            if ($subtask_id = intval(sanitize_key($_POST['subTaskId']))) {
                // delete subtask because removing time entries would take a lot of requests
                scoro_delete_task($subtask_id);
            }
            if ($type_and_id[0] === 'role') {
                // check for the planned duration of the task
                if ($_POST['duration'] && $_POST['durationNonce']) {
                    $minutes = intval(sanitize_key($_POST['duration']));
                    //validate if user has access to edit this timesheet
                    if (!isset($_POST['durationNonce']) || !wp_verify_nonce($_POST['durationNonce'], 'weekly_role_duration_' . $minutes)) {
                        // if not, send error
                        wp_send_json_error([
                            'message' => 'Could not validate timesheet.'
                        ]);
                    } else {
                        $task['duration_planned'] = str_pad(floor($minutes / 60), 2, '0', STR_PAD_LEFT) . ':' . str_pad($minutes % 60, 2, '0', STR_PAD_LEFT) . ':00';
                        // if it doesn't, create Scoro subtask
                        $new_task = scoro_modify_task($task);
                        //send success response
                        wp_send_json_success([
                            'message' => 'Subtimesheet created',
                            'result' => $new_task
                        ]);
                    }
                } else {
                    // if not, send error
                    wp_send_json_error([
                        'message' => 'Could not create timesheet.'
                    ]);
                }
            } else {
                $task['duration_planned'] = '00:00:00';
                // if it doesn't, create Scoro subtask
                $new_task = scoro_modify_task($task);
                //send success response
                wp_send_json_success([
                    'message' => 'Subtimesheet created',
                    'result' => $new_task
                ]);
            }
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Timesheet validation failed.'
        ]);
    }
    wp_die();
}




add_action('wp_ajax_save_main_timesheet', 'handle_save_main_timesheet_form');
add_action('wp_ajax_nopriv_save_main_timesheet', 'handle_save_main_timesheet_form');

function handle_save_main_timesheet_form()
{


    if (isset($_POST['partnerId']) && isset($_POST['mainTaskId'])) {
        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['mainTaskId']));
        // get partner ID
        $partner_id = intval(sanitize_key($_POST['partnerId']));

        //validate if user has access to edit this timesheet
        if (!isset($_POST['partnerNonce']) || !wp_verify_nonce($_POST['partnerNonce'], 'partner_permission_' . $partner_id) || !isset($_POST['editNonce']) || !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)) {
            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {

            // set up task array that we are going to send to scoro
            $task = [];

            // compile misc. data, rating, comments
            $type_and_id = explode('-', sanitize_text_field($_POST['id']));
            // $task['company_name'] = 'Project Partners';
            $task['Company_Name'] = 'Project Partners';
            // $task['custom_fields'] = array(
            //     'c_partner' => $partner_id,
            //     'c_partner_rating' => sanitize_key($_POST['rating']),
            //     'c_partner_comment' => sanitize_text_field($_POST['comments']),
            //     'c_weekly_timesheet' => 1
            // );
            $task['Partner'] = $partner_id;
            $task['Rating'] = sanitize_key($_POST['rating']);
            $task['Comments'] = sanitize_text_field($_POST['comments']);
            $task['Weekly_Timesheet'] = 1;
            // $task['event_id'] = $task_id;
            $task['Event_ID'] = $task_id;
            // $task['status'] = 'task_status2';
            $task['Status'] = 'task_status2';
            $task['is_completed'] = false;
            // $task['c_rolename'] = scoro_get_partner($partner_id)->c_position;
            $task['Role_name'] = creator_get_partner($partner_id)[0]->Position;

            //TODO: flag timesheet for approval

            // update Scoro subtask
            // $updated_task = scoro_modify_task($task, $task_id);
            $creator_task_id = creator_get_task_id($task_id)[0]->ID;
            $updated_task = creator_modify_task($task, $creator_task_id);
            //send success response
            wp_send_json_success([
                'message' => 'Weekly timesheet updated.',
                // 'result' => $updated_task
                'result' => $updated_task
            ]);
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Timesheet validation failed.'
        ]);
    }
    wp_die();
}



add_action('wp_ajax_delete_subtimesheet', 'handle_delete_subtimesheet_form');
add_action('wp_ajax_nopriv_delete_subtimesheet', 'handle_delete_subtimesheet_form');

function handle_delete_subtimesheet_form()
{

    if (isset($_POST['partnerId']) && isset($_POST['mainTaskId'])) {
        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['mainTaskId']));
        // get partner ID
        $partner_id = intval(sanitize_key($_POST['partnerId']));

        //validate if user has access to edit this timesheet
        if (!isset($_POST['partnerNonce']) || !wp_verify_nonce($_POST['partnerNonce'], 'partner_permission_' . $partner_id) || !isset($_POST['editNonce']) || !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)) {
            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {
            // check if subtask exists
            if ($subtask_id = intval(sanitize_key($_POST['subTaskId']))) {
                // delete Scoro subtask
                // scoro_delete_rating($subtask_id);
                creator_delete_rating($subtask_id);
                //send success response
                wp_send_json_success([
                    'message' => 'Subtimesheet deleted.'
                ]);
            } else {
                // if not, send error
                wp_send_json_success([
                    'message' => 'Subtimesheet was not saved so we can remove it safely.',
                ]);
            }
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Timesheet validation failed.'
        ]);
    }
    wp_die();
}


/* TIMESHEET APPROVAL */

add_action('wp_ajax_approve_timesheet', 'handle_timesheet_form');
add_action('wp_ajax_nopriv_approve_timesheet', 'handle_timesheet_form');

function handle_timesheet_form()
{

    if (
        !isset($_POST['approve-nonce']) ||
        !wp_verify_nonce($_POST['approve-nonce'], 'approve_timesheet')
    ) {

        wp_send_json_error([
            'message' => 'Validation failed'
        ]);
    }

    // get task ID from query param
    parse_str(parse_url($_POST['_wp_http_referer'])['query'], $parameters);

    // get things we need to update task
    $task_id = $parameters['task'];

    $ratings = explode(',', sanitize_text_field($_POST['ratings']));

    $rating_number = sanitize_key($_POST['rating']);
    $comments = sanitize_text_field($_POST['comments']);

    $last_rating = array_pop($ratings);
    // request the amendment of relevant scoro ratings
    foreach ($ratings as $rating) {
        $success = scoro_approve_rating($rating, $rating_number, stripslashes($comments));
    }
    if ($last_rating) {
        $success = scoro_approve_rating($last_rating, $rating_number, stripslashes($comments));
    }

    $all_ratings = scoro_get_ratings_of_task($task_id);

    $all_ratings_approved = true;

    foreach ($all_ratings as $rating) {
        if ($rating->status !== 'status_30') {
            $all_ratings_approved = false;
        }
    }

    // update and approve task
    if ($all_ratings_approved) {
        scoro_approve_task($task_id, $rating, stripslashes($comments));
    }

    if ($success) {
        //send success response
        wp_send_json_success([
            'message' => 'Timesheet approved.',
            'approve' => $all_ratings_approved
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Please try again.'
        ]);
    }
    wp_die();
}


/* TIMESHEET REQUEST AMENDMENT */

add_action('wp_ajax_request_amend_timesheet', 'handle_timesheet_amendment_request_form');
add_action('wp_ajax_nopriv_request_amend_timesheet', 'handle_timesheet_amendment_request_form');

function handle_timesheet_amendment_request_form()
{

    if (
        !isset($_POST['amend-nonce']) ||
        !wp_verify_nonce($_POST['amend-nonce'], 'amend_timesheet')
    ) {

        wp_send_json_error([
            'message' => 'Validation failed'
        ]);
    }

    // get task ID from query param
    parse_str(parse_url($_POST['_wp_http_referer'])['query'], $parameters);

    // get things we need to update task
    $task_id = $parameters['task'];
    $comments = sanitize_text_field($_POST['amendment-comments']);
    $ratings = explode(',', sanitize_text_field($_POST['ratings']));

    // pop last one because of trailing comma
    $last_rating = array_pop($ratings);
    // request the amendment of scoro rating
    foreach ($ratings as $rating) {
        scoro_request_rating_amendment($rating, stripslashes($comments));
    }
    if ($last_rating) {
        scoro_request_rating_amendment($last_rating, stripslashes($comments));
    }

    // request the amendment of the timesheet (scoro task)
    $amendment_requested = scoro_request_task_amendment($task_id);

    $partner = scoro_get_partner(scoro_get_custom_field($amendment_requested, 'c_partner'));

    if ($amendment_requested) {

        // set up email notification
        $to = $partner->c_emailaddress;
        $subject = '🚨 Please amend your timesheet';

        $body = '<style type="text/css">
    body {
      font: 13px "Lucida Grande", "Lucida Sans Unicode", Tahoma, Verdana, sans-serif; background-color: #efefef; padding: 5px 0 10px 0;
    }
    </style>

    <div id="header" style="width: 680px; padding: 0px; margin: 0 auto;text-align: left;">
        <h1><a href="https://my.project.partners">
            <img class="aligncenter wp-image-9" role="img" src="https://my.project.partners/wp-content/uploads/2021/10/PP-Logo.svg" alt="Project Partners" width="200" height="26">
        </a></h1>
    </div>
    <div id="body" style="width: 600px; background: white; padding: 40px; margin: 0 auto; text-align: left;">

    <h1 style="font-size: 30px; margin-bottom: 4px;">Hi ' . $partner->c_firstname . ' <img src="https://s.w.org/images/core/emoji/13.1.0/72x72/1f44b.png" class="wp-smiley" style="height: 1em; max-height: 1em;"></h1>
    <p>Your timesheet approver has a query your timesheet.</p>';

        if (strlen($comments) > 0) $body .= '</p>Here are the comments:<br><strong>' . $comments . '</strong>.</p>';

        $body .= '<p> You can amend your timesheet by <a href="' . get_site_url() . '/time-machine" style="font-weight:bold;">clicking here.</a></p>
    <div class="section" style="margin-bottom: 24px;">Thank you,<br>
    <strong>Project Partners</strong><br>
    </div>
    </div>
    <div id="footer" style="width: 680px; padding: 0px; margin: 0 auto; text-align: center;"></div>';

        $headers = array('Content-Type: text/html; charset=UTF-8');

        //send email notification
        wp_mail($to, $subject, $body, $headers);


        //send success response
        wp_send_json_success([
            'message' => 'Amendment requested.',
            'result' => $amendment_requested,
            'ratings' => $ratings[0]
        ]);
    } else {
        wp_send_json_error([
            'message' => 'Please try again.'
        ]);
    }
    wp_die();
}

add_action('wp_ajax_save_ratingform', 'handle_save_ratingform');
add_action('wp_ajax_nopriv_save_ratingform', 'handle_save_ratingform');

function handle_save_ratingform()
{

    if (isset($_POST['partnerId']) && isset($_POST['mainTaskId'])) {

        //get time entry ID if exists
        $task_id = intval(sanitize_key($_POST['mainTaskId']));

        // get partner ID
        $partner_id = intval(sanitize_key($_POST['partnerId']));

        //validate if user has access to edit this timesheet
        if (
            !isset($_POST['partnerNonce'])                                                  || 
            !wp_verify_nonce($_POST['partnerNonce'], 'partner_permission_' . $partner_id)   || 
            !isset($_POST['editNonce'])                                                     || 
            !wp_verify_nonce($_POST['editNonce'], 'modify_task_' . $task_id)
        ) {

            // if not, send error
            wp_send_json_error([
                'message' => 'Validation failed'
            ]);
        } else {

            // compile misc. data, rating, comments
            $type_and_id = explode('-', sanitize_text_field($_POST['id']));
            $rating_creator_data = array(
                'Status' => 'status_20',
                'Partner' => creator_get_current_user_partner_id($partner_id)->ID,
                'Partner_Rating' => sanitize_key($_POST['rating']),
                'SoW' => sanitize_text_field($_POST['sow']),
                'Task' => creator_get_task_id($task_id)[0]->ID,
                'Partner_Comments' => sanitize_text_field($_POST['comments']),
                'Start_Date' => date_format(date_create($_POST['ratingStartDate']), "d-M-Y"),
                'End_Date' => date_format(date_create($_POST['ratingEndDate']), "d-M-Y")
            );

            
            if ($type_and_id[0] === 'role') {
                $rating_creator_data['Role'] = $type_and_id[1];
                $creator_role = creator_get_roles_by_id($type_and_id[1])[0];
                $timesheet_approver = $creator_role->Timesheet_Approver;
                $role_name = $creator_role->Service;
                $company_name = $creator_role->Company_Name;

                // save planned time and actual time worked so we can easily report on it
                // planned time
                if ($creator_role->Permanent_Role) {
                    $days_to_work_this_week = 5;
                } else {

                    // if FTE is not 100%, check if role/placement is active (needs logged time) this week
                    $dates = get_each_date_from_periods($creator_role->Dates);
                    $current_week = iterator_to_array(
                        new DatePeriod(
                            new DateTime($rating_creator_data['Start_Date']),
                            new DateInterval('P1D'),
                            new DateTime($rating_creator_data['End_Date'])
                        )
                    );
                    $days_to_work_this_week = 0;
                    foreach ($dates as $date) {
                        if (in_array($date, $current_week)) {
                            $days_to_work_this_week++;
                        }
                    }
                    if ($days_to_work_this_week > 5) $days_to_work_this_week = 5;
                }
                $rating_creator_data['Planned_Hours'] = round($days_to_work_this_week * 8 * $creator_role->FTE, 2);

                // actual time
                $rating_creator_data['Actual_Hours'] = round(($_POST['unplannedDuration'] + $_POST['roleDuration']) / 60, 2);
            } else {
                // $rating_creator_data['c_quote'] = $type_and_id[1];
                $creator_quote = creator_get_quote($type_and_id[1])[0];
                $timesheet_approver = creator_get_custom_field($creator_quote,'Timesheet Approver');
                $role_name = 'Unplanned work';
                $company_name = $creator_quote->Company_Name;

                // // planned time
                // $rating['c_planned_work_time'] = 0;
                $rating_creator_data['Planned_Hours'] = 0;
                
                // // actual time worked
                $rating_creator_data['Actual_Hours'] = round($_POST['unplannedDuration'] / 60, 2);
            }
            $company_name = (strlen($company_name) > 1) ? $company_name : 'Project Partners';
            $startdate = DateTime::createFromFormat('Y-m-d', $rating_creator_data['Start_Date']);
            $date_reformat = date_format(date_create($rating_creator_data['Start_Date']), "Y-m-d");
            $rating_creator_data['Title'] = $date_reformat . ' - ' . $partner_id . ' - ' . $rating_creator_data['SoW'];
            $rating_creator_data['Timesheet_Approver'] = $timesheet_approver;
            $rating_creator_data['Company_Name'] = creator_get_company($company_name)[0]->ID;

            // check if rating exists
            if ($rating_id = intval(sanitize_key($_POST['subTaskId']))) {

                // if it exists
                $new_rating = creator_modify_rating($rating_creator_data, $rating_id);

                //send success response
                $rating_creator_data['Item_ID'] = (string) creator_get_largest_item_id();
                wp_send_json_success([
                    'message' => 'Rating modified',
                    'result' => $rating_creator_data,
                    'response' => $new_rating
                ]);
            } else {

                // if it doesn't, create Scoro rating
                $rating_creator_data['Item_ID'] = (string) creator_get_largest_item_id();
                $new_rating = creator_modify_rating($rating_creator_data);

                //send success response
                wp_send_json_success([
                    'message' => 'Rating created',
                    'result' => $rating_creator_data,
                    'response' => $new_rating
                ]);
            }
        }
    } else {
        // if not, send error
        wp_send_json_error([
            'message' => 'Timesheet validation failed.'
        ]);
    }
    wp_die();
}
