import { resumeProgress } from '../functions/save-time';

export default player => {
  if (typeof learndash_video_data === 'undefined' || learndash_video_data?.videos_found_provider !== 'presto') {
    return;
  }

  resumeProgress(player, true); // maybe resume progress

  // Learndash has this logic in a shitty method on their php class
  // we have to fake it here since we don't have access to it
  window.learndash_video_data.videos_auto_complete = prestoPlayer?.learndash?.lesson_video_auto_complete === 'on';
  window.learndash_video_data.videos_hide_complete_button = 'AFTER' === learndash_video_data?.videos_shown && prestoPlayer?.learndash?.lesson_video_hide_complete_button === 'on';
  window.learndash_video_data.videos_auto_complete_delay = prestoPlayer?.learndash?.lesson_video_auto_complete_delay;
  window.learndash_video_data.videos_auto_complete_delay_message = prestoPlayer?.learndash?.videos_auto_complete_delay_message;

  // Temporarily set the video track path to '/' to set the cookie globally
  window.learndash_video_data.video_track_path = '/';

  // Learndash has this logic in a shitty method on their php class
  // we have to fake it here since we don't have access to it
  if ('BEFORE' === learndash_video_data?.videos_shown) {
    learndash_video_data.videos_auto_complete = false;
    jQuery(document).trigger('learndash_video_disable_assets', [true]);

    // Set learndash video players
    jQuery(function () {
      if (learndash_video_data.video_debug === '1') {
        console.log('PRESTO: init');
      }

      if (document.querySelectorAll('presto-player[data-video-progression="true"][data-video-provider="' + learndash_video_data.videos_found_provider + '"]').length) {
        if (learndash_video_data.video_debug === '1') {
          console.log('PRESTO: calling LearnDash_disable_assets(true)');
        }

        // Disable learndash assets and watch players.
        LearnDash_disable_assets(true);
        LearnDash_watchPlayers();

        // Go through all the <presto-player> instances
        document
          .querySelectorAll('presto-player[data-video-progression="true"][data-video-provider="' + learndash_video_data.videos_found_provider + '"]')
          .forEach(function (element, index) {
            var element_key = 'presto-player__wrapper-player-' + index;
            var element_id = element.getAttribute('id');

            if (typeof element_id === 'undefined' || element_id == '') {
              element_id = element_key;
              element.setAttribute('id', element_id);
            }

            ld_video_players[element_key] = {};
            ld_video_players[element_key]['player_key'] = element_key;
            ld_video_players[element_key]['player_type'] = learndash_video_data.videos_found_provider;
            ld_video_players[element_key]['player_id'] = element_id;
            ld_video_players[element_key]['player_wrapper'] = element.closest('presto-player');
            if (typeof ld_video_players[element_key]['player_wrapper'] !== 'undefined') {
              ld_video_players[element_key]['player_cookie_key'] = ld_video_players[element_key]['player_wrapper'].getAttribute('data-video-cookie-key');
            } else {
              ld_video_players[element_key]['player_cookie_key'] = '';
            }
            ld_video_players[element_key]['player_cookie_values'] = LearnDash_Video_Progress_initSettings(ld_video_players[element_key]);

            // Check if video has been completed already.
            // If yes, enable the assets back and end watch players function.
            // If no, then apply all the filters and performs the actions again.
            if (LearnDash_Video_Progress_getSetting(ld_video_players[element_key], 'video_state') === 'complete') {
              LearnDash_disable_assets(false);
              LearnDash_watchPlayersEnd();
            } else {
              // Player Seeked.
              wp.hooks.addAction('presto.playerTimeUpdate', 'presto-player', player => {
                if (learndash_video_data.video_debug === '1') {
                  console.log('PRESTO: Video is playing');
                }
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_duration', player.duration);
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);

                if (player.duration && player.duration === player.currentTime) {
                  LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'complete');

                  if (learndash_video_data.video_debug === '1') {
                    console.log('PRESTO: calling LearnDash_disable_assets(false)');
                  }
                  LearnDash_disable_assets(false);

                  // Stop watching players.
                  LearnDash_watchPlayersEnd();
                }
              });

              // Player Playing.
              wp.hooks.addAction('presto.playerPlaying', 'presto-player', player => {
                if (learndash_video_data.video_debug === '1') {
                  console.log('PRESTO: Video is playing');
                }
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_duration', player.duration);
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'play');
              });

              // Player Paused.
              wp.hooks.addAction('presto.playerPause', 'presto-player', player => {
                if (learndash_video_data.video_debug === '1') {
                  console.log('PRESTO: Video is paused');
                }
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);
                console.log(player.duration);
                console.log(player.currentTime);
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'pause');
              });

              // Player Ended.
              wp.hooks.addAction('presto.playerEnded', 'presto-player', player => {
                if (learndash_video_data.video_debug === '1') {
                  console.log('PRESTO: video ended');
                }

                if (learndash_video_data.video_debug === '1') {
                  console.log("PRESTO: setting 'video_state' to 'complete'");
                }
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);
                LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'complete');

                if (learndash_video_data.video_debug === '1') {
                  console.log('PRESTO: calling LearnDash_disable_assets(false)');
                }
                LearnDash_disable_assets(false);

                // Stop watching players.
                LearnDash_watchPlayersEnd();
              });
            }
          });
      }
    });

    return;
  } else {
    window.LearnDash_disable_assets(true);
    wp.hooks.addAction('presto.playerEnded', 'presto-player', () => {
      window.LearnDash_disable_assets(false);
    });
  }
};
