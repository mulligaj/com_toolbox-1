'use strict';

var TOOLBOX = TOOLBOX || {};

TOOLBOX.downloadsForm = TOOLBOX.downloadsForm || {};

var downloadsForm = TOOLBOX.downloadsForm;

downloadsForm.ANCHOR_ID = 'uploader-anchor';
downloadsForm.DELETE_FORM_ID = 'delete-form';
downloadsForm.DOWNLOADS_LIST_ID = 'downloads';
downloadsForm.DROP_AREA_CLASS = 'qq-upload-drop-area';
downloadsForm.HUB_FORM_ID = 'hubForm';
downloadsForm.UPLOADS_LIST_CLASS = 'qq-upload-list';
downloadsForm.UPLOADS_LIST_ID = 'upload-list';
downloadsForm.UPLOAD_BUTTON_CLASS = 'qq-upload-button';
downloadsForm.UPLOADER_CLASS = 'qq-uploader';
downloadsForm.UPLOADER_CONTAINER_ID = 'file-uploader';

downloadsForm.init = function () {
	downloadsForm.container = $('#' + downloadsForm.UPLOADER_CONTAINER_ID);
	downloadsForm.deleteForm = $('#' + downloadsForm.DELETE_FORM_ID);
	downloadsForm.downloadsList = $('#' + downloadsForm.DOWNLOADS_LIST_ID);

	downloadsForm.hideDeleteForm();
	downloadsForm.clearUploaderContainer();
	downloadsForm.insertUploaderAnchors();
	downloadsForm.getUploaderAction();
	downloadsForm.renderUploader();
};

downloadsForm.hideDeleteForm = function () {
	var downloadsCount = downloadsForm.downloadsList.find('li').length;

	if (downloadsCount <= 0) {
		downloadsForm.deleteForm.hide();
	}
};

downloadsForm.clearUploaderContainer = function () {
	downloadsForm.container.html('');
};

downloadsForm.insertUploaderAnchors = function () {
	var $uploaderAnchor = $('<div id="' + downloadsForm.ANCHOR_ID + '"></div>');
	var $listAnchor = $('<div id="' + downloadsForm.UPLOADS_LIST_ID + '"></div>');

	downloadsForm.container.append($uploaderAnchor);
	downloadsForm.container.append($listAnchor);
};

downloadsForm.getUploaderAction = function () {
	if (!downloadsForm.action) {
		var $hubForm = $('#' + downloadsForm.HUB_FORM_ID);
		downloadsForm.action = $hubForm.attr('action');
	}

	return downloadsForm.action;
};

downloadsForm.renderUploader = function () {
	downloadsForm.anchor = $('#' + downloadsForm.ANCHOR_ID);
	downloadsForm.hasAnchor = !!downloadsForm.anchor.length;
	downloadsForm.uploadsList = $('#' + downloadsForm.UPLOADS_LIST_ID);

	if (!downloadsForm.hasAnchor) {
		return;
	}

	var action = downloadsForm.action + '?no_html=1';
	var template = downloadsForm.getUploaderTemplate();

	var uploader = new qq.FileUploader({
		element: downloadsForm.anchor[0],
		action: action,
		multiple: true,
		debug: true,
		template: template,
		onComplete: downloadsForm.onUploadComplete
	});
};

downloadsForm.onUploadComplete = function (_, fileName, response) {
	downloadsForm.switchLists(response, fileName);
	downloadsForm._notifyUploadResult(response, fileName);
};

downloadsForm.switchLists = function (response, fileName) {
	downloadsForm.removeUploadLi(fileName);
	downloadsForm.addToDeleteList(response, fileName);
};

downloadsForm.removeUploadLi = function (fileName) {
	var $uploadLi = downloadsForm.findUploadLi(fileName);

	$uploadLi.fadeOut(1000);
};

downloadsForm.addToDeleteList = function (response, fileName) {
	var downloadId = response.id;
	var toolId = response.tool_id;
	var uploadSucceeded = response.success;
	var $deleteLi = downloadsForm.generateDeleteLi(downloadId, toolId, fileName);

	if (uploadSucceeded) {
		if (downloadsForm.deleteForm.is(':hidden')) {
			downloadsForm.deleteForm.show();
		}

		downloadsForm.downloadsList.append($deleteLi).hide().fadeIn(1000);
	}
};

downloadsForm.generateDeleteLi = function (downloadId, toolId, fileName) {
	var $deleteLi = $('\n\t\t<li>\n\t\t\t<input type="checkbox" name="downloads" value="' + downloadId + '">\n\t\t\t<a href="/app/site/toolbox/downloads/' + toolId + '/' + fileName + '" download>\n\t\t\t\t' + fileName + '\n\t\t\t</a>\n\t\t</li>\n\t');

	return $deleteLi;
};

downloadsForm.findUploadLi = function (fileName) {
	var $uploadsList = $('.' + downloadsForm.UPLOADS_LIST_CLASS);
	var $uploadSpan = $uploadsList.find('span:contains(\'' + fileName + '\')');
	var $uploadLi = $uploadSpan.closest('li');

	return $uploadLi;
};

downloadsForm._notifyUploadResult = function (response, fileName) {
	var uploadSucceeded = response.success;
	var errorMessage = void 0,
	    errors = void 0;

	if (uploadSucceeded) {
		Notify.success('Upload(s) succeeded');
	} else {
    errorMessage = 'There was an error attempting to upload ' + fileName;
		errors = response.errors;

    if (errors && errors.length > 0) {
      errorMessage = 'The following errors occurred while attempting to upload ' + fileName + ':<br/><br/>';

      errors.forEach(function (error) {
        errorMessage += '&bull; ' + error + '<br/>';
      });
    }

		Notify.error(errorMessage);
	}
};

downloadsForm.getUploaderTemplate = function () {
	var uploaderClass = downloadsForm.UPLOADER_CLASS;
	var uploadButtonClass = downloadsForm.UPLOAD_BUTTON_CLASS;
	var dropAreaClass = downloadsForm.DROP_AREA_CLASS;
	var listClass = downloadsForm.UPLOADS_LIST_CLASS;

	var template = '<div class="' + uploaderClass + '">\n\t\t<div class="' + uploadButtonClass + '"><span>Click or drop file(s)</span></div>\n\t\t<div class="' + dropAreaClass + '"><span>Click or drop file(s)</span></div>\n\t\t<ul class="' + listClass + '"></ul>\n\t</div>';

	return template;
};

$(document).ready(function () {
	Hubzero.initApi(function () {

		// initialize downloads form
		downloadsForm.init();
	});
});
