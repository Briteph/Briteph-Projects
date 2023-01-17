import { Component, Element, Event, h, Prop, State, Watch } from '@stencil/core';
import { timePassed } from '../../../../../util';
export class PrestoActionBar {
  constructor() {
    this.show = false;
    this.youtubeRenderKey = 1;
    this.percentagePassed = 0;
  }
  componentWillLoad() {
    this.handleDuration();
  }
  /**
   * Wait for duration to start before checking time
   * @returns void
   */
  handleDuration() {
    this.handleTime();
  }
  /**
   * Handle with the player is ended
   * @param val
   * @returns
   */
  handleEnded(val) {
    var _a;
    if (val) {
      if (((_a = this === null || this === void 0 ? void 0 : this.config) === null || _a === void 0 ? void 0 : _a.percentage_start) !== 100)
        return;
      this.show = true;
    }
  }
  handlePercentagePassed() {
    this.percentagePassed = (this.currentTime / this.duration) * 100;
  }
  /**
   * When current time changes, check to see if we should
   * enable the overlay
   * @returns void
   */
  handleTime() {
    this.checkTime();
  }
  /**
   * Set enabled/disabled based on time that has passed
   */
  checkTime() {
    var _a, _b;
    if ((_a = window === null || window === void 0 ? void 0 : window.wp) === null || _a === void 0 ? void 0 : _a.blocks) {
      this.show = true;
      return;
    }
    // if ( this.config?.percentage_start === 0) {
    //   this.show = true;
    // } else {
    // console.log(this.currentTime, this.duration, this.config?.percentage_start);
    this.show = timePassed({
      current: this.currentTime,
      duration: this.duration,
      showAfter: (_b = this.config) === null || _b === void 0 ? void 0 : _b.percentage_start,
    });
  }
  handleButtonCountChange(newVal, oldVal) {
    if ((newVal === null || newVal === void 0 ? void 0 : newVal.button_count) !== (oldVal === null || oldVal === void 0 ? void 0 : oldVal.button_count)) {
      this.youtubeRenderKey++;
    }
    if (newVal === null || newVal === void 0 ? void 0 : newVal.enabled) {
      this.handleDuration();
    }
  }
  youtubeButton() {
    var _a, _b, _c, _d;
    if (((_a = this.config) === null || _a === void 0 ? void 0 : _a.button_type) !== 'youtube' || !((_b = this.youtube) === null || _b === void 0 ? void 0 : _b.channelId)) {
      return;
    }
    return h("presto-youtube-subscribe-button", { key: this.youtubeRenderKey, channel: (_c = this.youtube) === null || _c === void 0 ? void 0 : _c.channelId, showCount: (_d = this.config) === null || _d === void 0 ? void 0 : _d.button_count });
  }
  customButton() {
    var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k, _l;
    if (((_a = this.config) === null || _a === void 0 ? void 0 : _a.button_type) !== 'custom') {
      return;
    }
    return (h("presto-player-button", { type: "primary", size: "small", href: (_c = (_b = this.config) === null || _b === void 0 ? void 0 : _b.button_link) === null || _c === void 0 ? void 0 : _c.url, target: ((_e = (_d = this.config) === null || _d === void 0 ? void 0 : _d.button_link) === null || _e === void 0 ? void 0 : _e.opensInNewTab) ? '_blank' : '_self', style: Object.assign(Object.assign({ '--presto-player-button-border-radius': `${(_f = this.config) === null || _f === void 0 ? void 0 : _f.button_radius}px` }, (((_g = this.config) === null || _g === void 0 ? void 0 : _g.button_color)
        ? {
          '--presto-player-button-color': `${(_h = this.config) === null || _h === void 0 ? void 0 : _h.button_color}`,
        }
        : {})), (((_j = this.config) === null || _j === void 0 ? void 0 : _j.button_text_color)
        ? {
          '--presto-player-button-text': `${(_k = this.config) === null || _k === void 0 ? void 0 : _k.button_text_color}`,
        }
        : {})) }, (_l = this.config) === null || _l === void 0 ? void 0 : _l.button_text));
  }
  handleCtaStateChange(val) {
    this.actionBarStateChange.emit(val);
  }
  render() {
    var _a, _b;
    return (h("presto-action-bar-ui", { open: this.show, style: {
        '--presto-action-bar-background': ((_a = this.config) === null || _a === void 0 ? void 0 : _a.background_color) || '#1d1d1d',
      } }, (_b = this.config) === null || _b === void 0 ? void 0 :
      _b.text,
      h("div", { slot: "button" },
        this.youtubeButton(),
        this.customButton())));
  }
  static get is() { return "presto-action-bar-controller"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-action-bar-controller.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-action-bar-controller.css"]
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
    "config": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "ActionBarConfig",
        "resolved": "ActionBarConfig",
        "references": {
          "ActionBarConfig": {
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
    "youtube": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "YoutubeConfig",
        "resolved": "YoutubeConfig",
        "references": {
          "YoutubeConfig": {
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
    }
  }; }
  static get states() { return {
    "show": {},
    "youtubeRenderKey": {},
    "percentagePassed": {}
  }; }
  static get events() { return [{
      "method": "actionBarStateChange",
      "name": "actionBarStateChange",
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
  static get elementRef() { return "el"; }
  static get watchers() { return [{
      "propName": "duration",
      "methodName": "handleDuration"
    }, {
      "propName": "ended",
      "methodName": "handleEnded"
    }, {
      "propName": "currentTime",
      "methodName": "handlePercentagePassed"
    }, {
      "propName": "duration",
      "methodName": "handlePercentagePassed"
    }, {
      "propName": "currentTime",
      "methodName": "handleTime"
    }, {
      "propName": "config",
      "methodName": "handleButtonCountChange"
    }, {
      "propName": "show",
      "methodName": "handleCtaStateChange"
    }]; }
}
