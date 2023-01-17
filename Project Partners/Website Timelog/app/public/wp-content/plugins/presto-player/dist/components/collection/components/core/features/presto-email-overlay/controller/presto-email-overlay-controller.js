import { Component, Event, h, Prop, State, Watch } from '@stencil/core';
import { timePassed } from '../../../../../util';
export class PrestoEmailOverlayController {
  constructor() {
    this.percentagePassed = 0;
  }
  /**
   * Set email collection in local storage
   * @param status string
   */
  setStorage(status) {
    window.localStorage.setItem('presto.videos.email_collection', JSON.stringify({ [this.videoId]: status }));
  }
  /**
   * Get email collection in local storage
   * @returns status string
   */
  getStorage() {
    return window.localStorage.getItem('presto.videos.email_collection');
  }
  componentWillLoad() {
    this.handleDuration();
  }
  /**
   * Wait for duration to start before checking time
   * @returns void
   */
  handleDuration() {
    var _a;
    this.enabled = this.getStorage() ? false : (_a = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _a === void 0 ? void 0 : _a.enabled;
    this.handleTimeCheck();
  }
  /**
   * When current time changes, check to see if we should
   * enable the overlay
   * @returns void
   */
  handleTimeCheck() {
    if (!this.enabled)
      return; // not enabled
    if (this.getStorage())
      return; // already skipped
    this.checkTime();
  }
  handleShowChange() {
    if (!this.show)
      return; // bail if not showing.
    this.pauseVideo.emit(true); // pause video if here.
  }
  /**
   * Set enabled/disabled based on time that has passed
   */
  checkTime() {
    var _a;
    this.show = timePassed({
      current: this.currentTime,
      duration: this.duration,
      showAfter: ((_a = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _a === void 0 ? void 0 : _a.percentage) || 0,
    });
  }
  /**
   * Fetch updated nonce in case of caching
   * @returns Promise
   */
  async getNonce() {
    var _a;
    return fetch(`${(_a = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _a === void 0 ? void 0 : _a.ajaxurl}?action=presto_refresh_progress_nonce`);
  }
  /**
   * Submit email collection
   * @param e Event
   */
  async submit(e) {
    var _a;
    this.loading = true;
    this.error = '';
    // get nonce refresh
    const response = await this.getNonce();
    const { data: nonce } = await response.json();
    // handle submit
    try {
      let response = await fetch((_a = window === null || window === void 0 ? void 0 : window.prestoPlayer) === null || _a === void 0 ? void 0 : _a.ajaxurl, {
        method: 'post',
        body: new URLSearchParams(Object.assign({ action: 'presto_player_email_submit', nonce, preset_id: this.presetId, video_id: this.videoId }, ((e === null || e === void 0 ? void 0 : e.detail) || {}))),
      });
      const { success, data } = await response.json();
      if (success) {
        this.setStorage('collected');
        this.show = false;
        this.playVideo.emit();
      }
      else {
        throw data;
      }
    }
    catch (e) {
      const error = e === null || e === void 0 ? void 0 : e[0];
      if (error && typeof error === 'string') {
        this.error = error;
      }
    }
    finally {
      this.loading = false;
    }
  }
  /**
   * Skip email collection
   */
  skip() {
    this.setStorage('skipped');
    this.show = false;
    this.playVideo.emit();
  }
  /**
   * Maybe render
   * @returns JSX
   */
  handleEmailStateChange(val) {
    this.emailStateChange.emit(val);
  }
  render() {
    var _a, _b, _c, _d, _e, _f, _g, _h;
    if (!this.show) {
      return;
    }
    return (h("presto-email-overlay-ui", { style: Object.assign(Object.assign({}, (((_a = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _a === void 0 ? void 0 : _a.button_color)
        ? {
          '--presto-player-button-color': `${(_b = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _b === void 0 ? void 0 : _b.button_color}`,
        }
        : {})), (((_c = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _c === void 0 ? void 0 : _c.button_text_color)
        ? {
          '--presto-player-button-text': `${(_d = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _d === void 0 ? void 0 : _d.button_text_color}`,
        }
        : {})), direction: this.direction, class: "email-overlay", headline: (_e = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _e === void 0 ? void 0 : _e.headline, bottomText: (_f = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _f === void 0 ? void 0 : _f.bottom_text, allowSkip: (_g = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _g === void 0 ? void 0 : _g.allow_skip, buttonText: (_h = this === null || this === void 0 ? void 0 : this.emailCollection) === null || _h === void 0 ? void 0 : _h.button_text, isLoading: this.loading, errorMessage: this.error, onSubmitForm: e => this.submit(e), onSkip: () => this.skip(), i18n: this.i18n, provider: this.provider }));
  }
  static get is() { return "presto-email-overlay-controller"; }
  static get originalStyleUrls() { return {
    "$": ["presto-email-overlay-controller.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-email-overlay-controller.css"]
  }; }
  static get properties() { return {
    "ended": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "ended",
      "reflect": false
    },
    "currentTime": {
      "type": "number",
      "mutable": false,
      "complexType": {
        "original": "number",
        "resolved": "number",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "current-time",
      "reflect": true
    },
    "duration": {
      "type": "number",
      "mutable": false,
      "complexType": {
        "original": "number",
        "resolved": "number",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "duration",
      "reflect": true
    },
    "direction": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'rtl'",
        "resolved": "\"rtl\"",
        "references": {}
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "direction",
      "reflect": false
    },
    "emailCollection": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "EmailCollection",
        "resolved": "EmailCollection",
        "references": {
          "EmailCollection": {
            "location": "import",
            "path": "../../../../../interfaces"
          }
        }
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": ""
      }
    },
    "i18n": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "i18nConfig",
        "resolved": "i18nConfig",
        "references": {
          "i18nConfig": {
            "location": "import",
            "path": "../../../../../interfaces"
          }
        }
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      }
    },
    "videoId": {
      "type": "number",
      "mutable": false,
      "complexType": {
        "original": "number",
        "resolved": "number",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "video-id",
      "reflect": false
    },
    "presetId": {
      "type": "number",
      "mutable": false,
      "complexType": {
        "original": "number",
        "resolved": "number",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "preset-id",
      "reflect": false
    },
    "provider": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "string",
        "resolved": "string",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "provider",
      "reflect": false
    }
  }; }
  static get states() { return {
    "enabled": {},
    "show": {},
    "loading": {},
    "error": {},
    "percentagePassed": {}
  }; }
  static get events() { return [{
      "method": "playVideo",
      "name": "playVideo",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }, {
      "method": "pauseVideo",
      "name": "pauseVideo",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "complexType": {
        "original": "true",
        "resolved": "boolean",
        "references": {}
      }
    }, {
      "method": "restartVideo",
      "name": "restartVideo",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }, {
      "method": "emailStateChange",
      "name": "emailStateChange",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      }
    }]; }
  static get watchers() { return [{
      "propName": "duration",
      "methodName": "handleDuration"
    }, {
      "propName": "currentTime",
      "methodName": "handleTimeCheck"
    }, {
      "propName": "currentTime",
      "methodName": "handleShowChange"
    }, {
      "propName": "show",
      "methodName": "handleEmailStateChange"
    }]; }
}
