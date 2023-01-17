import { Component, Prop, h, Event, Watch, State } from '@stencil/core';
import { isIOS } from '../../../../util';
export class PrestoYoutube {
  /**
   * When player is set, do ratio and fixes
   * @returns
   */
  handlePlayerChange() {
    if (!this.player) {
      return;
    }
    this.fixes();
  }
  // fixes issue where youtube can sometimes can be muted if played before load
  fixes() {
    this.player.once('statechange', e => {
      var _a, _b, _c, _d, _e;
      // only playing
      if (e.detail.code !== 1) {
        return;
      }
      // not autoplay
      if ((_e = (_d = (_c = (_b = (_a = e === null || e === void 0 ? void 0 : e.detail) === null || _a === void 0 ? void 0 : _a.plyr) === null || _b === void 0 ? void 0 : _b.config) === null || _c === void 0 ? void 0 : _c.blockAttributes) === null || _d === void 0 ? void 0 : _d.mutedPreview) === null || _e === void 0 ? void 0 : _e.enabled) {
        return;
      }
      // unmute
      this.player.muted = false;
    });
  }
  // get id from youtube url
  getId(url) {
    var _a;
    const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
    const match = (url || '').match(regExp);
    return match && ((_a = match === null || match === void 0 ? void 0 : match[2]) === null || _a === void 0 ? void 0 : _a.length) === 11 ? match[2] : null;
  }
  // load player
  loadPlayer() {
    this.lazyLoad = false;
    this.reloadPlayer = true;
  }
  // wait for component to update before reloading
  componentDidRender() {
    if (this.reloadPlayer) {
      this.reloadPlayer = false;
      this.reload.emit('play');
    }
  }
  /**
   * detect if we're in a webview browser
   */
  setWebView() {
    var _a;
    // @ts-ignore
    let standalone = (_a = window.navigator) === null || _a === void 0 ? void 0 : _a.standalone, userAgent = window.navigator.userAgent.toLowerCase(), safari = /safari/.test(userAgent), ios = /iphone|ipod|ipad/.test(userAgent);
    if (ios) {
      // ios webview
      this.isWebView = !standalone && !safari;
    }
    else {
      // android webview
      this.isWebView = userAgent.includes('wv');
    }
  }
  // don't lazy load on iOS
  componentWillLoad() {
    this.setWebView();
    // pull default Youtube poster if nothing set.
    if (!this.poster) {
      this.poster = `//img.youtube.com/vi/${this.getId(this.src)}/maxresdefault.jpg`;
    }
    if (this.lazyLoad && isIOS()) {
      this.lazyLoad = false;
    }
  }
  render() {
    if (this.isWebView) {
      return (h("div", { class: "fallback-container" },
        h("iframe", { src: this.src, allowFullScreen: true, allowtransparency: true, allow: "autoplay" })));
    }
    if (this.lazyLoad) {
      return (h("div", null,
        h("presto-video", { part: "video", getRef: this.getRef, poster: this.poster, src: "", provider: "youtube" }),
        h("div", { class: "presto-player__play-cover", onClick: () => this.loadPlayer() })));
    }
    return h("div", { class: "plyr__video-embed", part: "embed", ref: this.getRef, "data-plyr-provider": "youtube", "data-plyr-embed-id": this.getId(this.src) });
  }
  static get is() { return "presto-youtube"; }
  static get originalStyleUrls() { return {
    "$": ["presto-youtube.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-youtube.css"]
  }; }
  static get properties() { return {
    "src": {
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
      "attribute": "src",
      "reflect": false
    },
    "poster": {
      "type": "string",
      "mutable": true,
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
      "attribute": "poster",
      "reflect": false
    },
    "lazyLoad": {
      "type": "boolean",
      "mutable": true,
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
      "attribute": "lazy-load",
      "reflect": false
    },
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
    "getRef": {
      "type": "unknown",
      "mutable": true,
      "complexType": {
        "original": "(elm?: HTMLIFrameElement | HTMLVideoElement | HTMLDivElement) => void",
        "resolved": "(elm?: HTMLVideoElement | HTMLIFrameElement | HTMLDivElement) => void",
        "references": {
          "HTMLIFrameElement": {
            "location": "global"
          },
          "HTMLVideoElement": {
            "location": "global"
          },
          "HTMLDivElement": {
            "location": "global"
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
    "reloadPlayer": {},
    "isWebView": {}
  }; }
  static get events() { return [{
      "method": "reload",
      "name": "reload",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": "Events"
      },
      "complexType": {
        "original": "string",
        "resolved": "string",
        "references": {}
      }
    }]; }
  static get watchers() { return [{
      "propName": "player",
      "methodName": "handlePlayerChange"
    }]; }
}
