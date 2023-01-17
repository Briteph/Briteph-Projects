import { Component, Event, h, Prop } from '@stencil/core';
export class PrestoMutedOverlay {
  render() {
    var _a, _b, _c, _d, _e, _f, _g, _h, _j;
    return (h("div", { class: "presto-player__muted-overlay", onClick: () => {
        this.mutedPreview = false;
        this.playVideo.emit();
      } },
      ((_a = this.preset) === null || _a === void 0 ? void 0 : _a['play-large']) && (h("div", { class: "plyr__control plyr__control--overlaid", "data-plyr": "play", "aria-label": "Play", part: "muted-overlay-play" },
        h("svg", { id: "plyr-play", viewBox: "0 0 18 18" },
          h("path", { d: "M15.562 8.1L3.87.225c-.818-.562-1.87 0-1.87.9v15.75c0 .9 1.052 1.462 1.87.9L15.563 9.9c.584-.45.584-1.35 0-1.8z" })),
        h("span", { class: "plyr__sr-only" }, "Play"))),
      ((_b = this.mutedOverlay) === null || _b === void 0 ? void 0 : _b.enabled) && ((_c = this.mutedOverlay) === null || _c === void 0 ? void 0 : _c.src) ? (h("div", { class: "presto-player__overlay is-image", part: "muted-overlay-image", style: {
          width: `${((_d = this.mutedOverlay) === null || _d === void 0 ? void 0 : _d.width) || 50}%`,
          left: `${(((_f = (_e = this.mutedOverlay) === null || _e === void 0 ? void 0 : _e.focalPoint) === null || _f === void 0 ? void 0 : _f.x) || 0.5) * 100}%`,
          top: `${(((_h = (_g = this.mutedOverlay) === null || _g === void 0 ? void 0 : _g.focalPoint) === null || _h === void 0 ? void 0 : _h.y) || 0.5) * 100}%`,
        } },
        h("img", { src: (_j = this.mutedOverlay) === null || _j === void 0 ? void 0 : _j.src, style: { transform: 'translateX(-50%) translateY(-50%)' } }))) : ('')));
  }
  static get is() { return "presto-muted-overlay"; }
  static get originalStyleUrls() { return {
    "$": ["presto-muted-overlay.css"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-muted-overlay.css"]
  }; }
  static get properties() { return {
    "mutedPreview": {
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
      "attribute": "muted-preview",
      "reflect": false
    },
    "mutedOverlay": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "MutedOverlay",
        "resolved": "MutedOverlay",
        "references": {
          "MutedOverlay": {
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
    }
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
    }]; }
}
