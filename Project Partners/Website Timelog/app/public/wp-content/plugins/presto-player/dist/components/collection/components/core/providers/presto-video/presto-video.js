import { Component, h, Prop } from '@stencil/core';
export class PrestoVideo {
  render() {
    return (h("video", { class: {
        'presto-player__player': true,
        'plyr__video-embed': ['youtube', 'vimeo'].includes(this.provider),
      }, part: "video", ref: this.getRef, autoplay: this.autoplay, preload: this.preload, "data-poster": this.poster, playsinline: this.playsinline },
      h("source", { src: this.src }),
      !!this.tracks &&
        !!this.tracks.length &&
        this.tracks.map(track => h("track", { kind: "captions", label: (track === null || track === void 0 ? void 0 : track.label) ? track.label : 'Captions', src: track === null || track === void 0 ? void 0 : track.src, srclang: (track === null || track === void 0 ? void 0 : track.srcLang) ? track === null || track === void 0 ? void 0 : track.srcLang : 'en' }))));
  }
  static get is() { return "presto-video"; }
  static get originalStyleUrls() { return {
    "$": ["presto-video.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-video.css"]
  }; }
  static get properties() { return {
    "getRef": {
      "type": "unknown",
      "mutable": true,
      "complexType": {
        "original": "(elm?: HTMLVideoElement) => void",
        "resolved": "(elm?: HTMLVideoElement) => void",
        "references": {
          "HTMLVideoElement": {
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
    },
    "autoplay": {
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
      "attribute": "autoplay",
      "reflect": false
    },
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
    "preload": {
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
      "attribute": "preload",
      "reflect": false
    },
    "poster": {
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
      "attribute": "poster",
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
    "tracks": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "{ label: string; src: string; srcLang: string }[]",
        "resolved": "{ label: string; src: string; srcLang: string; }[]",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      }
    },
    "playsinline": {
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
      "attribute": "playsinline",
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
}
