﻿/*
 Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
(function(){function c(a,b,d){var c=e;d&&(c=CKEDITOR.tools.debounce(e,d));a.on(b,c,null,{editor:a})}function e(a){var b=a.listenerData.editor;a=b.focusManager.hasFocus;var c=b.editable(),e=b.config.editorplaceholder,f=/<body.*?>((?:.|[\n\r])*?)<\/body>/i,g=b.config.fullPage,b=b.getData();g&&(f=b.match(f))&&1<f.length&&(b=f[1]);if(0!==b.length||a)return c.removeAttribute(d);c.setAttribute(d,e)}CKEDITOR.plugins.add("editorplaceholder",{isSupportedEnvironment:function(){return!CKEDITOR.env.ie||9<=CKEDITOR.env.version},
onLoad:function(){CKEDITOR.addCss(CKEDITOR.plugins.editorplaceholder.styles)},init:function(a){this.isSupportedEnvironment()&&a.config.editorplaceholder&&(c(a,"contentDom"),c(a,"focus"),c(a,"blur"),c(a,"change",a.config.editorplaceholder_delay))}});var d="data-cke-editorplaceholder";CKEDITOR.plugins.editorplaceholder={styles:"["+d+"]::before {position: absolute;opacity: .8;color: #aaa;content: attr( "+d+" );}.cke_wysiwyg_div["+d+"]::before {margin-top: 1em;}"};CKEDITOR.config.editorplaceholder_delay=
150;CKEDITOR.config.editorplaceholder=""})();
