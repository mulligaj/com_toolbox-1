'use strict';

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

var TOOLBOX = TOOLBOX || {};

TOOLBOX.toolSearchForm = TOOLBOX.toolSearchForm || {};

var toolSearchForm = TOOLBOX.toolSearchForm;

toolSearchForm.CARET_CLASS = 'caret';
toolSearchForm.CONTENT_CLASS = 'content';
toolSearchForm.MASTER_CARET_ID = 'master-caret';
toolSearchForm.ROW_CLASS = 'row';

toolSearchForm.init = function () {
	// Collect row content wrappers
	toolSearchForm.rowContentWrappers = $('.' + toolSearchForm.CONTENT_CLASS);

	// Collect carets
	toolSearchForm.carets = $('.' + toolSearchForm.CARET_CLASS);

	// Collect master caret
	toolSearchForm.masterCaret = $('#' + toolSearchForm.MASTER_CARET_ID);
};

toolSearchForm.caretHandler = function (e) {
	var $caret = $(e.target);
	var $rowContent = toolSearchForm.findCaretRowContent($caret);

	if ($rowContent.is(':visible')) {
		$rowContent.slideUp(null, function () {
			toolSearchForm.toggleCaretDirection($caret, $rowContent);
		});
	} else if ($rowContent.is(':hidden')) {
		$rowContent.slideDown(null, function () {
			toolSearchForm.toggleCaretDirection($caret, $rowContent);
		});
	}
};

toolSearchForm.toggleCaretDirection = function ($caret) {
	var $rowContent = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : null;

	if (!$rowContent) {
		$rowContent = toolSearchForm.findCaretRowContent($caret);
	}
	var html = void 0;

	if ($rowContent.is(':visible')) {
		html = '&#x2303;';
	} else if ($rowContent.is(':hidden')) {
		html = '&#x2304;';
	}

	$caret.html(html);
};

toolSearchForm.findCaretRowContent = function ($caret) {
	var $row = $caret.closest('.' + toolSearchForm.ROW_CLASS);
	var $rowContent = $row.find('.' + toolSearchForm.CONTENT_CLASS);

	return $rowContent;
};

toolSearchForm.masterCaretHandler = function (e) {
	var $masterCaret = toolSearchForm.masterCaret;
	var $rowContentWrappers = toolSearchForm.rowContentWrappers;
	var visibleKey = 'visible';
	var rowsVisible = !!$masterCaret.data(visibleKey);

	if (rowsVisible) {
		$rowContentWrappers.slideUp(null, function () {
			toolSearchForm.toggleAllCarets('&#xf0d7;', _defineProperty({}, visibleKey, false));
		});
	} else if (!rowsVisible) {
		$rowContentWrappers.slideDown(null, function () {
			toolSearchForm.toggleAllCarets('&#xf0d8;', _defineProperty({}, visibleKey, true));
		});
	}
};

toolSearchForm.toggleAllCarets = function (html, data) {
	var $masterCaret = toolSearchForm.masterCaret;

	$masterCaret.html(html);
	$masterCaret.data(data);
	toolSearchForm.toggleAllCaretDirections();
};

toolSearchForm.toggleAllCaretDirections = function () {
	var $carets = toolSearchForm.carets;

	$.each($carets, function (_, caret) {
		toolSearchForm.toggleCaretDirection($(caret));
	});
};

$(document).ready(function () {

	// initialize search form
	toolSearchForm.init();

	// add click handler to carets
	toolSearchForm.carets.click(toolSearchForm.caretHandler);

	// add click handler to master caret
	toolSearchForm.masterCaret.click(toolSearchForm.masterCaretHandler);
});
