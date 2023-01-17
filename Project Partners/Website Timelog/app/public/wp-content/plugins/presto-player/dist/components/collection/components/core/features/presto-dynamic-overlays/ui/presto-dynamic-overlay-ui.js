import { Component, Element, h, Prop } from '@stencil/core';
import fitText from '../../../../../library/fittext.js';
export class PrestoDynamicOverlayUi {
  constructor() {
    this.position = 'top-right';
  }
  closestElement(selector, el) {
    return (el && el != document && el != window && el.closest(selector)) || (el && this.closestElement(selector, el.getRootNode().host));
  }
  componentDidLoad() {
    let player = this.closestElement('presto-player', this.el);
    fitText(this.text, 3, {
      maxFontSize: 16,
      minFontSize: 10,
      container: player,
    });
  }
  render() {
    const Tag = this.href ? 'a' : 'span';
    return (h(Tag, { class: {
        'overlay-text': true,
        'overlay--top-left': this.position === 'top-left',
        'overlay--top-right': this.position === 'top-right',
      }, href: this.href, target: this.target, part: "overlay-text", ref: el => (this.text = el) },
      h("slot", null)));
  }
  static get is() { return "presto-dynamic-overlay-ui"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-dynamic-overlay-ui.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-dynamic-overlay-ui.css"]
  }; }
  static get properties() { return {
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
      "reflect": false
    },
    "position": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'top-left' | 'top-right'",
        "resolved": "\"top-left\" | \"top-right\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "position",
      "reflect": false,
      "defaultValue": "'top-right'"
    }
  }; }
  static get elementRef() { return "el"; }
}
