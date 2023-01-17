import { Component, Event, h, Prop, State } from '@stencil/core';
import fitText from '../../../../../library/fittext.js';
export class EmailOverlayUI {
  /**
   * Handle form submission
   * @param e Event
   */
  handleSubmit(e) {
    this.submitForm.emit({
      email: this.email,
    });
    e.preventDefault();
  }
  componentDidLoad() {
    let currentType = this.type || this.provider;
    this.isAudioProvider = currentType === 'audio' ? true : false;
    fitText(this.textInput, 3, {
      maxFontSize: this.isAudioProvider ? 15 : 20,
      minFontSize: 8,
    });
  }
  /**
   * Handle input change
   * @param e Event
   */
  handleChange(e) {
    this.email = e.target.value;
  }
  render() {
    var _a, _b, _c, _d;
    return (h("div", { class: `overlay ${this.direction === 'rtl' ? 'rtl' : ''}`, ref: el => (this.textInput = el) },
      h("div", { class: "content" },
        h("h1", { class: this.isAudioProvider && 'is-small' }, this.headline || ((_a = this === null || this === void 0 ? void 0 : this.i18n) === null || _a === void 0 ? void 0 : _a.emailDefaultHeadline)),
        this.isLoading ? (h("presto-player-spinner", null)) : (h("form", { onSubmit: e => this.handleSubmit(e) },
          h("input", { type: "email", placeholder: (_b = this === null || this === void 0 ? void 0 : this.i18n) === null || _b === void 0 ? void 0 : _b.emailPlaceholder, value: this.email, onInput: event => this.handleChange(event), required: true }),
          h("button", { type: "submit" }, this.buttonText || ((_c = this === null || this === void 0 ? void 0 : this.i18n) === null || _c === void 0 ? void 0 : _c.play)))),
        this.errorMessage && h("p", { class: "error" }, this.errorMessage),
        this.bottomText && h("p", { innerHTML: this.bottomText, class: this.isAudioProvider && 'is-small' }),
        !!this.allowSkip && (h("div", { class: "skip", onClick: () => {
            this.skip.emit();
          } }, (_d = this === null || this === void 0 ? void 0 : this.i18n) === null || _d === void 0 ? void 0 :
          _d.skip,
          " \u2192")))));
  }
  static get is() { return "presto-email-overlay-ui"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-email-overlay-ui.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-email-overlay-ui.css"]
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
    "borderRadius": {
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
      "attribute": "border-radius",
      "reflect": false
    },
    "isLoading": {
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
      "attribute": "is-loading",
      "reflect": false
    },
    "errorMessage": {
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
      "attribute": "error-message",
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
    "email": {},
    "isAudioProvider": {}
  }; }
  static get events() { return [{
      "method": "submitForm",
      "name": "submitForm",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": "Events"
      },
      "complexType": {
        "original": "object",
        "resolved": "object",
        "references": {}
      }
    }, {
      "method": "skip",
      "name": "skip",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": ""
      },
      "complexType": {
        "original": "object",
        "resolved": "object",
        "references": {}
      }
    }]; }
}
