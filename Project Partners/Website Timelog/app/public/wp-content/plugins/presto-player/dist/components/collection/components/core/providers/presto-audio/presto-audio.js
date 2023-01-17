import { Component, h, Event, Prop, State } from '@stencil/core';
import { lightOrDark } from '../../../../util';
export class PrestoAudio {
  renderPosterImage() {
    if (this.poster) {
      return (h("div", { class: "presto-audio__poster" },
        h("img", { src: this.poster })));
    }
    return;
  }
  hasPosterArea() {
    var _a;
    return !!this.poster || !!((_a = this.preset) === null || _a === void 0 ? void 0 : _a['play-large']);
  }
  renderMobilePoster() {
    var _a;
    return (h("div", { class: "presto-audio__poster-wrapper-mobile" },
      !!((_a = this.preset) === null || _a === void 0 ? void 0 : _a['play-large']) && this.renderLargePlay('presto-audio__large-play-button is-relative'),
      h("div", { class: "presto-audio__mobile-title" }, this.mediaTitle)));
  }
  renderLargePlay(className = 'presto-audio__large-play-button') {
    return (h("div", { class: className, onClick: () => {
        if (!this.player.playing) {
          this.playVideo.emit();
        }
        else {
          this.pauseVideo.emit();
        }
      } },
      h("svg", { class: "presto-audio__icon-play", width: "16", height: "18", viewBox: "0 0 16 18", fill: "none", xmlns: "http://www.w3.org/2000/svg" },
        h("path", { d: "M15.5588 9.00005L0.117662 17.915L0.117662 0.0850823L15.5588 9.00005Z" })),
      h("svg", { class: "presto-audio__icon-pause", width: "17", height: "17", viewBox: "0 0 17 17", fill: "none", xmlns: "http://www.w3.org/2000/svg" },
        h("rect", { width: "5", height: "17" }),
        h("rect", { x: "12", width: "5", height: "17" }))));
  }
  componentDidLoad() {
    // Only run if ResizeObserver is supported.
    if ('ResizeObserver' in window) {
      var ro = new ResizeObserver(entries => {
        entries.forEach(entry => (this.width = entry.contentRect.width));
      });
      ro.observe(this.el);
    }
  }
  render() {
    var _a, _b, _c, _d, _e;
    return (h("div", { ref: el => (this.el = el), class: {
        'presto-audio__wrapper': true,
        'has-poster': !!this.poster,
        'has-play-large': !!((_a = this.preset) === null || _a === void 0 ? void 0 : _a['play-large']),
        'is-playing': (_b = this.player) === null || _b === void 0 ? void 0 : _b.playing,
        'has-light-background': lightOrDark((_c = this.preset) === null || _c === void 0 ? void 0 : _c.background_color) === 'light',
        'has-dark-background': lightOrDark((_d = this.preset) === null || _d === void 0 ? void 0 : _d.background_color) === 'dark',
        'breakpoint-small': this.width < 520,
        'skin-stacked': this.width < 520,
        'breakpoint-large': this.width >= 520,
      } },
      this.hasPosterArea() && (h("div", { class: "presto-audio__poster-wrapper" },
        !!((_e = this.preset) === null || _e === void 0 ? void 0 : _e['play-large']) && h("div", { class: "presto-audio__large-play-wrapper" }, this.renderLargePlay()),
        this.renderPosterImage())),
      this.hasPosterArea() && this.renderMobilePoster(),
      h("div", { class: "presto-audio__controls-wrapper" },
        h("div", { class: "presto-audio__title" }, this.mediaTitle),
        h("audio", { part: "audio-player", ref: this.getRef, autoplay: this.autoplay, preload: this.preload, "data-poster": this.poster },
          h("source", { src: this.src }),
          !!this.tracks &&
            !!this.tracks.length &&
            this.tracks.map(track => h("track", { kind: "captions", label: (track === null || track === void 0 ? void 0 : track.label) ? track.label : 'Captions', src: track === null || track === void 0 ? void 0 : track.src, srclang: (track === null || track === void 0 ? void 0 : track.srcLang) ? track === null || track === void 0 ? void 0 : track.srcLang : 'en' }))))));
  }
  static get is() { return "presto-audio"; }
  static get originalStyleUrls() { return {
    "$": ["presto-audio.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-audio.css"]
  }; }
  static get properties() { return {
    "getRef": {
      "type": "unknown",
      "mutable": true,
      "complexType": {
        "original": "(elm?: HTMLAudioElement) => void",
        "resolved": "(elm?: HTMLAudioElement) => void",
        "references": {
          "HTMLAudioElement": {
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
    "preset": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "presetAttributes",
        "resolved": "presetAttributes",
        "references": {
          "presetAttributes": {
            "location": "import",
            "path": "../../../../interfaces"
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
    },
    "mediaTitle": {
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
      "attribute": "media-title",
      "reflect": false
    }
  }; }
  static get states() { return {
    "width": {}
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
    }]; }
}
