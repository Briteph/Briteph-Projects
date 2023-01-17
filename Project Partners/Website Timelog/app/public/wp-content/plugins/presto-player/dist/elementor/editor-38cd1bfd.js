/*!
 * 
 * Presto Player
 * 
 * @author Presto Made, Inc
 * @version 0.1.0
 * @link undefined
 * @license GPL
 * 
 * Copyright (c) 2022 Presto Made, Inc
 * 
 * This software is released under the GPL License
 * https://opensource.org/licenses/GPL
 * 
 * Compiled with the help of https://wpack.io
 * A zero setup Webpack Bundler Script for WordPress
 */
(window.wpackioprestoPlayerelementorJsonp=window.wpackioprestoPlayerelementorJsonp||[]).push([[0],[function(e,o,t){t(1),e.exports=t(2)},function(e,o,t){var n="prestoPlayerdist".replace(/[^a-zA-Z0-9_-]/g,"");t.p=window["__wpackIo".concat(n)]},function(e,o,t){"use strict";var n,i;t.r(o);var r="".concat(null===(n=prestoEditorData)||void 0===n?void 0:n.root).concat(null===(i=prestoEditorData)||void 0===i?void 0:i.wpVersionString,"presto-videos");jQuery(window).on("elementor/frontend/init",(function(){"undefined"!=typeof elementor&&(elementor.channels.editor.on("presto:video:edit",(function(e){var o=e.elementSettingsModel.get("video_block");o&&window.open(prestoEditorData.siteURL+"/wp-admin/post.php?post=".concat(o,"&action=edit"),"_blank").focus()})),elementor.channels.editor.on("editor:widget:presto_video:section_video:activated",(function(e){e.$el.find(".elementor-select2").select2({ajax:{url:r,dataType:"json",headers:{"X-WP-Nonce":prestoEditorData.nonce},data:function(e){return{search:e.term}},processResults:function(e){return{results:jQuery.map(e,(function(e){var o;return{id:e.id,text:(null==e||null===(o=e.title)||void 0===o?void 0:o.raw)||"Untitled Video"}}))}}}}),e.model.get("settings").on("change",(function(e){}))})),elementor.channels.editor.on("presto:video:create",(function(){window.open(prestoEditorData.siteURL+"/wp-admin/post-new.php?post_type=pp_video_block","_blank").focus()})))}))}],[[0,1]]]);
//# sourceMappingURL=editor-38cd1bfd.js.map