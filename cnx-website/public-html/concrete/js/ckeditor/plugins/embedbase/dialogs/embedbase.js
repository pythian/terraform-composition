﻿/*
 Copyright (c) 2003-2023, CKSource Holding sp. z o.o. All rights reserved.
 For licensing, see LICENSE.md or https://ckeditor.com/legal/ckeditor-oss-license
*/
CKEDITOR.dialog.add("embedBase",function(a){var c=a.lang.embedbase;return{title:c.title,minWidth:350,minHeight:50,onLoad:function(){function f(){b.setState(CKEDITOR.DIALOG_STATE_IDLE);d=null}var b=this,d=null;this.on("ok",function(g){g.data.hide=!1;g.stop();b.setState(CKEDITOR.DIALOG_STATE_BUSY);var c=b.getValueOf("info","url"),e=b.getModel(a);d=e.loadContent(c,{noNotifications:!0,callback:function(){e.isReady()||a.widgets.finalizeCreation(e.wrapper.getParent(!0));a.fire("saveSnapshot");b.hide();
f()},errorCallback:function(a){b.getContentElement("info","url").select();alert(e.getErrorMessage(a,c,"Given"));f()}})},null,null,15);this.on("cancel",function(a){a.data.hide&&d&&(d.cancel(),f())})},contents:[{id:"info",elements:[{type:"text",id:"url",label:a.lang.common.url,required:!0,setup:function(a){this.setValue(a.data.url)},validate:function(){return this.getDialog().getModel(a).isUrlValid(this.getValue())?!0:c.unsupportedUrlGiven}}]}]}});
