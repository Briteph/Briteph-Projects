'use strict';

function _interopNamespace(e) {
  if (e && e.__esModule) return e;
  var n = Object.create(null);
  if (e) {
    Object.keys(e).forEach(function (k) {
      if (k !== 'default') {
        var d = Object.getOwnPropertyDescriptor(e, k);
        Object.defineProperty(n, k, d.get ? d : {
          enumerable: true,
          get: function () {
            return e[k];
          }
        });
      }
    });
  }
  n['default'] = e;
  return Object.freeze(n);
}

function ownKeys(object, enumerableOnly) {
  var keys = Object.keys(object);

  if (Object.getOwnPropertySymbols) {
    var symbols = Object.getOwnPropertySymbols(object);
    enumerableOnly && (symbols = symbols.filter(function (sym) {
      return Object.getOwnPropertyDescriptor(object, sym).enumerable;
    })), keys.push.apply(keys, symbols);
  }

  return keys;
}

function _objectSpread2(target) {
  for (var i = 1; i < arguments.length; i++) {
    var source = null != arguments[i] ? arguments[i] : {};
    i % 2 ? ownKeys(Object(source), !0).forEach(function (key) {
      _defineProperty(target, key, source[key]);
    }) : Object.getOwnPropertyDescriptors ? Object.defineProperties(target, Object.getOwnPropertyDescriptors(source)) : ownKeys(Object(source)).forEach(function (key) {
      Object.defineProperty(target, key, Object.getOwnPropertyDescriptor(source, key));
    });
  }

  return target;
}

function _typeof(obj) {
  "@babel/helpers - typeof";

  return _typeof = "function" == typeof Symbol && "symbol" == typeof Symbol.iterator ? function (obj) {
    return typeof obj;
  } : function (obj) {
    return obj && "function" == typeof Symbol && obj.constructor === Symbol && obj !== Symbol.prototype ? "symbol" : typeof obj;
  }, _typeof(obj);
}

function _wrapRegExp() {
  _wrapRegExp = function (re, groups) {
    return new BabelRegExp(re, void 0, groups);
  };

  var _super = RegExp.prototype,
      _groups = new WeakMap();

  function BabelRegExp(re, flags, groups) {
    var _this = new RegExp(re, flags);

    return _groups.set(_this, groups || _groups.get(re)), _setPrototypeOf(_this, BabelRegExp.prototype);
  }

  function buildGroups(result, re) {
    var g = _groups.get(re);

    return Object.keys(g).reduce(function (groups, name) {
      return groups[name] = result[g[name]], groups;
    }, Object.create(null));
  }

  return _inherits(BabelRegExp, RegExp), BabelRegExp.prototype.exec = function (str) {
    var result = _super.exec.call(this, str);

    return result && (result.groups = buildGroups(result, this)), result;
  }, BabelRegExp.prototype[Symbol.replace] = function (str, substitution) {
    if ("string" == typeof substitution) {
      var groups = _groups.get(this);

      return _super[Symbol.replace].call(this, str, substitution.replace(/\$<([^>]+)>/g, function (_, name) {
        return "$" + groups[name];
      }));
    }

    if ("function" == typeof substitution) {
      var _this = this;

      return _super[Symbol.replace].call(this, str, function () {
        var args = arguments;
        return "object" != typeof args[args.length - 1] && (args = [].slice.call(args)).push(buildGroups(args, _this)), substitution.apply(this, args);
      });
    }

    return _super[Symbol.replace].call(this, str, substitution);
  }, _wrapRegExp.apply(this, arguments);
}

function asyncGeneratorStep(gen, resolve, reject, _next, _throw, key, arg) {
  try {
    var info = gen[key](arg);
    var value = info.value;
  } catch (error) {
    reject(error);
    return;
  }

  if (info.done) {
    resolve(value);
  } else {
    Promise.resolve(value).then(_next, _throw);
  }
}

function _asyncToGenerator(fn) {
  return function () {
    var self = this,
        args = arguments;
    return new Promise(function (resolve, reject) {
      var gen = fn.apply(self, args);

      function _next(value) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "next", value);
      }

      function _throw(err) {
        asyncGeneratorStep(gen, resolve, reject, _next, _throw, "throw", err);
      }

      _next(undefined);
    });
  };
}

function _classCallCheck(instance, Constructor) {
  if (!(instance instanceof Constructor)) {
    throw new TypeError("Cannot call a class as a function");
  }
}

function _defineProperties(target, props) {
  for (var i = 0; i < props.length; i++) {
    var descriptor = props[i];
    descriptor.enumerable = descriptor.enumerable || false;
    descriptor.configurable = true;
    if ("value" in descriptor) descriptor.writable = true;
    Object.defineProperty(target, descriptor.key, descriptor);
  }
}

function _createClass(Constructor, protoProps, staticProps) {
  if (protoProps) _defineProperties(Constructor.prototype, protoProps);
  if (staticProps) _defineProperties(Constructor, staticProps);
  Object.defineProperty(Constructor, "prototype", {
    writable: false
  });
  return Constructor;
}

function _defineProperty(obj, key, value) {
  if (key in obj) {
    Object.defineProperty(obj, key, {
      value: value,
      enumerable: true,
      configurable: true,
      writable: true
    });
  } else {
    obj[key] = value;
  }

  return obj;
}

function _inherits(subClass, superClass) {
  if (typeof superClass !== "function" && superClass !== null) {
    throw new TypeError("Super expression must either be null or a function");
  }

  Object.defineProperty(subClass, "prototype", {
    value: Object.create(superClass && superClass.prototype, {
      constructor: {
        value: subClass,
        writable: true,
        configurable: true
      }
    }),
    writable: false
  });
  if (superClass) _setPrototypeOf(subClass, superClass);
}

function _getPrototypeOf(o) {
  _getPrototypeOf = Object.setPrototypeOf ? Object.getPrototypeOf : function _getPrototypeOf(o) {
    return o.__proto__ || Object.getPrototypeOf(o);
  };
  return _getPrototypeOf(o);
}

function _setPrototypeOf(o, p) {
  _setPrototypeOf = Object.setPrototypeOf || function _setPrototypeOf(o, p) {
    o.__proto__ = p;
    return o;
  };

  return _setPrototypeOf(o, p);
}

function _isNativeReflectConstruct() {
  if (typeof Reflect === "undefined" || !Reflect.construct) return false;
  if (Reflect.construct.sham) return false;
  if (typeof Proxy === "function") return true;

  try {
    Boolean.prototype.valueOf.call(Reflect.construct(Boolean, [], function () {}));
    return true;
  } catch (e) {
    return false;
  }
}

function _construct(Parent, args, Class) {
  if (_isNativeReflectConstruct()) {
    _construct = Reflect.construct;
  } else {
    _construct = function _construct(Parent, args, Class) {
      var a = [null];
      a.push.apply(a, args);
      var Constructor = Function.bind.apply(Parent, a);
      var instance = new Constructor();
      if (Class) _setPrototypeOf(instance, Class.prototype);
      return instance;
    };
  }

  return _construct.apply(null, arguments);
}

function _isNativeFunction(fn) {
  return Function.toString.call(fn).indexOf("[native code]") !== -1;
}

function _wrapNativeSuper(Class) {
  var _cache = typeof Map === "function" ? new Map() : undefined;

  _wrapNativeSuper = function _wrapNativeSuper(Class) {
    if (Class === null || !_isNativeFunction(Class)) return Class;

    if (typeof Class !== "function") {
      throw new TypeError("Super expression must either be null or a function");
    }

    if (typeof _cache !== "undefined") {
      if (_cache.has(Class)) return _cache.get(Class);

      _cache.set(Class, Wrapper);
    }

    function Wrapper() {
      return _construct(Class, arguments, _getPrototypeOf(this).constructor);
    }

    Wrapper.prototype = Object.create(Class.prototype, {
      constructor: {
        value: Wrapper,
        enumerable: false,
        writable: true,
        configurable: true
      }
    });
    return _setPrototypeOf(Wrapper, Class);
  };

  return _wrapNativeSuper(Class);
}

function _objectWithoutPropertiesLoose(source, excluded) {
  if (source == null) return {};
  var target = {};
  var sourceKeys = Object.keys(source);
  var key, i;

  for (i = 0; i < sourceKeys.length; i++) {
    key = sourceKeys[i];
    if (excluded.indexOf(key) >= 0) continue;
    target[key] = source[key];
  }

  return target;
}

function _objectWithoutProperties(source, excluded) {
  if (source == null) return {};

  var target = _objectWithoutPropertiesLoose(source, excluded);

  var key, i;

  if (Object.getOwnPropertySymbols) {
    var sourceSymbolKeys = Object.getOwnPropertySymbols(source);

    for (i = 0; i < sourceSymbolKeys.length; i++) {
      key = sourceSymbolKeys[i];
      if (excluded.indexOf(key) >= 0) continue;
      if (!Object.prototype.propertyIsEnumerable.call(source, key)) continue;
      target[key] = source[key];
    }
  }

  return target;
}

function _assertThisInitialized(self) {
  if (self === void 0) {
    throw new ReferenceError("this hasn't been initialised - super() hasn't been called");
  }

  return self;
}

function _possibleConstructorReturn(self, call) {
  if (call && (typeof call === "object" || typeof call === "function")) {
    return call;
  } else if (call !== void 0) {
    throw new TypeError("Derived constructors may only return object or undefined");
  }

  return _assertThisInitialized(self);
}

function _createSuper(Derived) {
  var hasNativeReflectConstruct = _isNativeReflectConstruct();

  return function _createSuperInternal() {
    var Super = _getPrototypeOf(Derived),
        result;

    if (hasNativeReflectConstruct) {
      var NewTarget = _getPrototypeOf(this).constructor;

      result = Reflect.construct(Super, arguments, NewTarget);
    } else {
      result = Super.apply(this, arguments);
    }

    return _possibleConstructorReturn(this, result);
  };
}

function _slicedToArray(arr, i) {
  return _arrayWithHoles(arr) || _iterableToArrayLimit(arr, i) || _unsupportedIterableToArray(arr, i) || _nonIterableRest();
}

function _toConsumableArray(arr) {
  return _arrayWithoutHoles(arr) || _iterableToArray(arr) || _unsupportedIterableToArray(arr) || _nonIterableSpread();
}

function _arrayWithoutHoles(arr) {
  if (Array.isArray(arr)) return _arrayLikeToArray(arr);
}

function _arrayWithHoles(arr) {
  if (Array.isArray(arr)) return arr;
}

function _iterableToArray(iter) {
  if (typeof Symbol !== "undefined" && iter[Symbol.iterator] != null || iter["@@iterator"] != null) return Array.from(iter);
}

function _iterableToArrayLimit(arr, i) {
  var _i = arr == null ? null : typeof Symbol !== "undefined" && arr[Symbol.iterator] || arr["@@iterator"];

  if (_i == null) return;
  var _arr = [];
  var _n = true;
  var _d = false;

  var _s, _e;

  try {
    for (_i = _i.call(arr); !(_n = (_s = _i.next()).done); _n = true) {
      _arr.push(_s.value);

      if (i && _arr.length === i) break;
    }
  } catch (err) {
    _d = true;
    _e = err;
  } finally {
    try {
      if (!_n && _i["return"] != null) _i["return"]();
    } finally {
      if (_d) throw _e;
    }
  }

  return _arr;
}

function _unsupportedIterableToArray(o, minLen) {
  if (!o) return;
  if (typeof o === "string") return _arrayLikeToArray(o, minLen);
  var n = Object.prototype.toString.call(o).slice(8, -1);
  if (n === "Object" && o.constructor) n = o.constructor.name;
  if (n === "Map" || n === "Set") return Array.from(o);
  if (n === "Arguments" || /^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)) return _arrayLikeToArray(o, minLen);
}

function _arrayLikeToArray(arr, len) {
  if (len == null || len > arr.length) len = arr.length;

  for (var i = 0, arr2 = new Array(len); i < len; i++) arr2[i] = arr[i];

  return arr2;
}

function _nonIterableSpread() {
  throw new TypeError("Invalid attempt to spread non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

function _nonIterableRest() {
  throw new TypeError("Invalid attempt to destructure non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
}

function _createForOfIteratorHelper(o, allowArrayLike) {
  var it = typeof Symbol !== "undefined" && o[Symbol.iterator] || o["@@iterator"];

  if (!it) {
    if (Array.isArray(o) || (it = _unsupportedIterableToArray(o)) || allowArrayLike && o && typeof o.length === "number") {
      if (it) o = it;
      var i = 0;

      var F = function () {};

      return {
        s: F,
        n: function () {
          if (i >= o.length) return {
            done: true
          };
          return {
            done: false,
            value: o[i++]
          };
        },
        e: function (e) {
          throw e;
        },
        f: F
      };
    }

    throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.");
  }

  var normalCompletion = true,
      didErr = false,
      err;
  return {
    s: function () {
      it = it.call(o);
    },
    n: function () {
      var step = it.next();
      normalCompletion = step.done;
      return step;
    },
    e: function (e) {
      didErr = true;
      err = e;
    },
    f: function () {
      try {
        if (!normalCompletion && it.return != null) it.return();
      } finally {
        if (didErr) throw err;
      }
    }
  };
}

const NAMESPACE = 'web-components';

var scopeId;
var contentRef;
var hostTagName;
var useNativeShadowDom = false;
var checkSlotFallbackVisibility = false;
var checkSlotRelocate = false;
var isSvgMode = false;
var queuePending = false;
var win = typeof window !== 'undefined' ? window : {};
var doc = win.document || {
  head: {}
};

var plt = {
  $flags$: 0,
  $resourcesUrl$: '',
  jmp: function jmp(h) {
    return h();
  },
  raf: function raf(h) {
    return requestAnimationFrame(h);
  },
  ael: function ael(el, eventName, listener, opts) {
    return el.addEventListener(eventName, listener, opts);
  },
  rel: function rel(el, eventName, listener, opts) {
    return el.removeEventListener(eventName, listener, opts);
  },
  ce: function ce(eventName, opts) {
    return new CustomEvent(eventName, opts);
  }
};

var promiseResolve = function promiseResolve(v) {
  return Promise.resolve(v);
};

var supportsConstructibleStylesheets = /*@__PURE__*/function () {
  try {
    new CSSStyleSheet();
    return typeof new CSSStyleSheet().replace === 'function';
  } catch (e) {}

  return false;
}() ;

var addHostEventListeners = function addHostEventListeners(elm, hostRef, listeners, attachParentListeners) {
  if (listeners) {

    listeners.map(function (_ref6) {
      var _ref7 = _slicedToArray(_ref6, 3),
          flags = _ref7[0],
          name = _ref7[1],
          method = _ref7[2];

      var target = getHostListenerTarget(elm, flags) ;
      var handler = hostListenerProxy(hostRef, method);
      var opts = hostListenerOpts(flags);
      plt.ael(target, name, handler, opts);
      (hostRef.$rmListeners$ = hostRef.$rmListeners$ || []).push(function () {
        return plt.rel(target, name, handler, opts);
      });
    });
  }
};

var hostListenerProxy = function hostListenerProxy(hostRef, methodName) {
  return function (ev) {
    try {
      {
        if (hostRef.$flags$ & 256
        /* isListenReady */
        ) {
          // instance is ready, let's call it's member method for this event
          hostRef.$lazyInstance$[methodName](ev);
        } else {
          (hostRef.$queuedListeners$ = hostRef.$queuedListeners$ || []).push([methodName, ev]);
        }
      }
    } catch (e) {
      consoleError(e);
    }
  };
};

var getHostListenerTarget = function getHostListenerTarget(elm, flags) {
  if (flags & 8
  /* TargetWindow */
  ) return win;
  return elm;
}; // prettier-ignore


var hostListenerOpts = function hostListenerOpts(flags) {
  return (flags & 2
  /* Capture */
  ) !== 0;
};
var HYDRATED_CSS = '{visibility:hidden}.hydrated{visibility:inherit}';

var createTime = function createTime(fnName) {

  {
    return function () {
      return;
    };
  }
};

var uniqueTime = function uniqueTime(key, measureText) {
  {
    return function () {
      return;
    };
  }
};

var rootAppliedStyles = new WeakMap();

var registerStyle = function registerStyle(scopeId, cssText, allowCS) {
  var style = styles.get(scopeId);

  if (supportsConstructibleStylesheets && allowCS) {
    style = style || new CSSStyleSheet();
    style.replace(cssText);
  } else {
    style = cssText;
  }

  styles.set(scopeId, style);
};

var addStyle = function addStyle(styleContainerNode, cmpMeta, mode, hostElm) {
  var scopeId = getScopeId(cmpMeta);
  var style = styles.get(scopeId);
  // so the fallback is to always use the document for the root node in those cases


  styleContainerNode = styleContainerNode.nodeType === 11
  /* DocumentFragment */
  ? styleContainerNode : doc;

  if (style) {
    if (typeof style === 'string') {
      styleContainerNode = styleContainerNode.head || styleContainerNode;
      var appliedStyles = rootAppliedStyles.get(styleContainerNode);
      var styleElm;

      if (!appliedStyles) {
        rootAppliedStyles.set(styleContainerNode, appliedStyles = new Set());
      }

      if (!appliedStyles.has(scopeId)) {
        {
          {
            styleElm = doc.createElement('style');
            styleElm.innerHTML = style;
          }

          styleContainerNode.insertBefore(styleElm, styleContainerNode.querySelector('link'));
        }

        if (appliedStyles) {
          appliedStyles.add(scopeId);
        }
      }
    } else if (!styleContainerNode.adoptedStyleSheets.includes(style)) {
      styleContainerNode.adoptedStyleSheets = [].concat(_toConsumableArray(styleContainerNode.adoptedStyleSheets), [style]);
    }
  }

  return scopeId;
};

var attachStyles = function attachStyles(hostRef) {
  var cmpMeta = hostRef.$cmpMeta$;
  var elm = hostRef.$hostElement$;
  var flags = cmpMeta.$flags$;
  var endAttachStyles = createTime();
  var scopeId = addStyle(elm.shadowRoot ? elm.shadowRoot : elm.getRootNode(), cmpMeta);

  if (flags & 10
  /* needsScopedEncapsulation */
  ) {
    // only required when we're NOT using native shadow dom (slot)
    // or this browser doesn't support native shadow dom
    // and this host element was NOT created with SSR
    // let's pick out the inner content for slot projection
    // create a node to represent where the original
    // content was first placed, which is useful later on
    // DOM WRITE!!
    elm['s-sc'] = scopeId;
    elm.classList.add(scopeId + '-h');
  }

  endAttachStyles();
};

var getScopeId = function getScopeId(cmp, mode) {
  return 'sc-' + (cmp.$tagName$);
};
/**
 * Default style mode id
 */

/**
 * Reusable empty obj/array
 * Don't add values to these!!
 */


var EMPTY_OBJ = {};
/**
 * Namespaces
 */

var SVG_NS = 'http://www.w3.org/2000/svg';
var HTML_NS = 'http://www.w3.org/1999/xhtml';

var isDef = function isDef(v) {
  return v != null;
};

var isComplexType = function isComplexType(o) {
  // https://jsperf.com/typeof-fn-object/5
  o = _typeof(o);
  return o === 'object' || o === 'function';
};
/**
 * Production h() function based on Preact by
 * Jason Miller (@developit)
 * Licensed under the MIT License
 * https://github.com/developit/preact/blob/master/LICENSE
 *
 * Modified for Stencil's compiler and vdom
 */
// const stack: any[] = [];
// export function h(nodeName: string | d.FunctionalComponent, vnodeData: d.PropsType, child?: d.ChildType): d.VNode;
// export function h(nodeName: string | d.FunctionalComponent, vnodeData: d.PropsType, ...children: d.ChildType[]): d.VNode;


var h = function h(nodeName, vnodeData) {
  var child = null;
  var key = null;
  var slotName = null;
  var simple = false;
  var lastSimple = false;
  var vNodeChildren = [];

  var walk = function walk(c) {
    for (var _i = 0; _i < c.length; _i++) {
      child = c[_i];

      if (Array.isArray(child)) {
        walk(child);
      } else if (child != null && typeof child !== 'boolean') {
        if (simple = typeof nodeName !== 'function' && !isComplexType(child)) {
          child = String(child);
        }

        if (simple && lastSimple) {
          // If the previous child was simple (string), we merge both
          vNodeChildren[vNodeChildren.length - 1].$text$ += child;
        } else {
          // Append a new vNode, if it's text, we create a text vNode
          vNodeChildren.push(simple ? newVNode(null, child) : child);
        }

        lastSimple = simple;
      }
    }
  };

  for (var _len = arguments.length, children = new Array(_len > 2 ? _len - 2 : 0), _key = 2; _key < _len; _key++) {
    children[_key - 2] = arguments[_key];
  }

  walk(children);

  if (vnodeData) {


    if (vnodeData.key) {
      key = vnodeData.key;
    }

    if (vnodeData.name) {
      slotName = vnodeData.name;
    }

    {
      var classData = vnodeData.className || vnodeData.class;

      if (classData) {
        vnodeData.class = _typeof(classData) !== 'object' ? classData : Object.keys(classData).filter(function (k) {
          return classData[k];
        }).join(' ');
      }
    }
  }

  if (typeof nodeName === 'function') {
    // nodeName is a functional component
    return nodeName(vnodeData === null ? {} : vnodeData, vNodeChildren, vdomFnUtils);
  }

  var vnode = newVNode(nodeName, null);
  vnode.$attrs$ = vnodeData;

  if (vNodeChildren.length > 0) {
    vnode.$children$ = vNodeChildren;
  }

  {
    vnode.$key$ = key;
  }

  {
    vnode.$name$ = slotName;
  }

  return vnode;
};

var newVNode = function newVNode(tag, text) {
  var vnode = {
    $flags$: 0,
    $tag$: tag,
    $text$: text,
    $elm$: null,
    $children$: null
  };

  {
    vnode.$attrs$ = null;
  }

  {
    vnode.$key$ = null;
  }

  {
    vnode.$name$ = null;
  }

  return vnode;
};

var Host = {};

var isHost = function isHost(node) {
  return node && node.$tag$ === Host;
};

var vdomFnUtils = {
  forEach: function forEach(children, cb) {
    return children.map(convertToPublic).forEach(cb);
  },
  map: function map(children, cb) {
    return children.map(convertToPublic).map(cb).map(convertToPrivate);
  }
};

var convertToPublic = function convertToPublic(node) {
  return {
    vattrs: node.$attrs$,
    vchildren: node.$children$,
    vkey: node.$key$,
    vname: node.$name$,
    vtag: node.$tag$,
    vtext: node.$text$
  };
};

var convertToPrivate = function convertToPrivate(node) {
  if (typeof node.vtag === 'function') {
    var vnodeData = Object.assign({}, node.vattrs);

    if (node.vkey) {
      vnodeData.key = node.vkey;
    }

    if (node.vname) {
      vnodeData.name = node.vname;
    }

    return h.apply(void 0, [node.vtag, vnodeData].concat(_toConsumableArray(node.vchildren || [])));
  }

  var vnode = newVNode(node.vtag, node.vtext);
  vnode.$attrs$ = node.vattrs;
  vnode.$children$ = node.vchildren;
  vnode.$key$ = node.vkey;
  vnode.$name$ = node.vname;
  return vnode;
};
/**
 * Production setAccessor() function based on Preact by
 * Jason Miller (@developit)
 * Licensed under the MIT License
 * https://github.com/developit/preact/blob/master/LICENSE
 *
 * Modified for Stencil's compiler and vdom
 */


var setAccessor = function setAccessor(elm, memberName, oldValue, newValue, isSvg, flags) {
  if (oldValue !== newValue) {
    var isProp = isMemberInElement(elm, memberName);
    var ln = memberName.toLowerCase();

    if (memberName === 'class') {
      var classList = elm.classList;
      var oldClasses = parseClassList(oldValue);
      var newClasses = parseClassList(newValue);
      classList.remove.apply(classList, _toConsumableArray(oldClasses.filter(function (c) {
        return c && !newClasses.includes(c);
      })));
      classList.add.apply(classList, _toConsumableArray(newClasses.filter(function (c) {
        return c && !oldClasses.includes(c);
      })));
    } else if (memberName === 'style') {
      // update style attribute, css properties and values
      {
        for (var prop in oldValue) {
          if (!newValue || newValue[prop] == null) {
            if (prop.includes('-')) {
              elm.style.removeProperty(prop);
            } else {
              elm.style[prop] = '';
            }
          }
        }
      }

      for (var _prop in newValue) {
        if (!oldValue || newValue[_prop] !== oldValue[_prop]) {
          if (_prop.includes('-')) {
            elm.style.setProperty(_prop, newValue[_prop]);
          } else {
            elm.style[_prop] = newValue[_prop];
          }
        }
      }
    } else if (memberName === 'key') ;else if (memberName === 'ref') {
      // minifier will clean this up
      if (newValue) {
        newValue(elm);
      }
    } else if ((!isProp ) && memberName[0] === 'o' && memberName[1] === 'n') {
      // Event Handlers
      // so if the member name starts with "on" and the 3rd characters is
      // a capital letter, and it's not already a member on the element,
      // then we're assuming it's an event listener
      if (memberName[2] === '-') {
        // on- prefixed events
        // allows to be explicit about the dom event to listen without any magic
        // under the hood:
        // <my-cmp on-click> // listens for "click"
        // <my-cmp on-Click> // listens for "Click"
        // <my-cmp on-ionChange> // listens for "ionChange"
        // <my-cmp on-EVENTS> // listens for "EVENTS"
        memberName = memberName.slice(3);
      } else if (isMemberInElement(win, ln)) {
        // standard event
        // the JSX attribute could have been "onMouseOver" and the
        // member name "onmouseover" is on the window's prototype
        // so let's add the listener "mouseover", which is all lowercased
        memberName = ln.slice(2);
      } else {
        // custom event
        // the JSX attribute could have been "onMyCustomEvent"
        // so let's trim off the "on" prefix and lowercase the first character
        // and add the listener "myCustomEvent"
        // except for the first character, we keep the event name case
        memberName = ln[2] + memberName.slice(3);
      }

      if (oldValue) {
        plt.rel(elm, memberName, oldValue, false);
      }

      if (newValue) {
        plt.ael(elm, memberName, newValue, false);
      }
    } else {
      // Set property if it exists and it's not a SVG
      var isComplex = isComplexType(newValue);

      if ((isProp || isComplex && newValue !== null) && !isSvg) {
        try {
          if (!elm.tagName.includes('-')) {
            var n = newValue == null ? '' : newValue; // Workaround for Safari, moving the <input> caret when re-assigning the same valued

            if (memberName === 'list') {
              isProp = false; // tslint:disable-next-line: triple-equals
            } else if (oldValue == null || elm[memberName] != n) {
              elm[memberName] = n;
            }
          } else {
            elm[memberName] = newValue;
          }
        } catch (e) {}
      }

      if (newValue == null || newValue === false) {
        if (newValue !== false || elm.getAttribute(memberName) === '') {
          {
            elm.removeAttribute(memberName);
          }
        }
      } else if ((!isProp || flags & 4
      /* isHost */
      || isSvg) && !isComplex) {
        newValue = newValue === true ? '' : newValue;

        {
          elm.setAttribute(memberName, newValue);
        }
      }
    }
  }
};

var parseClassListRegex = /\s/;

var parseClassList = function parseClassList(value) {
  return !value ? [] : value.split(parseClassListRegex);
};

var updateElement = function updateElement(oldVnode, newVnode, isSvgMode, memberName) {
  // if the element passed in is a shadow root, which is a document fragment
  // then we want to be adding attrs/props to the shadow root's "host" element
  // if it's not a shadow root, then we add attrs/props to the same element
  var elm = newVnode.$elm$.nodeType === 11
  /* DocumentFragment */
  && newVnode.$elm$.host ? newVnode.$elm$.host : newVnode.$elm$;
  var oldVnodeAttrs = oldVnode && oldVnode.$attrs$ || EMPTY_OBJ;
  var newVnodeAttrs = newVnode.$attrs$ || EMPTY_OBJ;

  {
    // remove attributes no longer present on the vnode by setting them to undefined
    for (memberName in oldVnodeAttrs) {
      if (!(memberName in newVnodeAttrs)) {
        setAccessor(elm, memberName, oldVnodeAttrs[memberName], undefined, isSvgMode, newVnode.$flags$);
      }
    }
  } // add new & update changed attributes


  for (memberName in newVnodeAttrs) {
    setAccessor(elm, memberName, oldVnodeAttrs[memberName], newVnodeAttrs[memberName], isSvgMode, newVnode.$flags$);
  }
};

var createElm = function createElm(oldParentVNode, newParentVNode, childIndex, parentElm) {
  // tslint:disable-next-line: prefer-const
  var newVNode = newParentVNode.$children$[childIndex];
  var i = 0;
  var elm;
  var childNode;
  var oldVNode;

  if (!useNativeShadowDom) {
    // remember for later we need to check to relocate nodes
    checkSlotRelocate = true;

    if (newVNode.$tag$ === 'slot') {
      if (scopeId) {
        // scoped css needs to add its scoped id to the parent element
        parentElm.classList.add(scopeId + '-s');
      }

      newVNode.$flags$ |= newVNode.$children$ ? // slot element has fallback content
      2
      /* isSlotFallback */
      : // slot element does not have fallback content
      1
      /* isSlotReference */
      ;
    }
  }

  if (newVNode.$text$ !== null) {
    // create text node
    elm = newVNode.$elm$ = doc.createTextNode(newVNode.$text$);
  } else if (newVNode.$flags$ & 1
  /* isSlotReference */
  ) {
    // create a slot reference node
    elm = newVNode.$elm$ = doc.createTextNode('');
  } else {
    if (!isSvgMode) {
      isSvgMode = newVNode.$tag$ === 'svg';
    } // create element


    elm = newVNode.$elm$ = doc.createElementNS(isSvgMode ? SVG_NS : HTML_NS, newVNode.$flags$ & 2
    /* isSlotFallback */
    ? 'slot-fb' : newVNode.$tag$) ;

    if (isSvgMode && newVNode.$tag$ === 'foreignObject') {
      isSvgMode = false;
    } // add css classes, attrs, props, listeners, etc.


    {
      updateElement(null, newVNode, isSvgMode);
    }

    if (isDef(scopeId) && elm['s-si'] !== scopeId) {
      // if there is a scopeId and this is the initial render
      // then let's add the scopeId as a css class
      elm.classList.add(elm['s-si'] = scopeId);
    }

    if (newVNode.$children$) {
      for (i = 0; i < newVNode.$children$.length; ++i) {
        // create the node
        childNode = createElm(oldParentVNode, newVNode, i, elm); // return node could have been null

        if (childNode) {
          // append our new node
          elm.appendChild(childNode);
        }
      }
    }

    {
      if (newVNode.$tag$ === 'svg') {
        // Only reset the SVG context when we're exiting <svg> element
        isSvgMode = false;
      } else if (elm.tagName === 'foreignObject') {
        // Reenter SVG context when we're exiting <foreignObject> element
        isSvgMode = true;
      }
    }
  }

  {
    elm['s-hn'] = hostTagName;

    if (newVNode.$flags$ & (2
    /* isSlotFallback */
    | 1
    /* isSlotReference */
    )) {
      // remember the content reference comment
      elm['s-sr'] = true; // remember the content reference comment

      elm['s-cr'] = contentRef; // remember the slot name, or empty string for default slot

      elm['s-sn'] = newVNode.$name$ || ''; // check if we've got an old vnode for this slot

      oldVNode = oldParentVNode && oldParentVNode.$children$ && oldParentVNode.$children$[childIndex];

      if (oldVNode && oldVNode.$tag$ === newVNode.$tag$ && oldParentVNode.$elm$) {
        // we've got an old slot vnode and the wrapper is being replaced
        // so let's move the old slot content back to it's original location
        putBackInOriginalLocation(oldParentVNode.$elm$, false);
      }
    }
  }

  return elm;
};

var putBackInOriginalLocation = function putBackInOriginalLocation(parentElm, recursive) {
  plt.$flags$ |= 1
  /* isTmpDisconnected */
  ;
  var oldSlotChildNodes = parentElm.childNodes;

  for (var _i2 = oldSlotChildNodes.length - 1; _i2 >= 0; _i2--) {
    var childNode = oldSlotChildNodes[_i2];

    if (childNode['s-hn'] !== hostTagName && childNode['s-ol']) {
      // // this child node in the old element is from another component
      // // remove this node from the old slot's parent
      // childNode.remove();
      // and relocate it back to it's original location
      parentReferenceNode(childNode).insertBefore(childNode, referenceNode(childNode)); // remove the old original location comment entirely
      // later on the patch function will know what to do
      // and move this to the correct spot in need be

      childNode['s-ol'].remove();
      childNode['s-ol'] = undefined;
      checkSlotRelocate = true;
    }

    if (recursive) {
      putBackInOriginalLocation(childNode, recursive);
    }
  }

  plt.$flags$ &= ~1
  /* isTmpDisconnected */
  ;
};

var addVnodes = function addVnodes(parentElm, before, parentVNode, vnodes, startIdx, endIdx) {
  var containerElm = parentElm['s-cr'] && parentElm['s-cr'].parentNode || parentElm;
  var childNode;

  if (containerElm.shadowRoot && containerElm.tagName === hostTagName) {
    containerElm = containerElm.shadowRoot;
  }

  for (; startIdx <= endIdx; ++startIdx) {
    if (vnodes[startIdx]) {
      childNode = createElm(null, parentVNode, startIdx, parentElm);

      if (childNode) {
        vnodes[startIdx].$elm$ = childNode;
        containerElm.insertBefore(childNode, referenceNode(before) );
      }
    }
  }
};

var removeVnodes = function removeVnodes(vnodes, startIdx, endIdx, vnode, elm) {
  for (; startIdx <= endIdx; ++startIdx) {
    if (vnode = vnodes[startIdx]) {
      elm = vnode.$elm$;
      callNodeRefs(vnode);

      {
        // we're removing this element
        // so it's possible we need to show slot fallback content now
        checkSlotFallbackVisibility = true;

        if (elm['s-ol']) {
          // remove the original location comment
          elm['s-ol'].remove();
        } else {
          // it's possible that child nodes of the node
          // that's being removed are slot nodes
          putBackInOriginalLocation(elm, true);
        }
      } // remove the vnode's element from the dom


      elm.remove();
    }
  }
};

var updateChildren = function updateChildren(parentElm, oldCh, newVNode, newCh) {
  var oldStartIdx = 0;
  var newStartIdx = 0;
  var idxInOld = 0;
  var i = 0;
  var oldEndIdx = oldCh.length - 1;
  var oldStartVnode = oldCh[0];
  var oldEndVnode = oldCh[oldEndIdx];
  var newEndIdx = newCh.length - 1;
  var newStartVnode = newCh[0];
  var newEndVnode = newCh[newEndIdx];
  var node;
  var elmToMove;

  while (oldStartIdx <= oldEndIdx && newStartIdx <= newEndIdx) {
    if (oldStartVnode == null) {
      // Vnode might have been moved left
      oldStartVnode = oldCh[++oldStartIdx];
    } else if (oldEndVnode == null) {
      oldEndVnode = oldCh[--oldEndIdx];
    } else if (newStartVnode == null) {
      newStartVnode = newCh[++newStartIdx];
    } else if (newEndVnode == null) {
      newEndVnode = newCh[--newEndIdx];
    } else if (isSameVnode(oldStartVnode, newStartVnode)) {
      patch(oldStartVnode, newStartVnode);
      oldStartVnode = oldCh[++oldStartIdx];
      newStartVnode = newCh[++newStartIdx];
    } else if (isSameVnode(oldEndVnode, newEndVnode)) {
      patch(oldEndVnode, newEndVnode);
      oldEndVnode = oldCh[--oldEndIdx];
      newEndVnode = newCh[--newEndIdx];
    } else if (isSameVnode(oldStartVnode, newEndVnode)) {
      // Vnode moved right
      if ((oldStartVnode.$tag$ === 'slot' || newEndVnode.$tag$ === 'slot')) {
        putBackInOriginalLocation(oldStartVnode.$elm$.parentNode, false);
      }

      patch(oldStartVnode, newEndVnode);
      parentElm.insertBefore(oldStartVnode.$elm$, oldEndVnode.$elm$.nextSibling);
      oldStartVnode = oldCh[++oldStartIdx];
      newEndVnode = newCh[--newEndIdx];
    } else if (isSameVnode(oldEndVnode, newStartVnode)) {
      // Vnode moved left
      if ((oldStartVnode.$tag$ === 'slot' || newEndVnode.$tag$ === 'slot')) {
        putBackInOriginalLocation(oldEndVnode.$elm$.parentNode, false);
      }

      patch(oldEndVnode, newStartVnode);
      parentElm.insertBefore(oldEndVnode.$elm$, oldStartVnode.$elm$);
      oldEndVnode = oldCh[--oldEndIdx];
      newStartVnode = newCh[++newStartIdx];
    } else {
      // createKeyToOldIdx
      idxInOld = -1;

      {
        for (i = oldStartIdx; i <= oldEndIdx; ++i) {
          if (oldCh[i] && oldCh[i].$key$ !== null && oldCh[i].$key$ === newStartVnode.$key$) {
            idxInOld = i;
            break;
          }
        }
      }

      if (idxInOld >= 0) {
        elmToMove = oldCh[idxInOld];

        if (elmToMove.$tag$ !== newStartVnode.$tag$) {
          node = createElm(oldCh && oldCh[newStartIdx], newVNode, idxInOld, parentElm);
        } else {
          patch(elmToMove, newStartVnode);
          oldCh[idxInOld] = undefined;
          node = elmToMove.$elm$;
        }

        newStartVnode = newCh[++newStartIdx];
      } else {
        // new element
        node = createElm(oldCh && oldCh[newStartIdx], newVNode, newStartIdx, parentElm);
        newStartVnode = newCh[++newStartIdx];
      }

      if (node) {
        {
          parentReferenceNode(oldStartVnode.$elm$).insertBefore(node, referenceNode(oldStartVnode.$elm$));
        }
      }
    }
  }

  if (oldStartIdx > oldEndIdx) {
    addVnodes(parentElm, newCh[newEndIdx + 1] == null ? null : newCh[newEndIdx + 1].$elm$, newVNode, newCh, newStartIdx, newEndIdx);
  } else if (newStartIdx > newEndIdx) {
    removeVnodes(oldCh, oldStartIdx, oldEndIdx);
  }
};

var isSameVnode = function isSameVnode(vnode1, vnode2) {
  // compare if two vnode to see if they're "technically" the same
  // need to have the same element tag, and same key to be the same
  if (vnode1.$tag$ === vnode2.$tag$) {
    if (vnode1.$tag$ === 'slot') {
      return vnode1.$name$ === vnode2.$name$;
    }

    {
      return vnode1.$key$ === vnode2.$key$;
    }
  }

  return false;
};

var referenceNode = function referenceNode(node) {
  // this node was relocated to a new location in the dom
  // because of some other component's slot
  // but we still have an html comment in place of where
  // it's original location was according to it's original vdom
  return node && node['s-ol'] || node;
};

var parentReferenceNode = function parentReferenceNode(node) {
  return (node['s-ol'] ? node['s-ol'] : node).parentNode;
};

var patch = function patch(oldVNode, newVNode) {
  var elm = newVNode.$elm$ = oldVNode.$elm$;
  var oldChildren = oldVNode.$children$;
  var newChildren = newVNode.$children$;
  var tag = newVNode.$tag$;
  var text = newVNode.$text$;
  var defaultHolder;

  if (text === null) {
    {
      // test if we're rendering an svg element, or still rendering nodes inside of one
      // only add this to the when the compiler sees we're using an svg somewhere
      isSvgMode = tag === 'svg' ? true : tag === 'foreignObject' ? false : isSvgMode;
    } // element node


    {
      if (tag === 'slot') ;else {
        // either this is the first render of an element OR it's an update
        // AND we already know it's possible it could have changed
        // this updates the element's css classes, attrs, props, listeners, etc.
        updateElement(oldVNode, newVNode, isSvgMode);
      }
    }

    if (oldChildren !== null && newChildren !== null) {
      // looks like there's child vnodes for both the old and new vnodes
      updateChildren(elm, oldChildren, newVNode, newChildren);
    } else if (newChildren !== null) {
      // no old child vnodes, but there are new child vnodes to add
      if (oldVNode.$text$ !== null) {
        // the old vnode was text, so be sure to clear it out
        elm.textContent = '';
      } // add the new vnode children


      addVnodes(elm, null, newVNode, newChildren, 0, newChildren.length - 1);
    } else if (oldChildren !== null) {
      // no new child vnodes, but there are old child vnodes to remove
      removeVnodes(oldChildren, 0, oldChildren.length - 1);
    }

    if (isSvgMode && tag === 'svg') {
      isSvgMode = false;
    }
  } else if ((defaultHolder = elm['s-cr'])) {
    // this element has slotted content
    defaultHolder.parentNode.textContent = text;
  } else if (oldVNode.$text$ !== text) {
    // update the text content for the text only vnode
    // and also only if the text is different than before
    elm.data = text;
  }
};

var updateFallbackSlotVisibility = function updateFallbackSlotVisibility(elm) {
  // tslint:disable-next-line: prefer-const
  var childNodes = elm.childNodes;
  var childNode;
  var i;
  var ilen;
  var j;
  var slotNameAttr;
  var nodeType;

  for (i = 0, ilen = childNodes.length; i < ilen; i++) {
    childNode = childNodes[i];

    if (childNode.nodeType === 1
    /* ElementNode */
    ) {
      if (childNode['s-sr']) {
        // this is a slot fallback node
        // get the slot name for this slot reference node
        slotNameAttr = childNode['s-sn']; // by default always show a fallback slot node
        // then hide it if there are other slots in the light dom

        childNode.hidden = false;

        for (j = 0; j < ilen; j++) {
          nodeType = childNodes[j].nodeType;

          if (childNodes[j]['s-hn'] !== childNode['s-hn'] || slotNameAttr !== '') {
            // this sibling node is from a different component OR is a named fallback slot node
            if (nodeType === 1
            /* ElementNode */
            && slotNameAttr === childNodes[j].getAttribute('slot')) {
              childNode.hidden = true;
              break;
            }
          } else {
            // this is a default fallback slot node
            // any element or text node (with content)
            // should hide the default fallback slot node
            if (nodeType === 1
            /* ElementNode */
            || nodeType === 3
            /* TextNode */
            && childNodes[j].textContent.trim() !== '') {
              childNode.hidden = true;
              break;
            }
          }
        }
      } // keep drilling down


      updateFallbackSlotVisibility(childNode);
    }
  }
};

var relocateNodes = [];

var relocateSlotContent = function relocateSlotContent(elm) {
  // tslint:disable-next-line: prefer-const
  var childNode;
  var node;
  var hostContentNodes;
  var slotNameAttr;
  var relocateNodeData;
  var j;
  var i = 0;
  var childNodes = elm.childNodes;
  var ilen = childNodes.length;

  for (; i < ilen; i++) {
    childNode = childNodes[i];

    if (childNode['s-sr'] && (node = childNode['s-cr']) && node.parentNode) {
      // first got the content reference comment node
      // then we got it's parent, which is where all the host content is in now
      hostContentNodes = node.parentNode.childNodes;
      slotNameAttr = childNode['s-sn'];

      for (j = hostContentNodes.length - 1; j >= 0; j--) {
        node = hostContentNodes[j];

        if (!node['s-cn'] && !node['s-nr'] && node['s-hn'] !== childNode['s-hn']) {
          // let's do some relocating to its new home
          // but never relocate a content reference node
          // that is suppose to always represent the original content location
          if (isNodeLocatedInSlot(node, slotNameAttr)) {
            // it's possible we've already decided to relocate this node
            relocateNodeData = relocateNodes.find(function (r) {
              return r.$nodeToRelocate$ === node;
            }); // made some changes to slots
            // let's make sure we also double check
            // fallbacks are correctly hidden or shown

            checkSlotFallbackVisibility = true;
            node['s-sn'] = node['s-sn'] || slotNameAttr;

            if (relocateNodeData) {
              // previously we never found a slot home for this node
              // but turns out we did, so let's remember it now
              relocateNodeData.$slotRefNode$ = childNode;
            } else {
              // add to our list of nodes to relocate
              relocateNodes.push({
                $slotRefNode$: childNode,
                $nodeToRelocate$: node
              });
            }

            if (node['s-sr']) {
              relocateNodes.map(function (relocateNode) {
                if (isNodeLocatedInSlot(relocateNode.$nodeToRelocate$, node['s-sn'])) {
                  relocateNodeData = relocateNodes.find(function (r) {
                    return r.$nodeToRelocate$ === node;
                  });

                  if (relocateNodeData && !relocateNode.$slotRefNode$) {
                    relocateNode.$slotRefNode$ = relocateNodeData.$slotRefNode$;
                  }
                }
              });
            }
          } else if (!relocateNodes.some(function (r) {
            return r.$nodeToRelocate$ === node;
          })) {
            // so far this element does not have a slot home, not setting slotRefNode on purpose
            // if we never find a home for this element then we'll need to hide it
            relocateNodes.push({
              $nodeToRelocate$: node
            });
          }
        }
      }
    }

    if (childNode.nodeType === 1
    /* ElementNode */
    ) {
      relocateSlotContent(childNode);
    }
  }
};

var isNodeLocatedInSlot = function isNodeLocatedInSlot(nodeToRelocate, slotNameAttr) {
  if (nodeToRelocate.nodeType === 1
  /* ElementNode */
  ) {
    if (nodeToRelocate.getAttribute('slot') === null && slotNameAttr === '') {
      return true;
    }

    if (nodeToRelocate.getAttribute('slot') === slotNameAttr) {
      return true;
    }

    return false;
  }

  if (nodeToRelocate['s-sn'] === slotNameAttr) {
    return true;
  }

  return slotNameAttr === '';
};

var callNodeRefs = function callNodeRefs(vNode) {
  {
    vNode.$attrs$ && vNode.$attrs$.ref && vNode.$attrs$.ref(null);
    vNode.$children$ && vNode.$children$.map(callNodeRefs);
  }
};

var renderVdom = function renderVdom(hostRef, renderFnResults) {
  var hostElm = hostRef.$hostElement$;
  var cmpMeta = hostRef.$cmpMeta$;
  var oldVNode = hostRef.$vnode$ || newVNode(null, null);
  var rootVnode = isHost(renderFnResults) ? renderFnResults : h(null, null, renderFnResults);
  hostTagName = hostElm.tagName; // <Host> runtime check

  if (cmpMeta.$attrsToReflect$) {
    rootVnode.$attrs$ = rootVnode.$attrs$ || {};
    cmpMeta.$attrsToReflect$.map(function (_ref9) {
      var _ref10 = _slicedToArray(_ref9, 2),
          propName = _ref10[0],
          attribute = _ref10[1];

      return rootVnode.$attrs$[attribute] = hostElm[propName];
    });
  }

  rootVnode.$tag$ = null;
  rootVnode.$flags$ |= 4
  /* isHost */
  ;
  hostRef.$vnode$ = rootVnode;
  rootVnode.$elm$ = oldVNode.$elm$ = hostElm.shadowRoot || hostElm ;

  {
    scopeId = hostElm['s-sc'];
  }

  {
    contentRef = hostElm['s-cr'];
    useNativeShadowDom = (cmpMeta.$flags$ & 1
    /* shadowDomEncapsulation */
    ) !== 0; // always reset

    checkSlotFallbackVisibility = false;
  } // synchronous patch


  patch(oldVNode, rootVnode);

  {
    // while we're moving nodes around existing nodes, temporarily disable
    // the disconnectCallback from working
    plt.$flags$ |= 1
    /* isTmpDisconnected */
    ;

    if (checkSlotRelocate) {
      relocateSlotContent(rootVnode.$elm$);
      var relocateData;
      var nodeToRelocate;
      var orgLocationNode;
      var parentNodeRef;
      var insertBeforeNode;
      var refNode;
      var _i3 = 0;

      for (; _i3 < relocateNodes.length; _i3++) {
        relocateData = relocateNodes[_i3];
        nodeToRelocate = relocateData.$nodeToRelocate$;

        if (!nodeToRelocate['s-ol']) {
          // add a reference node marking this node's original location
          // keep a reference to this node for later lookups
          orgLocationNode = doc.createTextNode('');
          orgLocationNode['s-nr'] = nodeToRelocate;
          nodeToRelocate.parentNode.insertBefore(nodeToRelocate['s-ol'] = orgLocationNode, nodeToRelocate);
        }
      }

      for (_i3 = 0; _i3 < relocateNodes.length; _i3++) {
        relocateData = relocateNodes[_i3];
        nodeToRelocate = relocateData.$nodeToRelocate$;

        if (relocateData.$slotRefNode$) {
          // by default we're just going to insert it directly
          // after the slot reference node
          parentNodeRef = relocateData.$slotRefNode$.parentNode;
          insertBeforeNode = relocateData.$slotRefNode$.nextSibling;
          orgLocationNode = nodeToRelocate['s-ol'];

          while (orgLocationNode = orgLocationNode.previousSibling) {
            refNode = orgLocationNode['s-nr'];

            if (refNode && refNode['s-sn'] === nodeToRelocate['s-sn'] && parentNodeRef === refNode.parentNode) {
              refNode = refNode.nextSibling;

              if (!refNode || !refNode['s-nr']) {
                insertBeforeNode = refNode;
                break;
              }
            }
          }

          if (!insertBeforeNode && parentNodeRef !== nodeToRelocate.parentNode || nodeToRelocate.nextSibling !== insertBeforeNode) {
            // we've checked that it's worth while to relocate
            // since that the node to relocate
            // has a different next sibling or parent relocated
            if (nodeToRelocate !== insertBeforeNode) {
              if (!nodeToRelocate['s-hn'] && nodeToRelocate['s-ol']) {
                // probably a component in the index.html that doesn't have it's hostname set
                nodeToRelocate['s-hn'] = nodeToRelocate['s-ol'].parentNode.nodeName;
              } // add it back to the dom but in its new home


              parentNodeRef.insertBefore(nodeToRelocate, insertBeforeNode);
            }
          }
        } else {
          // this node doesn't have a slot home to go to, so let's hide it
          if (nodeToRelocate.nodeType === 1
          /* ElementNode */
          ) {
            nodeToRelocate.hidden = true;
          }
        }
      }
    }

    if (checkSlotFallbackVisibility) {
      updateFallbackSlotVisibility(rootVnode.$elm$);
    } // done moving nodes around
    // allow the disconnect callback to work again


    plt.$flags$ &= ~1
    /* isTmpDisconnected */
    ; // always reset

    relocateNodes.length = 0;
  }
}; // slot comment debug nodes only created with the `--debug` flag

var getElement = function getElement(ref) {
  return getHostRef(ref).$hostElement$ ;
};

var createEvent = function createEvent(ref, name, flags) {
  var elm = getElement(ref);
  return {
    emit: function emit(detail) {

      return emitEvent(elm, name, {
        bubbles: !!(flags & 4
        /* Bubbles */
        ),
        composed: !!(flags & 2
        /* Composed */
        ),
        cancelable: !!(flags & 1
        /* Cancellable */
        ),
        detail: detail
      });
    }
  };
};
/**
 * Helper function to create & dispatch a custom Event on a provided target
 * @param elm the target of the Event
 * @param name the name to give the custom Event
 * @param opts options for configuring a custom Event
 * @returns the custom Event
 */


var emitEvent = function emitEvent(elm, name, opts) {
  var ev = plt.ce(name, opts);
  elm.dispatchEvent(ev);
  return ev;
};

var attachToAncestor = function attachToAncestor(hostRef, ancestorComponent) {
  if (ancestorComponent && !hostRef.$onRenderResolve$ && ancestorComponent['s-p']) {
    ancestorComponent['s-p'].push(new Promise(function (r) {
      return hostRef.$onRenderResolve$ = r;
    }));
  }
};

var scheduleUpdate = function scheduleUpdate(hostRef, isInitialLoad) {
  {
    hostRef.$flags$ |= 16
    /* isQueuedForUpdate */
    ;
  }

  if (hostRef.$flags$ & 4
  /* isWaitingForChildren */
  ) {
    hostRef.$flags$ |= 512
    /* needsRerender */
    ;
    return;
  }

  attachToAncestor(hostRef, hostRef.$ancestorComponent$); // there is no ancestor component or the ancestor component
  // has already fired off its lifecycle update then
  // fire off the initial update

  var dispatch = function dispatch() {
    return dispatchHooks(hostRef, isInitialLoad);
  };

  return writeTask(dispatch) ;
};

var dispatchHooks = function dispatchHooks(hostRef, isInitialLoad) {
  var endSchedule = createTime();
  var instance = hostRef.$lazyInstance$ ;
  var promise;

  if (isInitialLoad) {
    {
      hostRef.$flags$ |= 256
      /* isListenReady */
      ;

      if (hostRef.$queuedListeners$) {
        hostRef.$queuedListeners$.map(function (_ref11) {
          var _ref12 = _slicedToArray(_ref11, 2),
              methodName = _ref12[0],
              event = _ref12[1];

          return safeCall(instance, methodName, event);
        });
        hostRef.$queuedListeners$ = null;
      }
    }

    {
      promise = safeCall(instance, 'componentWillLoad');
    }
  }

  endSchedule();
  return then(promise, function () {
    return updateComponent(hostRef, instance, isInitialLoad);
  });
};

var updateComponent = /*#__PURE__*/function () {
  var _ref13 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee(hostRef, instance, isInitialLoad) {
    var elm, endUpdate, rc, endRender, childrenPromises, postUpdate;
    return regeneratorRuntime.wrap(function _callee$(_context) {
      while (1) {
        switch (_context.prev = _context.next) {
          case 0:
            // updateComponent
            elm = hostRef.$hostElement$;
            endUpdate = createTime();
            rc = elm['s-rc'];

            if (isInitialLoad) {
              // DOM WRITE!
              attachStyles(hostRef);
            }

            endRender = createTime();

            {
              _context.next = 11;
              break;
            }

          case 9:
            _context.next = 12;
            break;

          case 11:
            callRender(hostRef, instance);

          case 12:

            if (rc) {
              // ok, so turns out there are some child host elements
              // waiting on this parent element to load
              // let's fire off all update callbacks waiting
              rc.map(function (cb) {
                return cb();
              });
              elm['s-rc'] = undefined;
            }

            endRender();
            endUpdate();

            {
              childrenPromises = elm['s-p'];

              postUpdate = function postUpdate() {
                return postUpdateComponent(hostRef);
              };

              if (childrenPromises.length === 0) {
                postUpdate();
              } else {
                Promise.all(childrenPromises).then(postUpdate);
                hostRef.$flags$ |= 4
                /* isWaitingForChildren */
                ;
                childrenPromises.length = 0;
              }
            }

          case 19:
          case "end":
            return _context.stop();
        }
      }
    }, _callee);
  }));

  return function updateComponent(_x, _x2, _x3) {
    return _ref13.apply(this, arguments);
  };
}();

var callRender = function callRender(hostRef, instance, elm) {

  try {
    instance = instance.render() ;

    {
      hostRef.$flags$ &= ~16
      /* isQueuedForUpdate */
      ;
    }

    {
      hostRef.$flags$ |= 2
      /* hasRendered */
      ;
    }

    {
      {
        // looks like we've got child nodes to render into this host element
        // or we need to update the css class/attrs on the host element
        // DOM WRITE!
        {
          renderVdom(hostRef, instance);
        }
      }
    }
  } catch (e) {
    consoleError(e, hostRef.$hostElement$);
  }
  return null;
};

var postUpdateComponent = function postUpdateComponent(hostRef) {
  var elm = hostRef.$hostElement$;
  var endPostUpdate = createTime();
  var instance = hostRef.$lazyInstance$ ;
  var ancestorComponent = hostRef.$ancestorComponent$;

  {

    safeCall(instance, 'componentDidRender');
  }

  if (!(hostRef.$flags$ & 64
  /* hasLoadedComponent */
  )) {
    hostRef.$flags$ |= 64
    /* hasLoadedComponent */
    ;

    {
      // DOM WRITE!
      addHydratedFlag(elm);
    }

    {

      safeCall(instance, 'componentDidLoad');
    }
    endPostUpdate();

    {
      hostRef.$onReadyResolve$(elm);

      if (!ancestorComponent) {
        appDidLoad();
      }
    }
  } else {
    endPostUpdate();
  }

  {
    hostRef.$onInstanceResolve$(elm);
  } // load events fire from bottom to top
  // the deepest elements load first then bubbles up


  {
    if (hostRef.$onRenderResolve$) {
      hostRef.$onRenderResolve$();
      hostRef.$onRenderResolve$ = undefined;
    }

    if (hostRef.$flags$ & 512
    /* needsRerender */
    ) {
      nextTick(function () {
        return scheduleUpdate(hostRef, false);
      });
    }

    hostRef.$flags$ &= ~(4
    /* isWaitingForChildren */
    | 512
    /* needsRerender */
    );
  } // ( •_•)
  // ( •_•)>⌐■-■
  // (⌐■_■)

};

var appDidLoad = function appDidLoad(who) {
  // on appload
  // we have finish the first big initial render
  {
    addHydratedFlag(doc.documentElement);
  }

  nextTick(function () {
    return emitEvent(win, 'appload', {
      detail: {
        namespace: NAMESPACE
      }
    });
  });
};

var safeCall = function safeCall(instance, method, arg) {
  if (instance && instance[method]) {
    try {
      return instance[method](arg);
    } catch (e) {
      consoleError(e);
    }
  }

  return undefined;
};

var then = function then(promise, thenFn) {
  return promise && promise.then ? promise.then(thenFn) : thenFn();
};

var addHydratedFlag = function addHydratedFlag(elm) {
  return elm.classList.add('hydrated') ;
};

var parsePropertyValue = function parsePropertyValue(propValue, propType) {
  // ensure this value is of the correct prop type
  if (propValue != null && !isComplexType(propValue)) {
    if (propType & 4
    /* Boolean */
    ) {
      // per the HTML spec, any string value means it is a boolean true value
      // but we'll cheat here and say that the string "false" is the boolean false
      return propValue === 'false' ? false : propValue === '' || !!propValue;
    }

    if (propType & 2
    /* Number */
    ) {
      // force it to be a number
      return parseFloat(propValue);
    }

    if (propType & 1
    /* String */
    ) {
      // could have been passed as a number or boolean
      // but we still want it as a string
      return String(propValue);
    } // redundant return here for better minification


    return propValue;
  } // not sure exactly what type we want
  // so no need to change to a different type


  return propValue;
};

var getValue = function getValue(ref, propName) {
  return getHostRef(ref).$instanceValues$.get(propName);
};

var setValue = function setValue(ref, propName, newVal, cmpMeta) {
  // check our new property value against our internal value
  var hostRef = getHostRef(ref);
  var elm = hostRef.$hostElement$ ;
  var oldVal = hostRef.$instanceValues$.get(propName);
  var flags = hostRef.$flags$;
  var instance = hostRef.$lazyInstance$ ;
  newVal = parsePropertyValue(newVal, cmpMeta.$members$[propName][0]);

  if ((!(flags & 8
  /* isConstructingInstance */
  ) || oldVal === undefined) && newVal !== oldVal) {
    // gadzooks! the property's value has changed!!
    // set our new value!
    hostRef.$instanceValues$.set(propName, newVal);

    if (instance) {
      // get an array of method names of watch functions to call
      if (cmpMeta.$watchers$ && flags & 128
      /* isWatchReady */
      ) {
        var watchMethods = cmpMeta.$watchers$[propName];

        if (watchMethods) {
          // this instance is watching for when this property changed
          watchMethods.map(function (watchMethodName) {
            try {
              // fire off each of the watch methods that are watching this property
              instance[watchMethodName](newVal, oldVal, propName);
            } catch (e) {
              consoleError(e, elm);
            }
          });
        }
      }

      if ((flags & (2
      /* hasRendered */
      | 16
      /* isQueuedForUpdate */
      )) === 2
      /* hasRendered */
      ) {
        // but only if we've already rendered, otherwise just chill out
        // queue that we need to do an update, but don't worry about queuing
        // up millions cuz this function ensures it only runs once


        scheduleUpdate(hostRef, false);
      }
    }
  }
};

var proxyComponent = function proxyComponent(Cstr, cmpMeta, flags) {
  if (cmpMeta.$members$) {
    if (Cstr.watchers) {
      cmpMeta.$watchers$ = Cstr.watchers;
    } // It's better to have a const than two Object.entries()


    var members = Object.entries(cmpMeta.$members$);
    var prototype = Cstr.prototype;
    members.map(function (_ref14) {
      var _ref15 = _slicedToArray(_ref14, 2),
          memberName = _ref15[0],
          _ref15$ = _slicedToArray(_ref15[1], 1),
          memberFlags = _ref15$[0];

      if ((memberFlags & 31
      /* Prop */
      || (flags & 2
      /* proxyState */
      ) && memberFlags & 32
      /* State */
      )) {
        // proxyComponent - prop
        Object.defineProperty(prototype, memberName, {
          get: function get() {
            // proxyComponent, get value
            return getValue(this, memberName);
          },
          set: function set(newValue) {


            setValue(this, memberName, newValue, cmpMeta);
          },
          configurable: true,
          enumerable: true
        });
      } else if (flags & 1
      /* isElementConstructor */
      && memberFlags & 64
      /* Method */
      ) {
        // proxyComponent - method
        Object.defineProperty(prototype, memberName, {
          value: function value() {
            for (var _len2 = arguments.length, args = new Array(_len2), _key2 = 0; _key2 < _len2; _key2++) {
              args[_key2] = arguments[_key2];
            }

            var ref = getHostRef(this);
            return ref.$onInstancePromise$.then(function () {
              var _ref$$lazyInstance$;

              return (_ref$$lazyInstance$ = ref.$lazyInstance$)[memberName].apply(_ref$$lazyInstance$, args);
            });
          }
        });
      }
    });

    if ((flags & 1
    /* isElementConstructor */
    )) {
      var attrNameToPropName = new Map();

      prototype.attributeChangedCallback = function (attrName, _oldValue, newValue) {
        var _this = this;

        plt.jmp(function () {
          var propName = attrNameToPropName.get(attrName); //  In a webcomponent lifecyle the attributeChangedCallback runs prior to connectedCallback
          //  in the case where an attribute was set inline.
          //  ```html
          //    <my-component some-attribute="some-value"></my-component>
          //  ```
          //
          //  There is an edge case where a developer sets the attribute inline on a custom element and then programatically
          //  changes it before it has been upgraded as shown below:
          //
          //  ```html
          //    <!-- this component has _not_ been upgraded yet -->
          //    <my-component id="test" some-attribute="some-value"></my-component>
          //    <script>
          //      // grab non-upgraded component
          //      el = document.querySelector("#test");
          //      el.someAttribute = "another-value";
          //      // upgrade component
          //      cutsomElements.define('my-component', MyComponent);
          //    </script>
          //  ```
          //  In this case if we do not unshadow here and use the value of the shadowing property, attributeChangedCallback
          //  will be called with `newValue = "some-value"` and will set the shadowed property (this.someAttribute = "another-value")
          //  to the value that was set inline i.e. "some-value" from above example. When
          //  the connectedCallback attempts to unshadow it will use "some-value" as the intial value rather than "another-value"
          //
          //  The case where the attribute was NOT set inline but was not set programmatically shall be handled/unshadowed
          //  by connectedCallback as this attributeChangedCallback will not fire.
          //
          //  https://developers.google.com/web/fundamentals/web-components/best-practices#lazy-properties
          //
          //  TODO(STENCIL-16) we should think about whether or not we actually want to be reflecting the attributes to
          //  properties here given that this goes against best practices outlined here
          //  https://developers.google.com/web/fundamentals/web-components/best-practices#avoid-reentrancy

          if (_this.hasOwnProperty(propName)) {
            newValue = _this[propName];
            delete _this[propName];
          }

          _this[propName] = newValue === null && typeof _this[propName] === 'boolean' ? false : newValue;
        });
      }; // create an array of attributes to observe
      // and also create a map of html attribute name to js property name


      Cstr.observedAttributes = members.filter(function (_ref16) {
        var _ref17 = _slicedToArray(_ref16, 2),
            m = _ref17[1];

        return m[0] & 15;
      }
      /* HasAttribute */
      ) // filter to only keep props that should match attributes
      .map(function (_ref18) {
        var _ref19 = _slicedToArray(_ref18, 2),
            propName = _ref19[0],
            m = _ref19[1];

        var attrName = m[1] || propName;
        attrNameToPropName.set(attrName, propName);

        if (m[0] & 512
        /* ReflectAttr */
        ) {
          cmpMeta.$attrsToReflect$.push([propName, attrName]);
        }

        return attrName;
      });
    }
  }

  return Cstr;
};

var initializeComponent = /*#__PURE__*/function () {
  var _ref20 = _asyncToGenerator( /*#__PURE__*/regeneratorRuntime.mark(function _callee2(elm, hostRef, cmpMeta, hmrVersionId, Cstr) {
    var endLoad, endNewInstance, style, _scopeId, endRegisterStyles, ancestorComponent, schedule;

    return regeneratorRuntime.wrap(function _callee2$(_context2) {
      while (1) {
        switch (_context2.prev = _context2.next) {
          case 0:
            if (!((hostRef.$flags$ & 32
            /* hasInitializedComponent */
            ) === 0)) {
              _context2.next = 37;
              break;
            }

            // we haven't initialized this element yet
            hostRef.$flags$ |= 32
            /* hasInitializedComponent */
            ; // lazy loaded components
            // request the component's implementation to be
            // wired up with the host element

            Cstr = loadModule(cmpMeta);

            if (!Cstr.then) {
              _context2.next = 10;
              break;
            }

            // Await creates a micro-task avoid if possible
            endLoad = uniqueTime();
            _context2.next = 8;
            return Cstr;

          case 8:
            Cstr = _context2.sent;
            endLoad();

          case 10:
            {
              _context2.next = 12;
              break;
            }

          case 12:
            if (!Cstr.isProxied) {
              // we've never proxied this Constructor before
              // let's add the getters/setters to its prototype before
              // the first time we create an instance of the implementation
              {
                cmpMeta.$watchers$ = Cstr.watchers;
              }

              proxyComponent(Cstr, cmpMeta, 2
              /* proxyState */
              );
              Cstr.isProxied = true;
            }

            endNewInstance = createTime(); // ok, time to construct the instance
            // but let's keep track of when we start and stop
            // so that the getters/setters don't incorrectly step on data

            {
              hostRef.$flags$ |= 8
              /* isConstructingInstance */
              ;
            } // construct the lazy-loaded component implementation
            // passing the hostRef is very important during
            // construction in order to directly wire together the
            // host element and the lazy-loaded instance


            try {
              new Cstr(hostRef);
            } catch (e) {
              consoleError(e);
            }

            {
              hostRef.$flags$ &= ~8
              /* isConstructingInstance */
              ;
            }

            {
              hostRef.$flags$ |= 128
              /* isWatchReady */
              ;
            }

            endNewInstance();
            _context2.next = 25;
            break;

          case 22:
            // sync constructor component
            Cstr = elm.constructor;
            hostRef.$flags$ |= 32
            /* hasInitializedComponent */
            ; // wait for the CustomElementRegistry to mark the component as ready before setting `isWatchReady`. Otherwise,
            // watchers may fire prematurely if `customElements.get()`/`customElements.whenDefined()` resolves _before_
            // Stencil has completed instantiating the component.

            customElements.whenDefined(cmpMeta.$tagName$).then(function () {
              return hostRef.$flags$ |= 128
              /* isWatchReady */
              ;
            });

          case 25:
            if (!(Cstr.style)) {
              _context2.next = 37;
              break;
            }

            // this component has styles but we haven't registered them yet
            style = Cstr.style;

            _scopeId = getScopeId(cmpMeta);

            if (styles.has(_scopeId)) {
              _context2.next = 37;
              break;
            }

            endRegisterStyles = createTime();

            {
              _context2.next = 35;
              break;
            }

          case 34:
            style = _context2.sent;

          case 35:
            registerStyle(_scopeId, style, !!(cmpMeta.$flags$ & 1
            /* shadowDomEncapsulation */
            ));
            endRegisterStyles();

          case 37:
            // we've successfully created a lazy instance
            ancestorComponent = hostRef.$ancestorComponent$;

            schedule = function schedule() {
              return scheduleUpdate(hostRef, true);
            };

            if (ancestorComponent && ancestorComponent['s-rc']) {
              // this is the intial load and this component it has an ancestor component
              // but the ancestor component has NOT fired its will update lifecycle yet
              // so let's just cool our jets and wait for the ancestor to continue first
              // this will get fired off when the ancestor component
              // finally gets around to rendering its lazy self
              // fire off the initial update
              ancestorComponent['s-rc'].push(schedule);
            } else {
              schedule();
            }

          case 40:
          case "end":
            return _context2.stop();
        }
      }
    }, _callee2);
  }));

  return function initializeComponent(_x4, _x5, _x6, _x7, _x8) {
    return _ref20.apply(this, arguments);
  };
}();

var _connectedCallback = function connectedCallback(elm) {
  if ((plt.$flags$ & 1
  /* isTmpDisconnected */
  ) === 0) {
    var hostRef = getHostRef(elm);
    var cmpMeta = hostRef.$cmpMeta$;
    var endConnected = createTime();

    if (!(hostRef.$flags$ & 1
    /* hasConnected */
    )) {
      // first time this component has connected
      hostRef.$flags$ |= 1
      /* hasConnected */
      ;
      var hostId;

      if (!hostId) {
        // initUpdate
        // if the slot polyfill is required we'll need to put some nodes
        // in here to act as original content anchors as we move nodes around
        // host element has been connected to the DOM
        if (cmpMeta.$flags$ & (4
        /* hasSlotRelocation */
        | 8
        /* needsShadowDomShim */
        )) {
          setContentReference(elm);
        }
      }

      {
        // find the first ancestor component (if there is one) and register
        // this component as one of the actively loading child components for its ancestor
        var ancestorComponent = elm;

        while (ancestorComponent = ancestorComponent.parentNode || ancestorComponent.host) {
          // climb up the ancestors looking for the first
          // component that hasn't finished its lifecycle update yet
          if (ancestorComponent['s-p']) {
            // we found this components first ancestor component
            // keep a reference to this component's ancestor component
            attachToAncestor(hostRef, hostRef.$ancestorComponent$ = ancestorComponent);
            break;
          }
        }
      } // Lazy properties
      // https://developers.google.com/web/fundamentals/web-components/best-practices#lazy-properties


      if (cmpMeta.$members$) {
        Object.entries(cmpMeta.$members$).map(function (_ref21) {
          var _ref22 = _slicedToArray(_ref21, 2),
              memberName = _ref22[0],
              _ref22$ = _slicedToArray(_ref22[1], 1),
              memberFlags = _ref22$[0];

          if (memberFlags & 31
          /* Prop */
          && elm.hasOwnProperty(memberName)) {
            var value = elm[memberName];
            delete elm[memberName];
            elm[memberName] = value;
          }
        });
      }

      {
        initializeComponent(elm, hostRef, cmpMeta);
      }
    } else {
      // not the first time this has connected
      // reattach any event listeners to the host
      // since they would have been removed when disconnected
      addHostEventListeners(elm, hostRef, cmpMeta.$listeners$); // fire off connectedCallback() on component instance
    }

    endConnected();
  }
};

var setContentReference = function setContentReference(elm) {
  // only required when we're NOT using native shadow dom (slot)
  // or this browser doesn't support native shadow dom
  // and this host element was NOT created with SSR
  // let's pick out the inner content for slot projection
  // create a node to represent where the original
  // content was first placed, which is useful later on
  var contentRefElm = elm['s-cr'] = doc.createComment('');
  contentRefElm['s-cn'] = true;
  elm.insertBefore(contentRefElm, elm.firstChild);
};

var _disconnectedCallback = function disconnectedCallback(elm) {
  if ((plt.$flags$ & 1
  /* isTmpDisconnected */
  ) === 0) {
    var hostRef = getHostRef(elm);
    var instance = hostRef.$lazyInstance$ ;

    {
      if (hostRef.$rmListeners$) {
        hostRef.$rmListeners$.map(function (rmListener) {
          return rmListener();
        });
        hostRef.$rmListeners$ = undefined;
      }
    } // clear CSS var-shim tracking

    {
      safeCall(instance, 'disconnectedCallback');
    }
  }
};

var bootstrapLazy = function bootstrapLazy(lazyBundles) {
  var options = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : {};
  var endBootstrap = createTime();
  var cmpTags = [];
  var exclude = options.exclude || [];
  var customElements = win.customElements;
  var head = doc.head;
  var metaCharset = /*@__PURE__*/head.querySelector('meta[charset]');
  var visibilityStyle = /*@__PURE__*/doc.createElement('style');
  var deferredConnectedCallbacks = [];
  var appLoadFallback;
  var isBootstrapping = true;
  Object.assign(plt, options);
  plt.$resourcesUrl$ = new URL(options.resourcesUrl || './', doc.baseURI).href;

  lazyBundles.map(function (lazyBundle) {
    return lazyBundle[1].map(function (compactMeta) {
      var cmpMeta = {
        $flags$: compactMeta[0],
        $tagName$: compactMeta[1],
        $members$: compactMeta[2],
        $listeners$: compactMeta[3]
      };

      {
        cmpMeta.$members$ = compactMeta[2];
      }

      {
        cmpMeta.$listeners$ = compactMeta[3];
      }

      {
        cmpMeta.$attrsToReflect$ = [];
      }

      {
        cmpMeta.$watchers$ = {};
      }

      var tagName = cmpMeta.$tagName$;

      var HostElement = /*#__PURE__*/function (_HTMLElement) {
        _inherits(HostElement, _HTMLElement);

        var _super2 = _createSuper(HostElement);

        // StencilLazyHost
        function HostElement(self) {
          var _this2;

          _classCallCheck(this, HostElement);

          // @ts-ignore
          _this2 = _super2.call(this, self);
          self = _assertThisInitialized(_this2);
          registerHost(self, cmpMeta);

          if (cmpMeta.$flags$ & 1
          /* shadowDomEncapsulation */
          ) {
            // this component is using shadow dom
            // and this browser supports shadow dom
            // add the read-only property "shadowRoot" to the host element
            // adding the shadow root build conditionals to minimize runtime
            {
              {
                self.attachShadow({
                  mode: 'open'
                });
              }
            }
          }

          return _this2;
        }

        _createClass(HostElement, [{
          key: "connectedCallback",
          value: function connectedCallback() {
            var _this3 = this;

            if (appLoadFallback) {
              clearTimeout(appLoadFallback);
              appLoadFallback = null;
            }

            if (isBootstrapping) {
              // connectedCallback will be processed once all components have been registered
              deferredConnectedCallbacks.push(this);
            } else {
              plt.jmp(function () {
                return _connectedCallback(_this3);
              });
            }
          }
        }, {
          key: "disconnectedCallback",
          value: function disconnectedCallback() {
            var _this4 = this;

            plt.jmp(function () {
              return _disconnectedCallback(_this4);
            });
          }
        }, {
          key: "componentOnReady",
          value: function componentOnReady() {
            return getHostRef(this).$onReadyPromise$;
          }
        }]);

        return HostElement;
      }( /*#__PURE__*/_wrapNativeSuper(HTMLElement));

      cmpMeta.$lazyBundleId$ = lazyBundle[0];

      if (!exclude.includes(tagName) && !customElements.get(tagName)) {
        cmpTags.push(tagName);
        customElements.define(tagName, proxyComponent(HostElement, cmpMeta, 1
        /* isElementConstructor */
        ));
      }
    });
  });

  {
    visibilityStyle.innerHTML = cmpTags + HYDRATED_CSS;
    visibilityStyle.setAttribute('data-styles', '');
    head.insertBefore(visibilityStyle, metaCharset ? metaCharset.nextSibling : head.firstChild);
  } // Process deferred connectedCallbacks now all components have been registered


  isBootstrapping = false;

  if (deferredConnectedCallbacks.length) {
    deferredConnectedCallbacks.map(function (host) {
      return host.connectedCallback();
    });
  } else {
    {
      plt.jmp(function () {
        return appLoadFallback = setTimeout(appDidLoad, 30);
      });
    }
  } // Fallback appLoad event


  endBootstrap();
};

var hostRefs = new WeakMap();

var getHostRef = function getHostRef(ref) {
  return hostRefs.get(ref);
};

var registerInstance = function registerInstance(lazyInstance, hostRef) {
  return hostRefs.set(hostRef.$lazyInstance$ = lazyInstance, hostRef);
};

var registerHost = function registerHost(elm, cmpMeta) {
  var hostRef = {
    $flags$: 0,
    $hostElement$: elm,
    $cmpMeta$: cmpMeta,
    $instanceValues$: new Map()
  };

  {
    hostRef.$onInstancePromise$ = new Promise(function (r) {
      return hostRef.$onInstanceResolve$ = r;
    });
  }

  {
    hostRef.$onReadyPromise$ = new Promise(function (r) {
      return hostRef.$onReadyResolve$ = r;
    });
    elm['s-p'] = [];
    elm['s-rc'] = [];
  }

  addHostEventListeners(elm, hostRef, cmpMeta.$listeners$);
  return hostRefs.set(elm, hostRef);
};

var isMemberInElement = function isMemberInElement(elm, memberName) {
  return memberName in elm;
};

var consoleError = function consoleError(e, el) {
  return (0, console.error)(e, el);
};

var cmpModules = /*@__PURE__*/new Map();

var loadModule = function loadModule(cmpMeta, hostRef, hmrVersionId) {
  // loadModuleImport
  var exportName = cmpMeta.$tagName$.replace(/-/g, '_');
  var bundleId = cmpMeta.$lazyBundleId$;

  var module = cmpModules.get(bundleId) ;

  if (module) {
    return module[exportName];
  }

  return Promise.resolve().then(function () { return /*#__PURE__*/_interopNamespace(require(
  /* webpackInclude: /\.entry\.js$/ */

  /* webpackExclude: /\.system\.entry\.js$/ */

  /* webpackMode: "lazy" */
  "./".concat(bundleId, ".entry.js").concat(''))); }).then(function (importedModule) {
    {
      cmpModules.set(bundleId, importedModule);
    }

    return importedModule[exportName];
  }, consoleError);
};

var styles = new Map();
var queueDomReads = [];
var queueDomWrites = [];

var queueTask = function queueTask(queue, write) {
  return function (cb) {
    queue.push(cb);

    if (!queuePending) {
      queuePending = true;

      if (write && plt.$flags$ & 4
      /* queueSync */
      ) {
        nextTick(flush);
      } else {
        plt.raf(flush);
      }
    }
  };
};

var consume = function consume(queue) {
  for (var _i7 = 0; _i7 < queue.length; _i7++) {
    try {
      queue[_i7](performance.now());
    } catch (e) {
      consoleError(e);
    }
  }

  queue.length = 0;
};

var flush = function flush() {
  // a throttle on how many can run in a certain time
  // DOM READS!!!


  consume(queueDomReads); // DOM WRITES!!!

  {
    consume(queueDomWrites);

    if (queuePending = queueDomReads.length > 0) {
      // still more to do yet, but we've run out of time
      // let's let this thing cool off and try again in the next tick
      plt.raf(flush);
    }
  }
};

var nextTick = /*@__PURE__*/function nextTick(cb) {
  return promiseResolve().then(cb);
};
var writeTask = /*@__PURE__*/queueTask(queueDomWrites, true);

exports.Host = Host;
exports._asyncToGenerator = _asyncToGenerator;
exports._classCallCheck = _classCallCheck;
exports._createClass = _createClass;
exports._createForOfIteratorHelper = _createForOfIteratorHelper;
exports._defineProperty = _defineProperty;
exports._objectSpread2 = _objectSpread2;
exports._objectWithoutProperties = _objectWithoutProperties;
exports._slicedToArray = _slicedToArray;
exports._toConsumableArray = _toConsumableArray;
exports._typeof = _typeof;
exports._wrapRegExp = _wrapRegExp;
exports.bootstrapLazy = bootstrapLazy;
exports.createEvent = createEvent;
exports.getElement = getElement;
exports.h = h;
exports.promiseResolve = promiseResolve;
exports.registerInstance = registerInstance;
