export default (el, css) => {
  if (!css)
    return;
  const style = document.createElement('style');
  el.shadowRoot.append(style);
  style.appendChild(document.createTextNode(css));
};
