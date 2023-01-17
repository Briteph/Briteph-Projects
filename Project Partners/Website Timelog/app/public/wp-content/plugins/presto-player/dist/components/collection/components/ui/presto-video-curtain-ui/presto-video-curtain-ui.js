import { Component, h, Prop } from '@stencil/core';
export class CurtainUI {
  render() {
    return (h("div", { class: "curtain", part: "base" },
      h("div", { class: "content", part: "curtain-content" },
        h("slot", null))));
  }
  static get is() { return "presto-video-curtain-ui"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-video-curtain-ui.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-video-curtain-ui.css"]
  }; }
  static get properties() { return {
    "actionUrl": {
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
      "attribute": "action-url",
      "reflect": false
    }
  }; }
}
