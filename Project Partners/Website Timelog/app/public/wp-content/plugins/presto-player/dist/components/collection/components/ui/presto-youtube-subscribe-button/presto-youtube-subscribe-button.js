import { Component, Prop, h } from '@stencil/core';
export class PrestoYoutubeSubscribeButton {
  constructor() {
    this.layout = 'default';
  }
  waitForApi(callback) {
    var interval = setInterval(function () {
      var _a;
      if ((_a = window === null || window === void 0 ? void 0 : window.gapi) === null || _a === void 0 ? void 0 : _a.ytsubscribe) {
        clearInterval(interval);
        callback();
      }
    }, 50);
  }
  componentDidLoad() {
    const po = document.createElement('script');
    po.type = 'text/javascript';
    po.async = false;
    po.src = 'https://apis.google.com/js/platform.js';
    const s = document.getElementsByTagName('script')[0];
    s && s.parentNode.insertBefore(po, s);
    this.waitForApi(() => {
      window.gapi.ytsubscribe.render(this.textInput, {
        channelId: this.channel,
        layout: this.layout,
        count: this.showCount ? 'default' : 'hidden',
      });
    });
  }
  render() {
    return h("div", { class: "g-ytsubscribe", ref: el => (this.textInput = el) });
  }
  static get is() { return "presto-youtube-subscribe-button"; }
  static get originalStyleUrls() { return {
    "$": ["presto-youtube-subscribe-button.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-youtube-subscribe-button.css"]
  }; }
  static get properties() { return {
    "channel": {
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
      "attribute": "channel",
      "reflect": false
    },
    "layout": {
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
      "attribute": "layout",
      "reflect": false,
      "defaultValue": "'default'"
    },
    "showCount": {
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
      "attribute": "show-count",
      "reflect": false
    }
  }; }
}
