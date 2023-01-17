import { Component, Prop, h } from '@stencil/core';
export class PrestoActionBarUi {
  render() {
    return (h("div", { class: {
        'bar': true,
        'bar--open': this.open,
      } },
      h("span", { part: "content", class: "bar__content" },
        h("slot", null)),
      h("span", { part: "button", class: "bar__button" },
        h("slot", { name: "button" }))));
  }
  static get is() { return "presto-action-bar-ui"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-action-bar-ui.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-action-bar-ui.css"]
  }; }
  static get properties() { return {
    "open": {
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
        "text": ""
      },
      "attribute": "open",
      "reflect": false
    }
  }; }
}
