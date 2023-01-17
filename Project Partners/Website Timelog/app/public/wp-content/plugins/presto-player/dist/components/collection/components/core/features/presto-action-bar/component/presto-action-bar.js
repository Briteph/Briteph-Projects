import { Component, Element, h, Prop, State } from '@stencil/core';
export class PrestoActionBar {
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
  render() {
    return (h("presto-action-bar-controller", { ended: this.ended, config: this.config, currentTime: this.currentTime, duration: this.duration, direction: this.direction, youtube: this.youtube }));
  }
  static get is() { return "presto-action-bar"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-action-bar.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-action-bar.css"]
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
    "currentTime": {},
    "duration": {},
    "ended": {}
  }; }
  static get elementRef() { return "el"; }
}
