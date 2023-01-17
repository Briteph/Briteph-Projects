import { Component, h, Prop, Element, State, Event } from '@stencil/core';
import { timeToSeconds, parseColor } from '../../../../../util';
export class PrestoDynamicOverlays {
  constructor() {
    this.watermarkRef = {
      left: null,
      right: null,
    };
    this.destroy = false;
    this.refs = {};
  }
  componentDidLoad() {
    if (!this.player) {
      return;
    }
    this.player.on('timeupdate', e => {
      this.currentTime = e.detail.plyr.currentTime;
      this.checkValidity(); // check overlays validity.
    });
  }
  /**
   * Check validity of the overlays.
   * Blow up if any funny business.
   */
  checkValidity() {
    var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k;
    // only if we are playing
    if (!this.player.playing) {
      return;
    }
    if (!((_a = this === null || this === void 0 ? void 0 : this.overlays) === null || _a === void 0 ? void 0 : _a.length) && !((_c = (_b = this.preset) === null || _b === void 0 ? void 0 : _b.watermark) === null || _c === void 0 ? void 0 : _c.enabled)) {
      return;
    }
    // make sure container and other divs are not removed.
    if (!((_d = this.container) === null || _d === void 0 ? void 0 : _d.offsetParent) || !((_e = this.topLeft) === null || _e === void 0 ? void 0 : _e.offsetParent) || !((_f = this.topRight) === null || _f === void 0 ? void 0 : _f.offsetParent)) {
      this.reloadComponent.emit();
    }
    // check for funny business on overlays.
    Object.keys(this.refs || {}).forEach(key => {
      const { overlay, component } = this.refs[key];
      this.checkComponent(component, overlay.text, () => this.reloadComponent.emit());
    });
    // check for any funny business on watermark
    if (this.shouldShowWatermark('top-left') && this.watermarkRef.left) {
      this.checkComponent(this.watermarkRef.left, (_h = (_g = this.preset) === null || _g === void 0 ? void 0 : _g.watermark) === null || _h === void 0 ? void 0 : _h.text, () => this.reloadComponent.emit());
    }
    if (this.shouldShowWatermark('top-right') && this.watermarkRef.right) {
      this.checkComponent(this.watermarkRef.right, (_k = (_j = this.preset) === null || _j === void 0 ? void 0 : _j.watermark) === null || _k === void 0 ? void 0 : _k.text, () => this.reloadComponent.emit());
    }
  }
  /**
   * Check if the component is valid.
   * If invalid, run a callback.
   *
   * @param component
   * @param text
   * @returns
   */
  checkComponent(component, text, callback) {
    // hidden, it's invalid.
    if (!(component === null || component === void 0 ? void 0 : component.offsetParent)) {
      return callback();
    }
    // slot was removed.
    if (!component.shadowRoot.querySelector('slot')) {
      return callback();
    }
    // only if we're playing
    if (!!this.player && this.player.playing) {
      const content = component.shadowRoot.querySelector('slot').assignedNodes()[0];
      if (content) {
        // slot content changed.
        if (content.parentElement.innerHTML != text) {
          return callback();
        }
        // font-size changes.
        const style = getComputedStyle(content.parentElement.shadowRoot.querySelector('.overlay-text'));
        if (parseInt(style.fontSize, 10) < 10) {
          return callback();
        }
        // opacity changes
        const color = parseColor(style.color);
        if ((color === null || color === void 0 ? void 0 : color[3]) !== '1') {
          return callback();
        }
      }
    }
  }
  /**
   * Show the overlay
   * @param overlay
   * @returns
   */
  shouldShowOverlay(overlay) {
    // need a time.
    if (typeof this.currentTime === 'undefined') {
      return;
    }
    // bail if current time is less than start time
    if (this.currentTime < timeToSeconds(overlay === null || overlay === void 0 ? void 0 : overlay.startTime)) {
      return false;
    }
    // bail if current time is more than end time
    if (this.currentTime > timeToSeconds(overlay === null || overlay === void 0 ? void 0 : overlay.endTime)) {
      return false;
    }
    return true;
  }
  /**
   * Render the watermark
   */
  renderOverlay(overlay) {
    var _a, _b;
    return (h("presto-dynamic-overlay-ui", { class: {
        visible: this.shouldShowOverlay(overlay),
      }, ref: el => (this.refs[overlay.id] = {
        overlay,
        component: el,
      }), key: overlay.id, position: overlay.position, href: (_a = overlay === null || overlay === void 0 ? void 0 : overlay.link) === null || _a === void 0 ? void 0 : _a.url, target: ((_b = overlay === null || overlay === void 0 ? void 0 : overlay.link) === null || _b === void 0 ? void 0 : _b.opensInNewTab) ? '_blank' : '_self', innerHTML: this.shouldShowOverlay(overlay) ? overlay.text : '', style: {
        '--presto-dynamic-overlay-color': (overlay === null || overlay === void 0 ? void 0 : overlay.color) || '#fff',
        '--presto-dynamic-overlay-background': (overlay === null || overlay === void 0 ? void 0 : overlay.backgroundColor) || '#333',
        '--presto-dynamic-overlay-opacity': (overlay === null || overlay === void 0 ? void 0 : overlay.opacity) ? (overlay.opacity / 100).toString() : '1',
      } }));
  }
  /**
   * Should we show the watermark?
   */
  shouldShowWatermark(position) {
    var _a, _b, _c;
    if (!((_b = (_a = this.preset) === null || _a === void 0 ? void 0 : _a.watermark) === null || _b === void 0 ? void 0 : _b.enabled))
      return false;
    const watermark = (_c = this.preset) === null || _c === void 0 ? void 0 : _c.watermark;
    // randomize
    if (watermark.position === 'randomize') {
      const time = Math.floor((this.player.currentTime || 0) / 10);
      // even or odd
      if (time % 2 == 0) {
        return position === 'top-left';
      }
      else {
        return position === 'top-right';
      }
    }
    // position is set.
    if (watermark.position === position) {
      return true;
    }
    // fallback.
    if (!watermark.position && 'top-right' === position) {
      return true;
    }
    return false;
  }
  render() {
    var _a, _b, _c, _d, _e, _f, _g, _h, _j, _k, _l;
    // bail if disabled.
    if (!this.enabled) {
      return;
    }
    // bail if no overlays or watermark
    if (!((_a = this === null || this === void 0 ? void 0 : this.overlays) === null || _a === void 0 ? void 0 : _a.length) && !((_c = (_b = this.preset) === null || _b === void 0 ? void 0 : _b.watermark) === null || _c === void 0 ? void 0 : _c.enabled)) {
      return;
    }
    return (h("div", { class: "overlays", ref: el => (this.container = el) },
      h("div", { class: "top-left", ref: el => (this.topLeft = el) },
        !!this.shouldShowWatermark('top-left') && (h("presto-dynamic-overlay-ui", { ref: el => (this.watermarkRef.left = el), style: {
            '--presto-dynamic-overlay-color': ((_d = this.preset.watermark) === null || _d === void 0 ? void 0 : _d.color) || '#fff',
            '--presto-dynamic-overlay-background': ((_e = this.preset.watermark) === null || _e === void 0 ? void 0 : _e.backgroundColor) || '#333',
            '--presto-dynamic-overlay-opacity': ((_f = this.preset.watermark) === null || _f === void 0 ? void 0 : _f.opacity) ? (this.preset.watermark.opacity / 100).toString() : '1',
          }, class: "visible", position: 'top-left', innerHTML: this.preset.watermark.text })),
        !!((_g = this === null || this === void 0 ? void 0 : this.overlays) === null || _g === void 0 ? void 0 : _g.length) &&
          this.overlays.map(overlay => {
            if (overlay.position !== 'top-left') {
              return '';
            }
            return this.renderOverlay(overlay);
          })),
      h("div", { class: "top-right", ref: el => (this.topRight = el) },
        !!this.shouldShowWatermark('top-right') && (h("presto-dynamic-overlay-ui", { ref: el => (this.watermarkRef.right = el), style: {
            '--presto-dynamic-overlay-color': ((_h = this.preset.watermark) === null || _h === void 0 ? void 0 : _h.color) || '#fff',
            '--presto-dynamic-overlay-background': ((_j = this.preset.watermark) === null || _j === void 0 ? void 0 : _j.backgroundColor) || '#333',
            '--presto-dynamic-overlay-opacity': ((_k = this.preset.watermark) === null || _k === void 0 ? void 0 : _k.opacity) ? (this.preset.watermark.opacity / 100).toString() : '1',
          }, class: "visible", position: 'top-right', innerHTML: this.preset.watermark.text })),
        !!((_l = this === null || this === void 0 ? void 0 : this.overlays) === null || _l === void 0 ? void 0 : _l.length) &&
          this.overlays.map(overlay => {
            if (overlay.position !== 'top-right') {
              return '';
            }
            return this.renderOverlay(overlay);
          }))));
  }
  static get is() { return "presto-dynamic-overlays"; }
  static get encapsulation() { return "shadow"; }
  static get originalStyleUrls() { return {
    "$": ["presto-dynamic-overlays.scss"]
  }; }
  static get styleUrls() { return {
    "$": ["presto-dynamic-overlays.css"]
  }; }
  static get properties() { return {
    "overlays": {
      "type": "unknown",
      "mutable": false,
      "complexType": {
        "original": "Array<DynamicOverlay>",
        "resolved": "DynamicOverlay[]",
        "references": {
          "Array": {
            "location": "global"
          },
          "DynamicOverlay": {
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
    "enabled": {
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
      "attribute": "enabled",
      "reflect": false
    }
  }; }
  static get states() { return {
    "currentTime": {},
    "destroy": {}
  }; }
  static get events() { return [{
      "method": "reloadComponent",
      "name": "reloadComponent",
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
  static get elementRef() { return "el"; }
}
