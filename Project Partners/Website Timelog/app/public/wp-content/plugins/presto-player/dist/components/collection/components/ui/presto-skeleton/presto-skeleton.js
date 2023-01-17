import { Component, Prop, h } from '@stencil/core';
export class PrestoSkeleton {
  constructor() {
    this.effect = 'sheen';
  }
  render() {
    return (h("div", { part: "base", class: {
        'skeleton': true,
        'skeleton--pulse': this.effect === 'pulse',
        'skeleton--sheen': this.effect === 'sheen',
      }, "aria-busy": "true", "aria-live": "polite" },
      h("div", { part: "indicator", class: "skeleton__indicator" })));
  }
  static get is() { return "presto-player-skeleton"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-skeleton.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-skeleton.css"]
  }; }
  static get properties() { return {
    "effect": {
      "type": "string",
      "mutable": false,
      "complexType": {
        "original": "'pulse' | 'sheen' | 'none'",
        "resolved": "\"none\" | \"pulse\" | \"sheen\"",
        "references": {}
      },
      "required": false,
      "optional": false,
      "docs": {
        "tags": [],
        "text": ""
      },
      "attribute": "effect",
      "reflect": false,
      "defaultValue": "'sheen'"
    }
  }; }
}
