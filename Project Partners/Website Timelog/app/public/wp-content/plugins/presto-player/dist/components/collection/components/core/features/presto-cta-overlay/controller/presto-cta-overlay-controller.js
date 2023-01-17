import { Component, Event, h, Prop, State, Watch } from '@stencil/core';
import { timePassed } from '../../../../../util';
export class PrestoCtaOverlayController {
  constructor() {
    this.percentagePassed = 0;
  }
  componentWillLoad() {
    this.handleDuration();
  }
  /**
   * Handle with the player is ended
   * @param val
   * @returns
   */
  handleEnded(val) {
    var _a;
    if (val) {
      if (((_a = this === null || this === void 0 ? void 0 : this.cta) === null || _a === void 0 ? void 0 : _a.percentage) !== 100)
        return;
      this.show = true;
    }
  }
  /**
   * Wait for duration to start before checking time
   * @returns void
   */
  handleDuration() {
    var _a;
    this.enabled = this.skipped ? false : (_a = this === null || this === void 0 ? void 0 : this.cta) === null || _a === void 0 ? void 0 : _a.enabled;
    this.handleTime();
  }
  handlePercentagePassed() {
    this.percentagePassed = (this.currentTime / this.duration) * 100;
  }
  /**
   * Watch current time and check if we should
   * pause the video.
   */
  handleEnabled() {
    if (!this.show)
      return; // not showing.
    if (this.skipped)
      return; // already skipped.
    this.pauseVideo.emit(true); // pause if enabled.
  }
  /**
   * When current time changes, check to see if we should
   * enable the overlay
   * @returns void
   */
  handleTime() {
    var _a;
    if (!this.enabled)
      return; // not enabled.
    if (this.skipped)
      return; // skipped.
    if (((_a = this === null || this === void 0 ? void 0 : this.cta) === null || _a === void 0 ? void 0 : _a.percentage) === 100)
      return; // we'll catch this on ended event.
    this.checkTime();
  }
  /**
   * Set enabled/disabled based on time that has passed
   */
  checkTime() {
    var _a;
    this.show = timePassed({
      current: this.currentTime,
      duration: this.duration,
      showAfter: ((_a = this === null || this === void 0 ? void 0 : this.cta) === null || _a === void 0 ? void 0 : _a.percentage) || 0,
    });
  }
  /**
   * Skip email collection
   */
  skip() {
    this.skipped = true;
    this.show = false;
    this.playVideo.emit();
  }
  /**
   * Handle rewatch click.
   */
  rewatch() {
    this.ended = false;
    this.show = false;
    this.restartVideo.emit();
  }
  /**
   * Maybe render
   * @returns JSX
   */
  handleCtaStateChange(val) {
    this.ctaStateChange.emit(val);
  }
  render() {
    var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k, _l, _m, _o, _p;
    if (!this.show) {
      return;
    }
    return (h("presto-cta-overlay-ui", { style: Object.assign(Object.assign(Object.assign({ '--presto-player-button-border-radius': `${(_a = this === null || this === void 0 ? void 0 : this.cta) === null || _a === void 0 ? void 0 : _a.button_radius}px` }, (((_b = this === null || this === void 0 ? void 0 : this.cta) === null || _b === void 0 ? void 0 : _b.background_opacity) ? { '--presto-player-cta-background-opacity': `${((_c = this === null || this === void 0 ? void 0 : this.cta) === null || _c === void 0 ? void 0 : _c.background_opacity) / 100}` } : {})), (((_d = this === null || this === void 0 ? void 0 : this.cta) === null || _d === void 0 ? void 0 : _d.button_color)
        ? {
          '--presto-player-button-color': `${(_e = this === null || this === void 0 ? void 0 : this.cta) === null || _e === void 0 ? void 0 : _e.button_color}`,
        }
        : {})), (((_f = this === null || this === void 0 ? void 0 : this.cta) === null || _f === void 0 ? void 0 : _f.button_text_color)
        ? {
          '--presto-player-button-text': `${(_g = this === null || this === void 0 ? void 0 : this.cta) === null || _g === void 0 ? void 0 : _g.button_text_color}`,
        }
        : {})), direction: this.direction, class: "cta-overlay", i18n: this.i18n, headline: (_h = this === null || this === void 0 ? void 0 : this.cta) === null || _h === void 0 ? void 0 : _h.headline, "bottom-text": (_j = this === null || this === void 0 ? void 0 : this.cta) === null || _j === void 0 ? void 0 : _j.bottom_text, "show-button": (_k = this === null || this === void 0 ? void 0 : this.cta) === null || _k === void 0 ? void 0 : _k.show_button, buttonLink: (_l = this === null || this === void 0 ? void 0 : this.cta) === null || _l === void 0 ? void 0 : _l.button_link, allowSkip: !this.ended && ((_m = this === null || this === void 0 ? void 0 : this.cta) === null || _m === void 0 ? void 0 : _m.show_skip), allowRewatch: this.ended && ((_o = this === null || this === void 0 ? void 0 : this.cta) === null || _o === void 0 ? void 0 : _o.show_rewatch), "button-text": (_p = this === null || this === void 0 ? void 0 : this.cta) === null || _p === void 0 ? void 0 : _p.button_text, onSkip: () => this.skip(), onRewatch: () => this.rewatch(), provider: this.provider }));
  }
  static get is() { return "presto-cta-overlay-controller"; }
  static get originalStyleUrls() { return {
    "$": ["presto-cta-overlay-controller.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-cta-overlay-controller.css"]
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
    "cta": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "CTA",
        "resolved": "CTA",
        "references": {
          "CTA": {
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
    "skipped": {},
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
        "original": "boolean",
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
      "method": "ctaStateChange",
      "name": "ctaStateChange",
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
      "propName": "ended",
      "methodName": "handleEnded"
    }, {
      "propName": "duration",
      "methodName": "handleDuration"
    }, {
      "propName": "currentTime",
      "methodName": "handlePercentagePassed"
    }, {
      "propName": "duration",
      "methodName": "handlePercentagePassed"
    }, {
      "propName": "currentTime",
      "methodName": "handleEnabled"
    }, {
      "propName": "currentTime",
      "methodName": "handleTime"
    }, {
      "propName": "show",
      "methodName": "handleCtaStateChange"
    }]; }
}
