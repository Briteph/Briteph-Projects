import { Component, Prop, Event, State, Element, h } from '@stencil/core';
import Fragment from 'stencil-fragment';
export class PrestoPlayerButton {
  constructor() {
    this.hasFocus = false;
    this.hasLabel = false;
    this.hasPrefix = false;
    this.hasSuffix = false;
    /** The button's type. */
    this.type = 'default';
    /** The button's size. */
    this.size = 'medium';
    /** Draws the button with a caret for use with dropdowns, popovers, etc. */
    this.full = false;
    /** Disables the button. */
    this.disabled = false;
    /** Indicates if activating the button should submit the form. Ignored when `href` is set. */
    this.submit = false;
  }
  componentWillLoad() {
    this.handleSlotChange();
  }
  /** Simulates a click on the button. */
  click() {
    this.button.click();
  }
  /** Sets focus on the button. */
  focus(options) {
    this.button.focus(options);
  }
  /** Removes focus from the button. */
  blur() {
    this.button.blur();
  }
  handleSlotChange() {
    this.hasLabel = !!this.button.children;
    this.hasPrefix = !!this.button.querySelector('[slot="prefix"]');
    this.hasSuffix = !!this.button.querySelector('[slot="suffix"]');
  }
  handleBlur() {
    this.hasFocus = false;
    this.prestoBlur.emit();
  }
  handleFocus() {
    this.hasFocus = true;
    this.prestoFocus.emit();
  }
  handleClick(event) {
    if (this.disabled) {
      event.preventDefault();
      event.stopPropagation();
    }
  }
  render() {
    const isLink = this.href ? true : false;
    const interior = (h(Fragment, null,
      h("span", { part: "prefix", class: "button__prefix" },
        h("slot", { onSlotchange: () => this.handleSlotChange(), name: "prefix" })),
      h("span", { part: "label", class: "button__label" },
        h("slot", { onSlotchange: () => this.handleSlotChange() })),
      h("span", { part: "suffix", class: "button__suffix" },
        h("slot", { onSlotchange: () => this.handleSlotChange(), name: "suffix" }))));
    const button = (h("button", { part: "base", class: {
        'button': true,
        'button--default': this.type === 'default',
        'button--primary': this.type === 'primary',
        'button--success': this.type === 'success',
        'button--info': this.type === 'info',
        'button--warning': this.type === 'warning',
        'button--danger': this.type === 'danger',
        'button--text': this.type === 'text',
        'button--small': this.size === 'small',
        'button--medium': this.size === 'medium',
        'button--large': this.size === 'large',
        'button--disabled': this.disabled,
        'button--focused': this.hasFocus,
        'button--has-label': this.hasLabel,
        'button--has-prefix': this.hasPrefix,
        'button--has-suffix': this.hasSuffix,
      }, disabled: this.disabled, type: this.submit ? 'submit' : 'button', name: this.name, value: this.value, onBlur: () => this.handleBlur, onFocus: () => this.handleFocus, onClick: () => this.handleClick }, interior));
    const link = (h("a", { part: "base", class: {
        'button': true,
        'button--default': this.type === 'default',
        'button--primary': this.type === 'primary',
        'button--success': this.type === 'success',
        'button--info': this.type === 'info',
        'button--warning': this.type === 'warning',
        'button--danger': this.type === 'danger',
        'button--text': this.type === 'text',
        'button--small': this.size === 'small',
        'button--medium': this.size === 'medium',
        'button--large': this.size === 'large',
        'button--disabled': this.disabled,
        'button--focused': this.hasFocus,
        'button--has-label': this.hasLabel,
        'button--has-prefix': this.hasPrefix,
        'button--has-suffix': this.hasSuffix,
      }, href: this.href, target: this.target, download: this.download, rel: this.target ? 'noreferrer noopener' : undefined, role: "button", "aria-disabled": this.disabled ? 'true' : 'false', tabindex: this.disabled ? '-1' : '0', onBlur: () => this.handleBlur, onFocus: () => this.handleFocus, onClick: () => this.handleClick }, interior));
    return isLink ? link : button;
  }
  static get is() { return "presto-player-button"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-player-button.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-player-button.css"]
  }; }
  static get properties() { return {
    "type": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'default' | 'primary' | 'success' | 'info' | 'warning' | 'danger' | 'text'",
        "resolved": "\"danger\" | \"default\" | \"info\" | \"primary\" | \"success\" | \"text\" | \"warning\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "The button's type."
      },
      "attribute": "type",
      "reflect": true,
      "defaultValue": "'default'"
    },
    "size": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'small' | 'medium' | 'large'",
        "resolved": "\"large\" | \"medium\" | \"small\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "The button's size."
      },
      "attribute": "size",
      "reflect": true,
      "defaultValue": "'medium'"
    },
    "full": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": "Draws the button with a caret for use with dropdowns, popovers, etc."
      },
      "attribute": "full",
      "reflect": true,
      "defaultValue": "false"
    },
    "disabled": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": "Disables the button."
      },
      "attribute": "disabled",
      "reflect": true,
      "defaultValue": "false"
    },
    "submit": {
      "type": "boolean",
      "mutable": false,
      "complexType": {
        "original": "boolean",
        "resolved": "boolean",
        "references": {}
      },
      "required": false,
      "optional": true,
      "docs": {
        "tags": [],
        "text": "Indicates if activating the button should submit the form. Ignored when `href` is set."
      },
      "attribute": "submit",
      "reflect": true,
      "defaultValue": "false"
    },
    "name": {
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
        "text": "An optional name for the button. Ignored when `href` is set."
      },
      "attribute": "name",
      "reflect": false
    },
    "value": {
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
        "text": "An optional value for the button. Ignored when `href` is set."
      },
      "attribute": "value",
      "reflect": false
    },
    "href": {
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
        "text": "When set, the underlying button will be rendered as an `<a>` with this `href` instead of a `<button>`."
      },
      "attribute": "href",
      "reflect": false
    },
    "target": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'_blank' | '_parent' | '_self' | '_top'",
        "resolved": "\"_blank\" | \"_parent\" | \"_self\" | \"_top\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": "Tells the browser where to open the link. Only used when `href` is set."
      },
      "attribute": "target",
      "reflect": true
    },
    "download": {
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
        "text": "Tells the browser to download the linked file as this filename. Only used when `href` is set."
      },
      "attribute": "download",
      "reflect": false
    }
  }; }
  static get states() { return {
    "hasFocus": {},
    "hasLabel": {},
    "hasPrefix": {},
    "hasSuffix": {}
  }; }
  static get events() { return [{
      "method": "prestoBlur",
      "name": "prestoBlur",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": "Emitted when the button loses focus."
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }, {
      "method": "prestoFocus",
      "name": "prestoFocus",
      "bubbles": true,
      "cancelable": true,
      "composed": true,
      "docs": {
        "tags": [],
        "text": "Emitted when the button gains focus."
      },
      "complexType": {
        "original": "void",
        "resolved": "void",
        "references": {}
      }
    }]; }
  static get elementRef() { return "button"; }
}
