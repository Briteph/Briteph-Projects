'use strict';

const prestoActionBar_prestoActionBarController_prestoActionBarUi_prestoAudio_prestoBunny_prestoBusinessSkin_prestoCtaOverlay_prestoCtaOverlayController_prestoCtaOverlayUi_prestoDynamicOverlayUi_prestoDynamicOverlays_prestoEmailOverlay_prestoEmailOverlayController_prestoEmailOverlayUi_prestoModernSkin_prestoMutedOverlay_prestoPlayer_prestoPlayerButton_prestoPlayerSpinner_prestoStackedSkin_prestoTimestamp_prestoVideo_prestoVimeo_prestoYoutube_prestoYoutubeSubscribeButton_entry = require('./presto-action-bar.presto-action-bar-controller.presto-action-bar-ui.presto-audio.presto-bunny.presto-business-skin.presto-cta-overlay.presto-cta-overlay-controller.presto-cta-overlay-ui.presto-dynamic-overlay-ui.presto-dynamic-overlays.presto-email-overlay.presto-email-overlay-controller.presto-email-overlay-ui.presto-modern-skin.presto-muted-overlay.presto-player.presto-player-button.presto-player-spinner.presto-stacked-skin.presto-timestamp.presto-video.presto-vimeo.presto-youtube.presto-youtube-subscribe-button-d509eec6.js');
require('./index-c0b7f1dc.js');

const learndash = (function (player) {
  var _learndash_video_data, _prestoPlayer, _prestoPlayer$learnda, _learndash_video_data2, _prestoPlayer2, _prestoPlayer2$learnd, _prestoPlayer3, _prestoPlayer3$learnd, _prestoPlayer4, _prestoPlayer4$learnd, _learndash_video_data3;

  if (typeof learndash_video_data === 'undefined' || ((_learndash_video_data = learndash_video_data) === null || _learndash_video_data === void 0 ? void 0 : _learndash_video_data.videos_found_provider) !== 'presto') {
    return;
  }

  prestoActionBar_prestoActionBarController_prestoActionBarUi_prestoAudio_prestoBunny_prestoBusinessSkin_prestoCtaOverlay_prestoCtaOverlayController_prestoCtaOverlayUi_prestoDynamicOverlayUi_prestoDynamicOverlays_prestoEmailOverlay_prestoEmailOverlayController_prestoEmailOverlayUi_prestoModernSkin_prestoMutedOverlay_prestoPlayer_prestoPlayerButton_prestoPlayerSpinner_prestoStackedSkin_prestoTimestamp_prestoVideo_prestoVimeo_prestoYoutube_prestoYoutubeSubscribeButton_entry.resumeProgress(player); // maybe resume progress
  // Learndash has this logic in a shitty method on their php class
  // we have to fake it here since we don't have access to it

  window.learndash_video_data.videos_auto_complete = ((_prestoPlayer = prestoPlayer) === null || _prestoPlayer === void 0 ? void 0 : (_prestoPlayer$learnda = _prestoPlayer.learndash) === null || _prestoPlayer$learnda === void 0 ? void 0 : _prestoPlayer$learnda.lesson_video_auto_complete) === 'on';
  window.learndash_video_data.videos_hide_complete_button = 'AFTER' === ((_learndash_video_data2 = learndash_video_data) === null || _learndash_video_data2 === void 0 ? void 0 : _learndash_video_data2.videos_shown) && ((_prestoPlayer2 = prestoPlayer) === null || _prestoPlayer2 === void 0 ? void 0 : (_prestoPlayer2$learnd = _prestoPlayer2.learndash) === null || _prestoPlayer2$learnd === void 0 ? void 0 : _prestoPlayer2$learnd.lesson_video_hide_complete_button) === 'on';
  window.learndash_video_data.videos_auto_complete_delay = (_prestoPlayer3 = prestoPlayer) === null || _prestoPlayer3 === void 0 ? void 0 : (_prestoPlayer3$learnd = _prestoPlayer3.learndash) === null || _prestoPlayer3$learnd === void 0 ? void 0 : _prestoPlayer3$learnd.lesson_video_auto_complete_delay;
  window.learndash_video_data.videos_auto_complete_delay_message = (_prestoPlayer4 = prestoPlayer) === null || _prestoPlayer4 === void 0 ? void 0 : (_prestoPlayer4$learnd = _prestoPlayer4.learndash) === null || _prestoPlayer4$learnd === void 0 ? void 0 : _prestoPlayer4$learnd.videos_auto_complete_delay_message; // Temporarily set the video track path to '/' to set the cookie globally

  window.learndash_video_data.video_track_path = '/'; // Learndash has this logic in a shitty method on their php class
  // we have to fake it here since we don't have access to it

  if ('BEFORE' === ((_learndash_video_data3 = learndash_video_data) === null || _learndash_video_data3 === void 0 ? void 0 : _learndash_video_data3.videos_shown)) {
    learndash_video_data.videos_auto_complete = false;
    jQuery(document).trigger('learndash_video_disable_assets', [true]); // Set learndash video players

    jQuery(function () {
      if (learndash_video_data.video_debug === '1') {
        console.log('PRESTO: init');
      }

      if (document.querySelectorAll('presto-player[data-video-progression="true"][data-video-provider="' + learndash_video_data.videos_found_provider + '"]').length) {
        if (learndash_video_data.video_debug === '1') {
          console.log('PRESTO: calling LearnDash_disable_assets(true)');
        } // Disable learndash assets and watch players.


        LearnDash_disable_assets(true);
        LearnDash_watchPlayers(); // Go through all the <presto-player> instances

        document.querySelectorAll('presto-player[data-video-progression="true"][data-video-provider="' + learndash_video_data.videos_found_provider + '"]').forEach(function (element, index) {
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

          ld_video_players[element_key]['player_cookie_values'] = LearnDash_Video_Progress_initSettings(ld_video_players[element_key]); // Check if video has been completed already.
          // If yes, enable the assets back and end watch players function.
          // If no, then apply all the filters and performs the actions again.

          if (LearnDash_Video_Progress_getSetting(ld_video_players[element_key], 'video_state') === 'complete') {
            LearnDash_disable_assets(false);
            LearnDash_watchPlayersEnd();
          } else {
            // Player Seeked.
            wp.hooks.addAction('presto.playerTimeUpdate', 'presto-player', function (player) {
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

                LearnDash_disable_assets(false); // Stop watching players.

                LearnDash_watchPlayersEnd();
              }
            }); // Player Playing.

            wp.hooks.addAction('presto.playerPlaying', 'presto-player', function (player) {
              if (learndash_video_data.video_debug === '1') {
                console.log('PRESTO: Video is playing');
              }

              LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_duration', player.duration);
              LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);
              LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'play');
            }); // Player Paused.

            wp.hooks.addAction('presto.playerPause', 'presto-player', function (player) {
              if (learndash_video_data.video_debug === '1') {
                console.log('PRESTO: Video is paused');
              }

              LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_time', player.currentTime);
              console.log(player.duration);
              console.log(player.currentTime);
              LearnDash_Video_Progress_setSetting(ld_video_players[element_key], 'video_state', 'pause');
            }); // Player Ended.

            wp.hooks.addAction('presto.playerEnded', 'presto-player', function (player) {
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

              LearnDash_disable_assets(false); // Stop watching players.

              LearnDash_watchPlayersEnd();
            });
          }
        });
      }
    });
    return;
  } else {
    window.LearnDash_disable_assets(true);
    wp.hooks.addAction('presto.playerEnded', 'presto-player', function () {
      window.LearnDash_disable_assets(false);
    });
  }
});

exports.default = learndash;
