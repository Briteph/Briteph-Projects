import { Component, h, Prop } from '@stencil/core';
export class PrestoTimestamp {
  handleClick(e) {
    e.preventDefault();
    const player = document.body.querySelector('presto-player');
    if (!player)
      return;
    player.goToAndPlay(this.timeToSeconds(this.time));
  }
  /**
   * Formats Timestamp to seconds.
   * I.E. 12:34 to number of seconds.
   */
  timeToSeconds(time) {
    let pieces = time.split(':');
    let seconds;
    if (pieces.length > 1) {
      seconds = parseInt(pieces[0]) * 60;
    }
    return parseInt(pieces[1]) + parseInt(seconds);
  }
  render() {
    return (h("a", { href: "#", class: "presto-timestamp", onClick: e => this.handleClick(e) },
      h("slot", null, this.time)));
  }
  static get is() { return "presto-timestamp"; }
  static get originalStyleUrls() { return {
    "$": ["presto-timestamp.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-timestamp.css"]
  }; }
  static get properties() { return {
    "time": {
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
      "attribute": "time",
      "reflect": false
    }
  }; }
}
