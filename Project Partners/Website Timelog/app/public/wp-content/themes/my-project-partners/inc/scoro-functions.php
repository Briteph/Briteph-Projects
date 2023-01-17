<?php
/* SCORO API FUNCTIONS */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

function scoro_get_all_current_sows()
{
    $page = 1;
    $sows = scoro_get_current_sows($page);
    $new_sows = [1];

    while (count($new_sows)) {
        $page++;
        $new_sows = scoro_get_current_sows($page);
        if (!count($new_sows)) {
            break;
        } else {
            $sows = array_merge($sows, $new_sows);
        }
    }
    return $sows;
}

function scoro_get_current_sows($page = 1)
{
    $today = date('Y-m-d');
    $week_ago = date("Y-m-d", strtotime("-1 week"));

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'page' => $page,
        'per_page' => 100,
        'request' => array(),
        'filter'  => array(
            'custom_fields' => array(
                'c_servicescommence' => array(
                    'to_date' => $today
                ),
                'c_servicescomplete' => array(
                    'from_date' => $week_ago
                )
            )
        )
    );

    $results = wp_remote_post(
        SCORO_BASE_URL . 'sows/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $result = json_decode($results['body']);
    return $result->data;
}


function scoro_get_current_user_partner_id()
{
    //get the email of current user
    $current_user_email = wp_get_current_user()->user_email;

    $partner_fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter' => array(
            'c_emailaddress' => $current_user_email
        ),
    );

    $partner_result = wp_remote_post(
        SCORO_BASE_URL . 'partners/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($partner_fields)
        )
    );

    $partner_results = json_decode($partner_result['body']);

    return $partner_results->data[0]->item_id;
}




function scoro_get_roles($partner_id)
{

    $request = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter' => array(
            'c_partner' => $partner_id
        ),
        'per_page' => 100
    );

    $result_raw = wp_remote_post(
        SCORO_BASE_URL . 'roles/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($request)
        )
    );

    $result = json_decode($result_raw['body']);

    return $result->data;
}

function scoro_get_partner_tasks_by_date($start_date, $end_date, $partner_id)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter' => array(
            'start_datetime' => array(
                "from_date" => $start_date,
                "to_date"   => $end_date
            ),
            'end_datetime' => array(
                "from_date" => $start_date,
                "to_date"   => $end_date
            ),
            'custom_fields' => array(
                'c_partner' => $partner_id,
            )
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}


function scoro_get_partner_weekly_timesheets_by_date($start_date, $end_date, $partner_id)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter' => array(
            'start_datetime' => array(
                "from_date" => $start_date,
                "to_date"   => $end_date
            ),
            'end_datetime' => array(
                "from_date" => $start_date,
                "to_date"   => $end_date
            ),
            'custom_fields' => array(
                'c_partner' => $partner_id,
                'c_weekly_timesheet' => 1
            )
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_modify_task($task, $id = false)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => $task,
    );

    if ($id) {
        //modifying existing time entry
        $request_url = SCORO_BASE_URL . 'tasks/modify/' . $id;
    } else {
        //creating new time entry
        $request_url = SCORO_BASE_URL . 'tasks/modify/';
    }

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);
    return $results->data;
}


function scoro_get_activities($parent_name)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter'  => array(
            'parent_name' => $parent_name
        )
    );

    $results = wp_remote_post(
        SCORO_BASE_URL . 'activities/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $result = json_decode($results['body']);

    return $result->data;
}



function scoro_get_all_current_quotes()
{
    $quotes = scoro_get_current_quotes();
    $page = 1;
    if (count($quotes) < 1) return false;
    while (!(count($quotes) % 3)) {
        $page++;
        $quotes = array_merge($quotes, scoro_get_current_quotes($page));
    }
    return $quotes;
}

function scoro_get_current_quotes($page = 1)
{
    $today = date('Y-m-d');
    $week_ago = date("Y-m-d", strtotime("-1 week"));

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'page' => $page,
        'per_page' => 3,
        'request' => array(),
        'filter'  => array(
            'custom_fields' => array(
                'c_servicescommence' => array(
                    'to_date' => $today
                ),
                'c_servicescomplete' => array(
                    'from_date' => $week_ago
                )
            )
        ),
        'detailed_response' => 1
    );

    $results = wp_remote_post(
        SCORO_BASE_URL . 'quotes/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $result = json_decode($results['body']);
    return $result->data;
}

function scoro_get_time_entry($id)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );
    $response = wp_remote_post(
        SCORO_BASE_URL . 'timeEntries/view/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}


function scoro_get_quote($id)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );
    $response = wp_remote_post(
        SCORO_BASE_URL . 'quotes/view/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_get_role($role_id)
{

    $request = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );

    $result_raw = wp_remote_post(
        SCORO_BASE_URL . 'roles/view/' . $role_id,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($request)
        )
    );

    $result = json_decode($result_raw['body']);

    return $result->data;
}


/**
 * @param int $id
 * @param object $data
 * @return array 
 */
function scoro_modify_time_entry($timelog, $id = 'new')
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => $timelog,
    );

    $fields['request']['user_id'] = 11;

    if ($id === 'new') {
        //creating new time entry
        $request_url = SCORO_BASE_URL . 'timeEntries/modify/';
    } else {
        //modifying existing time entry
        $request_url = SCORO_BASE_URL . 'timeEntries/modify/' . $timelog['time_entry_id'];
    }

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);
    return $results->data;
}

function scoro_delete_time_entry($id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
    );

    //delete existing time entry
    $request_url = SCORO_BASE_URL . 'timeEntries/delete/' . $id;

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);

    return $results;
}


function scoro_get_partner($id)
{

    $request = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );

    $result_raw = wp_remote_post(
        SCORO_BASE_URL . 'partners/view/' . $id,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($request)
        )
    );

    $result = json_decode($result_raw['body']);

    return $result->data;
}
function scoro_get_task($id)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );
    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/view/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}


function scoro_delete_task($task_id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array()
    );

    //creating new time entry
    $request_url = SCORO_BASE_URL . 'tasks/delete/' . $task_id;

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);

    return $results->data;
}

function scoro_get_current_user_contact_id()
{
    $current_user_email = wp_get_current_user()->user_email;

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'filter' => array(
            'means_of_contact' => array(
                'email' => $current_user_email
            ),
        ),
    );

    //var_dump(json_encode($fields));

    $result = wp_remote_post(
        SCORO_BASE_URL . 'contacts/filters',
        array(
            'method' => 'POST',
            'timeout'     => 60,
            'redirection' => 5,
            'blocking'    => true,
            'body' => json_encode($fields)
        )
    );
    //var_dump($result);

    $results = json_decode($result['body']);
    if (count($results->data)) {
        return $results->data[0]->contact_id;
    } else {
        return false;
    }
}


/**
 * @param int $contact_id 
 * @return array 
 */
function scoro_get_tasks_related_to_contact($contact_id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'person_id' => $contact_id
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/filters',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_view_object($type, $object_id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
    );


    $result = wp_remote_post(
        SCORO_BASE_URL . $type . 's/view/' . $object_id,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);

    return $results->data;
}

function scoro_approve_task($id, $rating = 4, $comments = '')
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(
            'status'       => 'additional5',
            'custom_fields' => array(
                array(
                    'id' => 'c_rating',
                    'value' => $rating
                ),
                array(
                    'id' => 'c_comments',
                    'value' => $comments
                )
            )
        )
    );
    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/modify/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($response['body']);

    if ($results->statusCode == 200) {
        return true;
    } else {
        return false;
    }
}

/**
 * @param int $contact_id 
 * @return array 
 */

function scoro_get_pending_tasks_related_to_contact($contact_id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'person_id' => $contact_id,
            'status'    => 'task_status2'
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/filters',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_get_current_user_as_partner()
{

    //get the email of current user
    $current_user_email = wp_get_current_user()->user_email;

    $partner_fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
        'detailed_response' => true,
        'filter' => array(
            'c_emailaddress' => $current_user_email
        ),
    );

    $partner_result = wp_remote_post(
        SCORO_BASE_URL . 'partners/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($partner_fields)
        )
    );

    $partner_results = json_decode($partner_result['body']);

    return $partner_results->data[0];
}

function scoro_request_task_amendment($id, $comments = false)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(
            'status'       => 'task_status3',
            'is_completed' => false
        )
    );
    if ($comments) {
        $fields['request']['custom_fields']['c_comments'] = $comments;
    }
    $response = wp_remote_post(
        SCORO_BASE_URL . 'tasks/modify/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($response['body']);

    if ($results->statusCode == 200) {
        return $results->data;
    } else {
        return false;
    }
}

function scoro_request_rating_amendment($id, $comments = false)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(
            'status' => 'status_10'
        )
    );
    if ($comments) {
        $fields['request']['c_ta_comments'] = $comments;
    }
    $response = wp_remote_post(
        SCORO_BASE_URL . 'ratings/modify/' . $id,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($response['body']);

    if ($results->statusCode == 200) {
        return true;
    } else {
        return false;
    }
}


function scoro_modify_rating($rating_object, $id = false)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => $rating_object,
    );

    if ($id) {
        //modifying existing rating
        $request_url = SCORO_BASE_URL . 'ratings/modify/' . $id;
    } else {
        //creating new rating
        $request_url = SCORO_BASE_URL . 'ratings/modify/';
    }

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);

    return $results->data;
}

function scoro_get_ratings_of_task($task_id, $contact_id = false)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'c_task' => $task_id
        ),
        'detailed_response' => true,
    );
    if ($contact_id) $fields['filter']['c_timesheet_approver'] = $contact_id;

    $response = wp_remote_post(
        SCORO_BASE_URL . 'ratings/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_delete_rating($id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(),
    );

    //delete existing time entry
    $request_url = SCORO_BASE_URL . 'ratings/delete/' . $id;

    $result = wp_remote_post(
        $request_url,
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($result['body']);

    return $results;
}

function scoro_get_ratings_of_contact_by_status($contact_id, $status)
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'c_timesheet_approver' => $contact_id,
            'status'  => $status
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'ratings/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

/**
 * @param int $contact_id 
 * @return array 
 */
function scoro_get_ratings_of_contact($contact_id)
{

    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'c_timesheet_approver' => $contact_id
        ),
        'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'ratings/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}

function scoro_approve_rating($id, $rating = 4, $comments = '')
{
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'request' => array(
            'is_completed' => 1,
            'status'       => 'status_30',
            'c_ta_comments' => $comments,
            'c_ta_rating'   => $rating
        )
    );
    $response = wp_remote_post(
        SCORO_BASE_URL . 'ratings/modify/' . ($id),
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );
    $results = json_decode($response['body']);

    if ($results->statusCode == 200) {
        return true;
    } else {
        return false;
    }
}

function scoro_get_projects_of_timesheet_approver($contact_id)
{
    $now = date_create();
    $fields = array(
        'apiKey' => SCORO_API_KEY,
        'lang' => 'eng',
        'company_account_id' => SCORO_COMPANY_ID,
        'per_page' => 100,
        'request' => array(),
        'filter' => array(
            'custom_fields' => array(
                'c_timesheet_approver' => $contact_id,
                'c_servicescommence' => array(
                    'to_date' => $now->format('Y-m-d')
                ),
                'c_servicescomplete' => array(
                    'from_date' => $now->modify('-12 weeks')->format('Y-m-d')
                )
            )
        ),
        // 'detailed_response' => true,
    );

    $response = wp_remote_post(
        SCORO_BASE_URL . 'quotes/list',
        array(
            'method' => 'POST',
            'timeout'     => 60, // added
            'redirection' => 5,  // added
            'blocking'    => true, // added
            'body' => json_encode($fields)
        )
    );

    $results = json_decode($response['body']);

    return $results->data;
}
