<?php

/**

 * Template part for the calendar view for timesheet approvers.

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<?php
$main_task = $args['task'];
$partner = scoro_get_current_user_as_partner();

$is_approved = ($main_task->status == 'additional5') ? true : false;

// get start date
$start_date_str = $main_task->start_datetime;
$start_date = new DateTime($start_date_str);

if($start_date->format('D') !== 'Mon'){
    $start_date = new DateTime($start_date_str . ' Monday ago');
}

$end_date = clone $start_date;
$end_date->modify('+6 days');
$end_date->modify('+12 hours');
// get date period
$current_week = iterator_to_array(
    new DatePeriod(
        $start_date,
        new DateInterval('P1D'),
        $end_date
    )
);

$current_week_ymd = array_map(function($date){
 return $date->format('Y-m-d');
}, $current_week);
// get all roles 
$roles = scoro_get_roles($partner->item_id);
//var_dump($roles);
$total_minutes = 0;

foreach($roles as $key => $role){
    $fte = (float) $role->c_fte;
    // if permanent role, set workdays to 5
    if($role->c_permanentrole){
        $days_to_work_this_week = 5;
        $role->days_to_work = 5;
    }
    else {
        // if role is NOT permanent, check if role/placement is active (needs logged time) this week
        $dates = get_each_date_from_periods($role->c_dates);
        if(!$days_to_work_this_week || $days_to_work_this_week < 1) {
            $days_to_work_this_week = 0;
        }
        $role->days_to_work = 0;
        foreach($dates as $date){
            if(in_array($date, $current_week)){
                $days_to_work_this_week++;
                $role->days_to_work++;
                if($role->days_to_work > 5) $role->days_to_work = 5;
            }
        }
        if($days_to_work_this_week > 5) $days_to_work_this_week = 5;
    }
    $role->weekly_duration = $role->days_to_work * 8 * 60 * $fte;

    if($role->days_to_work < 1){
        unset($roles[$key]);
    }
    // var_dump($roles);
    $total_minutes = $total_minutes + $role->weekly_duration;
}
$roles = array_values($roles);


$total_hours = round($total_minutes / 60, 2);


// get all Scoro Rating objects associated with the timesheet
$ratings = scoro_get_ratings_of_task($main_task->event_id);

// loop through all time entries in the timesheet
foreach($main_task->time_entries as $timelog){
    if($timelog->activity_id === SCORO_PLANNED_ACTIVITY_ID || $timelog->activity_id === SCORO_CLIENT_WORK_ACTIVITY_ID){
        $minutes = explode(':', $timelog->duration);
        $minutes = (intval($minutes[0]) * 60 ) + intval($minutes[1]);
        $actual_total_minutes+=$minutes;
        $timelog_date = explode('T',$timelog->start_datetime)[0];
        $show_weekend = false;
        if($timelog_date === $current_week[5]->format('Y-m-d') || $timelog_date === $current_week[6]->format('Y-m-d')){
            $show_weekend = true;
        }
    }
}
$actual_hours = ($actual_total_minutes % 60) ? round( $actual_total_minutes / 60 , 2 )  : ($actual_total_minutes / 60);

?>

<div id="single-timesheet-container" class="timesheet-container timesheet-approver view-timesheet-page">

<div id="calendar-page-title"><?php echo esc_html($partner->c_fullname); ?>
        <div class="grey dates">
            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#6F8699"/></g></svg>
            <span class="uppercase normal normal-size"><?php echo esc_html($start_date->format('d.m.Y') . '-' . $end_date->format('d.m.Y')); ?></span>
        </div>
</div>
<div class="week-prehead flex mobile-stack space-between mt-2">
    <div class="title">
        <!-- display task dates -->
        <div class="grey dates">
            <svg width="12" height="14" viewBox="0 0 12 14" fill="none" xmlns="http://www.w3.org/2000/svg"><g><path d="M3.96429 7.875H2.89286C2.71607 7.875 2.57143 7.72734 2.57143 7.54688V6.45312C2.57143 6.27266 2.71607 6.125 2.89286 6.125H3.96429C4.14107 6.125 4.28571 6.27266 4.28571 6.45312V7.54688C4.28571 7.72734 4.14107 7.875 3.96429 7.875ZM6.85714 7.54688V6.45312C6.85714 6.27266 6.7125 6.125 6.53571 6.125H5.46429C5.2875 6.125 5.14286 6.27266 5.14286 6.45312V7.54688C5.14286 7.72734 5.2875 7.875 5.46429 7.875H6.53571C6.7125 7.875 6.85714 7.72734 6.85714 7.54688ZM9.42857 7.54688V6.45312C9.42857 6.27266 9.28393 6.125 9.10714 6.125H8.03571C7.85893 6.125 7.71429 6.27266 7.71429 6.45312V7.54688C7.71429 7.72734 7.85893 7.875 8.03571 7.875H9.10714C9.28393 7.875 9.42857 7.72734 9.42857 7.54688ZM6.85714 10.1719V9.07812C6.85714 8.89766 6.7125 8.75 6.53571 8.75H5.46429C5.2875 8.75 5.14286 8.89766 5.14286 9.07812V10.1719C5.14286 10.3523 5.2875 10.5 5.46429 10.5H6.53571C6.7125 10.5 6.85714 10.3523 6.85714 10.1719ZM4.28571 10.1719V9.07812C4.28571 8.89766 4.14107 8.75 3.96429 8.75H2.89286C2.71607 8.75 2.57143 8.89766 2.57143 9.07812V10.1719C2.57143 10.3523 2.71607 10.5 2.89286 10.5H3.96429C4.14107 10.5 4.28571 10.3523 4.28571 10.1719ZM9.42857 10.1719V9.07812C9.42857 8.89766 9.28393 8.75 9.10714 8.75H8.03571C7.85893 8.75 7.71429 8.89766 7.71429 9.07812V10.1719C7.71429 10.3523 7.85893 10.5 8.03571 10.5H9.10714C9.28393 10.5 9.42857 10.3523 9.42857 10.1719ZM12 3.0625V12.6875C12 13.4121 11.4241 14 10.7143 14H1.28571C0.575893 14 0 13.4121 0 12.6875V3.0625C0 2.33789 0.575893 1.75 1.28571 1.75H2.57143V0.328125C2.57143 0.147656 2.71607 0 2.89286 0H3.96429C4.14107 0 4.28571 0.147656 4.28571 0.328125V1.75H7.71429V0.328125C7.71429 0.147656 7.85893 0 8.03571 0H9.10714C9.28393 0 9.42857 0.147656 9.42857 0.328125V1.75H10.7143C11.4241 1.75 12 2.33789 12 3.0625ZM10.7143 12.5234V4.375H1.28571V12.5234C1.28571 12.6137 1.35804 12.6875 1.44643 12.6875H10.5536C10.642 12.6875 10.7143 12.6137 10.7143 12.5234Z" fill="#6F8699"/></g></svg>
            <span class="uppercase"><?php echo esc_html($start_date->format('d.m.Y') . '-' . $end_date->format('d.m.Y')); ?></span>
        </div>
        <h1 class="timesheet-name h3">Weekly Timesheet of <?php echo esc_html($partner->c_fullname); ?></h1>
    </div>
    <!-- display Partner details -->
    <!-- <div class="partner-badge">
        <div class="profile-img"><img src="<?php // echo esc_url(get_stylesheet_directory_uri() . '/svg/user.svg'); ?>"></div>
        <div class="text">
            <span><?php // echo esc_html($partner->c_fullname); ?></span>
        </div>
    </div> -->
</div>

<div class="week<?php if($show_weekend) { echo ' expanded'; }?>" id="timesheet-calendar">
    <div class="header flex">
        <div class="timestamp-spacer"></div>
        <?php foreach($current_week as $day) { 
            ?>
            <div class="day-header <?php echo esc_attr(strtolower($day->format('l'))); ?>">
                <h6 class="mt-0 mb-0"><span class="day-name small semibold grey"><?php echo esc_html($day->format('l')); ?></span><span class="date"><?php echo esc_html(substr($day->format('Y-m-d'), -2)); ?></span></h6>
            </div>

        <?php } ?>
    </div>

    <div class="body flex" id="calendar-body">
            <button type="button" class="expand-weekend" id="expand-weekend">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M14.7071 12.7071C14.3166 13.0976 13.6834 13.0976 13.2929 12.7071L10 9.41421L6.70711 12.7071C6.31658 13.0976 5.68342 13.0976 5.29289 12.7071C4.90237 12.3166 4.90237 11.6834 5.29289 11.2929L9.29289 7.29289C9.68342 6.90237 10.3166 6.90237 10.7071 7.29289L14.7071 11.2929C15.0976 11.6834 15.0976 12.3166 14.7071 12.7071Z" fill="#6E55FF"/></svg>
            </button>
        <div class="timestamps">
            <div class="timestamp"></div>
            <div class="timestamp">01:00</div>
            <div class="timestamp">02:00</div>
            <div class="timestamp">03:00</div>
            <div class="timestamp">04:00</div>
            <div class="timestamp">05:00</div>
            <div class="timestamp">06:00</div>
            <div class="timestamp">07:00</div>
            <div class="timestamp">08:00</div>
            <div class="timestamp" id="workday-start">09:00</div>
            <div class="timestamp">10:00</div>
            <div class="timestamp">11:00</div>
            <div class="timestamp">12:00</div>
            <div class="timestamp">13:00</div>
            <div class="timestamp">14:00</div>
            <div class="timestamp">15:00</div>
            <div class="timestamp">16:00</div>
            <div class="timestamp">17:00</div>
            <div class="timestamp">18:00</div>
            <div class="timestamp">19:00</div>
            <div class="timestamp">20:00</div>
            <div class="timestamp">21:00</div>
            <div class="timestamp">22:00</div>
            <div class="timestamp">23:00</div>
        </div>
        <?php foreach($current_week as $day) { ?>
            <div class="day <?php echo esc_attr(strtolower($day->format('l'))); ?>" data-date="<?php echo esc_attr($day->format('Y-m-d')); ?>">

            <?php for($x=0; $x<=22; $x++) { ?>
                <div class="hour"></div>
            <?php
            } ?>
            </div>
        <?php
        }
        ?>
        
    </div>


</div>

</div>



<div class="calendar-footer" id="calendar-footer">
        <div class="total-time">
            <h6 class="mt-0 mb-0">Worked hours</h6>
            <p class="small grey mt-0"><?php echo $actual_hours; ?> out of <?php echo $total_hours; ?> planned</p>
        </div>
        <div class="legend-wrap flex inline">
            <div class="badge dot green">Billed work</div>
            <div class="badge dot danger">Unbilled time</div>
            <div class="badge dot amber">Unbilled work</div>
        </div>

        <div>
        <?php if($is_approved) { ?>
            <div class="completed-task flex space-between">
                <div class="badge rounded uppercase bold">Approved</div>
            </div>
        <?php }
        else { 
        }
        ?>
        </div>
    </div>


<?php 

get_template_part('/template-parts/views/single-timesheet/partials/success-popup', null, array(
    'headline'  => 'Timesheet approved.',
    'button'    => array(
        'label' => 'Back to Timesheets',
        'url'   => site_url() . '/approve-timesheets'
    )
));

?>

<?php
$approve_nonce = wp_create_nonce('approve_weekly_task_' . $main_task->event_id);
?>


<?php
// set up array to check if we queried this activity from Scoro yet
$queried_activities = [];
// check the activity name for each time entry so we can show it correctly
foreach($main_task->time_entries as $time_entry){
    $title_timestamp = substr(explode('T', $time_entry->start_datetime)[1], 0, 5) . '-' . substr(explode('T', $time_entry->end_datetime)[1], 0, 5);
    
    if($time_entry->activity_id === SCORO_PLANNED_ACTIVITY_ID || $time_entry->activity_id === SCORO_CLIENT_WORK_ACTIVITY_ID){
        // get object type and ID to query from scoro
        $activity_object_type_and_id = explode('-', $time_entry->description);
        
        // check if we already queried this
        if(!array_key_exists($time_entry->description, $queried_activities)){
            $activity_object = scoro_view_object($activity_object_type_and_id[0], $activity_object_type_and_id[1]);
            // var_dump($activity_object);
            if($activity_object_type_and_id[0] === 'role'){
                foreach($roles as $role){
                    if($activity_object_type_and_id[1] = $role->item_id) break;
                }
                $role->c_service;
                $sow_string = (strlen($activity_object->c_sow) > 0) ? (' - ' . $activity_object->c_sow) : '';
                $time_entry->activity_name = $role->c_service . ' - ' . $activity_object->c_companyname . $sow_string;
            }
            else {
                $time_entry->activity_name = 'Unplanned work - ' . $activity_object->company_name . ' - ' . scoro_get_custom_field($activity_object, 'c_sow');
            }
            $queried_activities[$time_entry->description] = $time_entry->activity_name;
        }
        else {
            $time_entry->activity_name = $queried_activities[$time_entry->description];
        }
    }
    else {
        $time_entry->activity_name = $time_entry->title;
    }
    $time_entry->activity_name = '<span class="semibold">' . $title_timestamp . '</span> - ' .$time_entry->activity_name;
}


//var_dump($timelogs_to_show);

?>
<script type="text/javascript" id="existing-tasks-script">

function loadExistingTimelogs(){
    // Scoro Client Work activity IDs (for ease of change)
    var clientWorkActivityId = <?php echo SCORO_CLIENT_WORK_ACTIVITY_ID; ?>;
    var plannedActivityId = <?php echo SCORO_PLANNED_ACTIVITY_ID; ?>;

    var previousTimelogs = <?php echo json_encode($main_task->time_entries); ?>;

    var partnerRoles = <?php echo json_encode($roles); ?>;

    var mainTaskId = <?php echo $main_task->event_id; ?>;

    document.getElementById('page-title').innerHTML = document.getElementById('calendar-page-title').innerHTML;
    // process existing timelogs and push them into state
    if(previousTimelogs) {
        previousTimelogs.forEach(previousTimelog => {
            // get start and end dates
            const startDateTime = new Date(previousTimelog.start_datetime);
            const endDateTime = new Date(previousTimelog.end_datetime);
            // set up timelog object that we'll push to state
            const timelog = {
                id: previousTimelog.time_entry_id,
                date: previousTimelog.start_datetime.split('T')[0],
                // get dates into the right formats
                startTime: `${("0" + startDateTime.getHours()).slice(-2)}:${("0" + startDateTime.getMinutes()).slice(-2)}`,
                endTime: `${("0" + endDateTime.getHours()).slice(-2)}:${("0" + endDateTime.getMinutes()).slice(-2)}`,
                activity: previousTimelog.activity_id,
                activityTitle: previousTimelog.title,
                startDate: startDateTime,
                endDate: endDateTime,
                taskId: previousTimelog.event_id,
                editNonce: false,
                activityTitle: previousTimelog.activity_name,
                // duration in minutes
                duration: Math.round((endDateTime - startDateTime) / 60000)
            }
            // the backend activity IDs are slightly different from the app, so need to convert them
            if(previousTimelog.activity_id === plannedActivityId) {
                // if it's planned activity
                timelog.activity = previousTimelog.description;
                timelog.isPlanned = true;
                timelog.isClientWork = true;
            }
            else{
                // if it's unplanned activity
                timelog.isPlanned = false;
                if(previousTimelog.activity_id === <?php echo SCORO_CLIENT_WORK_ACTIVITY_ID; ?>){
                    // if it's unplanned client work
                    timelog.activity = previousTimelog.description;
                }
                else {
                    // if it's unplanned "Other" e.g. holidays
                    timelog.activity = previousTimelog.activity_id;
                    timelog.companyName = false;
                    timelog.isClientWork = false;
                }
            }
            // push to state
            if(!getTimelogById(timelog.id)) state.push(timelog);
        });
        displayTimelogs();
    }

    function getCustomFieldValue(object, customFieldName){
        const index = object.custom_fields.findIndex(customField => customField.id === customFieldName);
        return customFieldValue = object.custom_fields[index].value;
    }

}

if(typeof barba !== 'undefined'){
    loadExistingTimelogs();
}
else {
    window.addEventListener("load", function(event) { 
        loadExistingTimelogs();
    });
}


</script>
