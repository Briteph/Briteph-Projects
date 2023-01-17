var prestoDOMReady = function (callback) {
  document.readyState === "interactive" || document.readyState === "complete"
    ? callback()
    : document.addEventListener("DOMContentLoaded", callback);
};
prestoDOMReady(function () {
  if (window.prestoComponents && window.prestoComponents.url) {
    var script = document.createElement("script");
    script.type = "module";
    script.src = window.prestoComponents.url;
    document.getElementsByTagName("head")[0].appendChild(script);
  }
});
