<?php

function generate_token($url)
{
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  //print_r($result);
  return json_decode($result);
}

function create_record($access_token, $url, $rating_obj)
{
  //Authorization: Zoho-oauthtoken access_token
  $header = array(
    'Authorization: Zoho-oauthtoken ' . $access_token,
    'Content-Type: application/json'
  );

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($rating_obj));
  curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
  $result = curl_exec($ch);
  curl_close($ch);
  var_dump($result);
}


function fetchRecord()
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Projects_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    fetchRecord();
  }
  return $results->data;
}

function creator_get_current_user_partner_id()
{

  //get the email of current user
  $current_user_email = wp_get_current_user()->user_email;
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $_SESSION['access_token'] = $access_token;
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Partners_Report?criteria=Email_address=="' . $current_user_email . '"',
    array(
      'method' => 'GET',
      'timeout'     => 10, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    return creator_get_current_user_partner_id();
  } else if ($results->code == "3000") {
    //print_r($results->code);
    return $results->data[0];
  }
}

function creator_get_roles_by_id($item_id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Roles_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => ['criteria' => 'Item_ID==' . $item_id]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_roles($partner_id);
  }
  return $results->data;
}

function creator_get_roles($partner_id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Roles_Report?criteria=Partner==' . $partner_id,
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_roles($partner_id);
  }
  return $results->data;
}

function creator_get_company($name)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Client_Report?criteria=Company_Name=="' . $name . '"',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_company($name);
  }
  return $results->data;
}

function creator_get_partner_weekly_timesheets_by_date($start_date, $end_date, $partner_id)
{
  //get the email of current user
  //$current_user_email = wp_get_current_user()->user_email;
  // $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  // $json_a = json_decode($zoho_credentials, true);
  // $access_token = $json_a["access_token"]; $_SESSION['access_token']
  $access_token =  $_SESSION['access_token'];
  //$criteria = 'Start_Date_Time>='.$start_date.'&& Start_Date_Time<='.$end_date.'&&Partner='.$partner_id;
  $criteria = '((Start_Date_Time>=\'' . $start_date . '\'&&' . 'Start_Date_Time <\'' . $end_date . '\')&&Partner=="' . $partner_id . '")';
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => [
        'criteria' => $criteria
      ]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_current_user_partner_id();
  }
  //print_r($response);
  return $results->data;
}



function creator_get_partner_time_entries($event_id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token =  $json_a['access_token'];
  $criteria = 'Event_ID=' . $event_id;
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => [
        'criteria' => $criteria
      ]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_current_user_partner_id();
  }
  // print_r($results);
  return $results->data;
}


function creator_get_partner($partner_id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token =  $json_a['access_token'];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Partners_Report?criteria=Partner_ID==' . $partner_id,
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => [
        'limit' => 1
      ]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_partner($partner_id);
  }
  return $results->data;
}

function creator_get_partner_tasks_by_date($start_date, $end_date, $partner_id)
{
  //get the email of current user
  //$current_user_email = wp_get_current_user()->user_email;
  // $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  // $json_a = json_decode($zoho_credentials, true);
  // $access_token = $json_a["access_token"]; 
  $access_token =  $_SESSION['access_token'];
  //$criteria = 'Start_Date_Time>='.$start_date.'&& Start_Date_Time<='.$end_date.'&&Partner='.$partner_id;
  $criteria = '((Start_Date_Time>=\'' . $start_date . '\'&&' . 'Start_Date_Time <\'' . $end_date . '\')&&Partner=="' . $partner_id . '")';
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => [
        'criteria' => $criteria,
        'limit' => 1
      ]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_partner_tasks_by_date($start_date, $end_date, $partner_id);
  }
  //print_r($response);
  return $results->data;
}

function creator_get_quote($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    "https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Quotes_Report",
    array(
      'method'      => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => ['criteria' => 'id1==' . $id]
    )
  );
  $results = json_decode($response['body']);
  return $results->data;
}

function creator_get_task_id($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    "https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report?criteria=Event_ID==" . $id,
    array(
      'method'      => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  return $results->data;
}

function creator_get_largest_time_entry_id()
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    "https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report",
    array(
      'method'      => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  $all_time_entries = $results->data;
  $largest = "0";
  foreach ($all_time_entries as $record) {
    $time_entry_id = (string) $record->Time_Entry_ID;
    if ($time_entry_id > $largest) {
      $largest = $time_entry_id;
    }
  }
  return $largest + 1;
}

function creator_get_largest_item_id()
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    "https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/All_Ratings",
    array(
      'method'      => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  $all_ratings = $results->data;
  $largest = "0";
  foreach ($all_ratings as $record) {
    $item_id = (string) $record->Item_ID;
    if ($item_id > $largest) {
      $largest = $item_id;
    }
  }
  return $largest + 1;
}

function creator_modify_rating($task, $id = false)
{

  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];
  $req_method = "PATCH";
  if ($id) {
    $request_url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/All_Ratings';
    // $task['criteria'] = "(Item_ID==" . $id . ")";
    $request = array(
      'data' => $task, 
      'criteria' => "(Item_ID==" . $id . ")"
    );
  } else {
    $request_url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/form/Ratings';
    $req_method = "POST";
    $request = array('data' => $task);
  }

  $response = wp_remote_post(
    $request_url,
    array(
      'method'      => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => json_encode($request)
    )
  );
  $results = json_decode($response['body']);
  if ($req_method == "PATCH") return $results;
  return $results->data;
  // return $id;
}


function creator_modify_task($task, $id = false)
{

  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $fields = array(
    'data' => $task
  );

  if ($id) {
    $request_url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report/' . $id;
  } else {
    $request_url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report';
  }

  $response = wp_remote_post(
    $request_url,
    array(
      'method' => 'PATCH',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => json_encode($fields)
    )
  );
  $results = json_decode($response['body']);
  return $results->data;
}

function creator_delete_time_entry($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report',
    array(
      'method' => 'DELETE',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => json_encode(['criteria' => 'Time_Entry_ID==' . $id])
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_delete_time_entry($id);
  }
  return $results;
}

function creator_delete_task($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report?criteria=Event_ID=="' . $id . '"',
    array(
      'method' => 'DELETE',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token]
    )
  );
  $results = json_decode($response['body']);
  return $results->data;
}



function creator_modify_time_entry($timelog, $id = 'new')
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token =  $json_a['access_token'];
  $req_method = 'PATCH';
  if ($id == 'new') {
    $req_method = 'POST';
    $url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/form/Time_Entries';
  }
  else {
    $url = 'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report/' . $id;
  }
  $response = wp_remote_post(
    $url,
    array(
      'method' => $req_method,
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => json_encode($timelog)
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_current_user_partner_id();
  }
  return $results;
}

function creator_get_time_entry_id($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token =  $json_a['access_token'];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report?criteria=Time_Entry_ID==' . $id,
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_current_user_partner_id();
  }
  return $results->data;
}

function creator_get_time_entry_by_event_id($event_id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token =  $json_a['access_token'];
  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Time_Entries_Report',
    array(
      'method' => 'GET',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => ['criteria' => 'Event_ID==' . $event_id]
    )
  );
  $results = json_decode($response['body']);
  if ($results->code == "1030") {
    update_token("Error");
    creator_get_current_user_partner_id();
  }
  return $results->data;
}


function creator_approve_task($id, $rating = 4, $comments = '')
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $fields = array(
    'status' => 'additional5',
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
  );

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report?criteria=Event_ID=="' . $id . '"',
    array(
      'method' => 'PATCH',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
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

function creator_request_task_amendment($id, $comments = false)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $field = array(
    'status'       => 'task_status3',
    'is_completed' => false
  );

  if ($comments) {
    $fields['request']['custom_fields']['c_comments'] = $comments;
  }

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/Tasks_Report?criteria=Event_ID=="' . $id . '"',
    array(
      'method' => 'PATCH',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
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

function creator_request_rating_amendment($id, $comments = false)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $field = array(
    'request' => array(
      'Status' => 'status_10'
    )
  );

  if ($comments) {
    $fields['request']['c_ta_comments'] = $comments;
  }

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/All_Ratings?criteria=Item_ID=="' . $id . '"',
    array(
      'method' => 'PATCH',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
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

function creator_delete_rating($id)
{
  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/All_Ratings',
    array(
      'method' => 'DELETE',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
      'body' => ['criteria' => "Item_ID==" . $id]
    )
  );
  $results = json_decode($response['body']);
  return $results->data;
}

function creator_approve_rating($id, $rating = 4, $comments = '')
{

  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $access_token = $json_a["access_token"];

  $fields = array(
    'request' => array(
      'is_completed' => 1,
      'Status'       => 'status_30',
      'Partner_Comments' => $comments,
      'Partner_Rating'   => $rating
    )
  );

  $response = wp_remote_post(
    'https://creator.zoho.eu/api/v2/projectpartners88/scoro/report/All_Ratings?criteria=Item_ID=="' . $id . '"',
    array(
      'method' => 'PATCH',
      'timeout'     => 60, // added
      'redirection' => 5,  // added
      'blocking'    => true, // added
      'headers' => ['Authorization' => 'Zoho-oauthtoken ' . $access_token],
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


function update_token($msg)
{

  $zoho_credentials = file_get_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json");
  $json_a = json_decode($zoho_credentials, true);
  $client_id = $json_a["client_id"];
  $client_secret = $json_a["client_secret"];
  $refresh_token = $json_a["refresh_token"];
  $base_acc_url = 'https://accounts.zoho.eu';
  $service_url = 'https://creator.zoho.eu';
  // $code = '1000.142974cc4bdc0b029836a39492246540.e2b2065a42148fb1dc805ea38f62b738';
  $code = '1000.3537acfb52b082c6135d6104ba252f3e.8f7bb0aae1395b80f2a1817823ac1abb';
  if ($refresh_token == "" || $msg == "") {
    $refresh_token_url = $base_acc_url . '/oauth/v2/token?grant_type=authorization_code&client_id=' . $client_id . '&client_secret=' . $client_secret . '&redirect_uri=http://my-projectpartners.local&code=' . $code;
    $rep = generate_token($refresh_token_url);
    $json_a["access_token"] = $rep->access_token;
    $json_a["refresh_token"] = $rep->refresh_token;
    $refresh_token = $json_a["refresh_token"];
  } else if ($msg == "Error") {
    $access_token_url = $base_acc_url . '/oauth/v2/token?refresh_token=' . $refresh_token . '&client_id=' . $client_id . '&client_secret=' . $client_secret . '&grant_type=refresh_token';
    $rep = generate_token($access_token_url);
    $json_a["access_token"] = $rep->access_token;
  }


  $enc_data = json_encode($json_a, JSON_PRETTY_PRINT);
  file_put_contents($_SERVER['DOCUMENT_ROOT'] . "/wp-content/themes/my-project-partners/zoho-token.json", $enc_data);
  $_SESSION['access_token'] = $json_a["access_token"];
  return $json_a["access_token"];
}
