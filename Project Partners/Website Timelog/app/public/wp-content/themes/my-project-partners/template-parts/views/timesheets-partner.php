<?php

/**

 * Template part for the Partner Timesheet list section of the Project Partners dashboard.

 *

 * @link https://codex.wordpress.org/Template_Hierarchy

 *

 */


?>

<div id="timesheets-container">

    <?php
    // get the Scoro contact ID of the currently logged in WP user
    $partner_id =  creator_get_current_user_partner_id()->Partner_ID;
    //echo $partner_id;
    $monday = new DateTime('monday this week');
    // get last 6 weeks' tasks start and end time
    $start_date = clone $monday;
    $start_date->modify('-6 weeks');
    $end_date = clone $monday;

    //print_r($start_date);
    //generate_refresh_token($token_url);
    //creator_get_current_user_partner_id();
    //creator_get_current_user_partner_id();

    // get last 6 weeks' weekly timesheets
    //$weekly_timesheets = scoro_get_partner_weekly_timesheets_by_date($start_date->format('Y-m-d'), $end_date->format('Y-m-d'), $partner_id);
    $new_weekly_timesheets = creator_get_partner_weekly_timesheets_by_date($start_date->format('d-M-Y'), $end_date->format('d-M-Y'), $partner_id);

    // $new_json=json_encode($new_weekly_timesheets,JSON_FORCE_OBJECT);
    //  echo $new_json;
    // sort them 
    // usort($weekly_timesheets, function ($a, $b) {
    //     return strcmp($b->start_datetime, $a->start_datetime);
    // });

    usort($new_weekly_timesheets, function ($a, $b) {
        return strcmp($b->Start_Date_Time, $a->Start_Date_Time);
    });

    //var_dump($weekly_timesheets);

    ?>

    <h2 class="h3">This week's timesheet</h2>
    <div class="tip large">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path fill-rule="evenodd" clip-rule="evenodd" d="M18 10C18 14.4183 14.4183 18 10 18C5.58172 18 2 14.4183 2 10C2 5.58172 5.58172 2 10 2C14.4183 2 18 5.58172 18 10ZM11 6C11 6.55228 10.5523 7 10 7C9.44772 7 9 6.55228 9 6C9 5.44772 9.44772 5 10 5C10.5523 5 11 5.44772 11 6ZM9 9C8.44772 9 8 9.44772 8 10C8 10.5523 8.44772 11 9 11V14C9 14.5523 9.44772 15 10 15H11C11.5523 15 12 14.5523 12 14C12 13.4477 11.5523 13 11 13V10C11 9.44772 10.5523 9 10 9H9Z" fill="#6E55FF" />
        </svg>
        <div class="tip-text">
            <h6>Why log your time?</h6>
            <p class="mt-half">Timesheeting helps to create a healthy & happy work environment by monitoring your actual workload, preventing you from being over- or underworked. In addition, the ratings and feedback you provide will help us prioritise work that you enjoy.</p>

            <?php
            if (count($new_weekly_timesheets) > 0 && date_create($new_weekly_timesheets[0]->Start_Date_Time)->format('Y-m-d') == $monday->format('Y-m-d')) {

                if ($new_weekly_timesheets[0]->Status === 'additional5') { ?>
                    <div class="flex icon green done-notice badge">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#34D399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                        <span class="green">Done for this week</span>
                    </div>
                <?php } else {
                ?>
                    <a href="/time-machine/weekly-timesheet?start=<?php echo $monday->format('Y-m-d'); ?>" class="button log-time-cta">Edit current timesheet</a>

                    <?php
                    $new_weekly_timesheets[0]->start_date = new DateTime($new_weekly_timesheets[0]->Start_Date_Time);
                    if ($new_weekly_timesheets[0]->start_date->diff($monday)->days < 2) {
                        $current_weekly_timesheet = array_shift($new_weekly_timesheets);

                        if ($current_weekly_timesheet->Status == 'task_status3') { ?>
                            <a href="/time-machine/weekly-timesheet?start=<?php echo $monday->format('Y-m-d'); ?>" class="danger tip inline-flex">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M12 8V12M12 16H12.01M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#DC2626" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <span>Needs attention</span>
                            </a>
                <?php
                        }
                    }
                }
            } else { ?>
                <a href="/time-machine/weekly-timesheet?start=<?php echo $monday->format('Y-m-d'); ?>" class="button log-time-cta">Log time for this week</a>
            <?php
            }

            ?>
        </div>
    </div>
    <?php
    $next_week_start = clone $monday;
    $next_week_start->modify('+1 week');
    ?>
    <h2 class="h6 mt-2 mb-0">Plan ahead</h2>
    <div class="flex inline dropdown-button-container mt-1">
        <a href="/time-machine/weekly-timesheet?start=<?php echo $next_week_start->format('Y-m-d'); ?>" class="secondary-cta white button flex inline">Next week</a>
        <a href="/time-machine/weekly-timesheet?start=<?php echo $next_week_start->modify('+1 week')->format('Y-m-d'); ?>" class="secondary-cta white button flex inline">After next week</a>
    </div>

    <h2 class="h3 mt-4 mb-2">Previous timesheets</h2>

    <div class="grid mb-2">

        <?php
        // prepare weekly timesheet dates and statuses
        if (count($new_weekly_timesheets) > 0) {
            foreach ($new_weekly_timesheets as $weekly_timesheet) {

                $weekly_timesheet->start_date = new DateTime($weekly_timesheet->Start_Date_Time);
                $weekly_timesheet->end_date = clone $weekly_timesheet->start_date;
                $weekly_timesheet->end_date->modify('+6 days');
                $weekly_timesheet->start_day = $weekly_timesheet->start_date->format('Y-m-d');

                // get difference in weeks to print out "X weeks ago"
                $difference_in_weeks = round($weekly_timesheet->start_date->diff($monday)->days / 7);

                switch ($weekly_timesheet->Status) {
                    case "task_status1":
                        $weekly_timesheet->status_classname = 'danger';
                        $weekly_timesheet->status_nicename = 'Overdue';
                        break;
                    case "task_status2":
                        $weekly_timesheet->status_classname = 'amber';
                        $weekly_timesheet->status_nicename = 'Pending';
                        break;
                    case "additional5":
                        $weekly_timesheet->status_classname = 'green';
                        $weekly_timesheet->status_nicename = 'Approved';
                        break;
                    default:
                        $weekly_timesheet->status_classname = 'danger';
                        $weekly_timesheet->status_nicename = 'Needs attention';
                }
            }
        }

        //get dates of weekly timesheets
        $weekly_timesheet_dates = array_column($new_weekly_timesheets, 'start_day');

        // iterate through last 6 weeks (including current one)
        for ($i = 0; $i <= 6; $i++) {
            $new_week_start = clone $monday;
            $new_week_start->modify('-' . $i . ' week');
            $timesheet_index = array_search($new_week_start->format('Y-m-d'), $weekly_timesheet_dates);
            // check if there's a timesheet created already for current week of the loop
            if ($timesheet_index !== false) {

                if ($new_weekly_timesheets[$timesheet_index]->status_nicename === 'Approved') { ?>
                    <a class="card no-pad timesheet-card" href="/approve-timesheets/view-timesheet/?task=<?php echo $new_weekly_timesheets[$timesheet_index]->Event_ID; ?>">
                        <div class="badge dot <?php echo esc_attr($new_weekly_timesheets[$timesheet_index]->status_classname); ?> float"><?php echo esc_html($new_weekly_timesheets[$timesheet_index]->status_nicename); ?></div>
                        <div class="card-padding">
                            <h4 class="normal-size grey mt-0 mb-0 semibold"><?php echo $new_weekly_timesheets[$timesheet_index]->start_date->format('d.m.Y') . '-' . $new_weekly_timesheets[$timesheet_index]->end_date->format('d.m.Y'); ?></h4>
                            <h3 class="h4 mt-half mb-0"><?php
                                                        if ($i < 1) {
                                                            echo 'This week';
                                                        } else if ($i == 1) {
                                                            echo 'Last week';
                                                        } else {
                                                            echo $i . ' weeks ago';
                                                        } ?></h3>
                        </div>
                        <div class="card-footer flex icon green">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M9 12L11 14L15 10M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z" stroke="#34D399" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            <span>Approved</span>
                        </div>
                    </a>
                <?php
                } else { ?>

                    <a class="card no-pad timesheet-card" href="/time-machine/weekly-timesheet?start=<?php echo $new_weekly_timesheets[$timesheet_index]->start_day; ?>">
                        <div class="badge dot <?php echo esc_attr($new_weekly_timesheets[$timesheet_index]->status_classname); ?> float"><?php echo esc_html($new_weekly_timesheets[$timesheet_index]->status_nicename); ?></div>
                        <div class="card-padding">
                            <h4 class="normal-size grey mt-0 mb-0 semibold"><?php echo $new_weekly_timesheets[$timesheet_index]->start_date->format('d.m.Y') . '-' . $new_weekly_timesheets[$timesheet_index]->end_date->format('d.m.Y'); ?></h4>
                            <h3 class="h4 mt-half mb-0"><?php if ($i == 1) {
                                                            echo 'Last week';
                                                        } else {
                                                            echo $i . ' weeks ago';
                                                        } ?></h3>
                        </div>
                        <div class="card-footer">Review</div>
                    </a>
                <?php
                }
            } else if ($i > 0 && $new_week_start > new DateTime('2021-12-12')) { ?>
                <a class="card no-pad timesheet-card" href="/time-machine/weekly-timesheet?start=<?php echo $new_week_start->format('Y-m-d'); ?>">
                    <div class="badge dot danger float">Overdue</div>
                    <div class="card-padding">
                        <h4 class="normal-size grey mt-0 mb-0 semibold"><?php echo $new_week_start->format('d.m.Y') . '-' . $new_week_start->modify('+6 days')->format('d.m.Y'); ?></h4>
                        <h3 class="h4 mt-half mb-0"><?php if ($i == 1) {
                                                        echo 'Last week';
                                                    } else {
                                                        echo $i . ' weeks ago';
                                                    } ?></h3>
                    </div>
                    <div class="card-footer">Review</div>
                </a>
        <?php

            }
        }


        ?>

    </div>

</div>