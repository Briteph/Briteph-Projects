import { Component, Event, h, Prop, State } from '@stencil/core';
import fitText from '../../../../../library/fittext.js';
export class CTAOverlayUI {
  /**
   * Shrink text.
   */
  componentDidLoad() {
    let currentType = this.type || this.provider;
    this.isAudioProvider = currentType === 'audio' ? true : false;
    fitText(this.textInput, 3, {
      maxFontSize: this.isAudioProvider === true ? 15 : 20,
      minFontSize: 8,
    });
  }
  handleCTAClick(e) {
    var _a;
    if (!((_a = this.buttonLink) === null || _a === void 0 ? void 0 : _a.url)) {
      return;
    }
    e.preventDefault();
    e.stopPropagation();
    this.handleLink();
  }
  handleLink() {
    var _a, _b, _c;
    if ((_a = this.buttonLink) === null || _a === void 0 ? void 0 : _a.opensInNewTab) {
      window.open((_b = this.buttonLink) === null || _b === void 0 ? void 0 : _b.url, '_blank');
    }
    else {
      window.location.href = (_c = this.buttonLink) === null || _c === void 0 ? void 0 : _c.url;
    }
  }
  render() {
    var _a, _b, _c, _d, _e;
    return (h("div", { class: "wrapper", ref: el => (this.textInput = el) },
      h("div", { onClick: e => this.handleCTAClick(e), class: `overlay ${this.direction === 'rtl' ? 'rtl' : ''} ${((_a = this.buttonLink) === null || _a === void 0 ? void 0 : _a.url) ? 'has-link' : ''}` },
        h("div", { class: this.isAudioProvider ? 'content is-centered' : 'content' },
          h("h1", { part: "cta-headline" }, this.headline || this.defaultHeadline),
          this.bottomText && h("p", { class: this.isAudioProvider && 'is-small', part: "cta-bottom-text", innerHTML: this.bottomText }),
          this.showButton && (h("presto-player-button", { full: true, onClick: e => this.handleCTAClick(e), part: "cta-button", href: (_b = this === null || this === void 0 ? void 0 : this.buttonLink) === null || _b === void 0 ? void 0 : _b.url, target: ((_c = this === null || this === void 0 ? void 0 : this.buttonLink) === null || _c === void 0 ? void 0 : _c.opensInNewTab) ? '_blank' : '_self', class: "button", type: "primary" }, this.buttonText)))),
      !!this.allowRewatch && (h("div", { class: "rewatch", onClick: e => {
          e.preventDefault();
          e.stopImmediatePropagation();
          this.rewatch.emit();
        } },
        h("svg", { xmlns: "http://www.w3.org/2000/svg", width: "16", height: "16", viewBox: "0 0 24 24", fill: "none", stroke: "currentColor", "stroke-width": "3", "stroke-linecap": "round", "stroke-linejoin": "round", class: "icon icon-corner-up-left" },
          h("polyline", { points: "9 14 4 9 9 4" }),
          h("path", { d: "M20 20v-7a4 4 0 0 0-4-4H4" })), (_d = this === null || this === void 0 ? void 0 : this.i18n) === null || _d === void 0 ? void 0 :
        _d.rewatch)),
      !!this.allowSkip && (h("div", { class: "skip", onClick: e => {
          e.preventDefault();
          this.skip.emit();
        } }, (_e = this === null || this === void 0 ? void 0 : this.i18n) === null || _e === void 0 ? void 0 :
        _e.skip,
        " \u2192"))));
  }
  static get is() { return "presto-cta-overlay-ui"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-cta-overlay-ui.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-cta-overlay-ui.css"]
  }; }
  static get properties() { return {
    "headline": {
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
        "text": "Props"
      },
      "attribute": "headline",
      "reflect": false
    },
    "defaultHeadline": {
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
      "attribute": "default-headline",
      "reflect": false
    },
    "bottomText": {
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
      "attribute": "bottom-text",
      "reflect": false
    },
    "showButton": {
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
      "attribute": "show-button",
      "reflect": false
    },
    "buttonText": {
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
      "attribute": "button-text",
      "reflect": false
    },
    "buttonType": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'link' | 'time'",
        "resolved": "\"link\" | \"time\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "button-type",
      "reflect": false
    },
    "buttonLink": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "ButtonLinkObject",
        "resolved": "ButtonLinkObject",
        "references": {
          "ButtonLinkObject": {
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
    "allowRewatch": {
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
      "attribute": "allow-rewatch",
      "reflect": false
    },
    "allowSkip": {
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
      "attribute": "allow-skip",
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
    },
    "type": {
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
      "attribute": "type",
      "reflect": false
    }
  }; }
  static get states() { return {
    "isAudioProvider": {}
  }; }
  static get events() { return [{
      "method": "skip",
      "name": "skip",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": "Events"
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }, {
      "method": "rewatch",
      "name": "rewatch",
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
