import { Component, h, Prop, State } from '@stencil/core';
/**
 * This component is needed to prevent re-rendering of
 * main component with currentTime changes.
 */
export class PrestoEmailOverlay {
  componentWillLoad() {
    if (!this.player)
      return;
    this.player.on('ended', () => this.setEnded());
    this.player.on('timeupdate', e => this.setCurrentTime(e));
  }
  setEnded() {
    this.ended = true;
  }
  setCurrentTime(e) {
    var _a, _b;
    this.currentTime = (_a = e === null || e === void 0 ? void 0 : e.detail) === null || _a === void 0 ? void 0 : _a.plyr.currentTime;
    this.duration = (_b = e === null || e === void 0 ? void 0 : e.detail) === null || _b === void 0 ? void 0 : _b.plyr.duration;
  }
  /**
   * Remove listeners if destroyed
   */
  disconnectedCallback() {
    if (!this.player)
      return;
    this.player.off('ended', this.setEnded);
    this.player.off('timeupdate', this.setCurrentTime);
  }
  /**
   * Maybe render
   * @returns JSX
   */
  render() {
    var _a, _b;
    return (h("presto-email-overlay-controller", { ended: this.ended, currentTime: this.currentTime, videoId: this.videoId, duration: this.duration, direction: this.direction, presetId: (_a = this.preset) === null || _a === void 0 ? void 0 : _a.id, emailCollection: (_b = this.preset) === null || _b === void 0 ? void 0 : _b.email_collection, i18n: this.i18n, provider: this.provider }));
  }
  static get is() { return "presto-email-overlay"; }
  static get originalStyleUrls() { return {
    "$": ["presto-email-overlay.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-email-overlay.css"]
  }; }
  static get properties() { return {
    "player": {
      "type": "any",
      "mutable": false,
      "complexType": {
        "original": "any",
        "resolved": "any",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "player",
      "reflect": false
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
    "preset": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "presetAttributes",
        "resolved": "presetAttributes",
        "references": {
          "presetAttributes": {
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
    "currentTime": {},
    "duration": {},
    "ended": {}
  }; }
}
