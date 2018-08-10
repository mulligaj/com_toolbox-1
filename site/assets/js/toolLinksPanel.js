'use strict';

var TOOLBOX = TOOLBOX || {};

TOOLBOX.linksForm = TOOLBOX.linksForm || {};

linksForm = TOOLBOX.linksForm;

linksForm.createLinkPanel = function () {
	var panelNumber = linksForm.linksCount + 1;
	linksForm.linksCount++;

	var linkPanel = '';
	linkPanel += '<div class="col span11 grid link">';
	linkPanel += '<div class="col span5">';
	linkPanel += '<label>Text';
	linkPanel += '<input type="text" name="links[' + panelNumber + '][text]">';
	linkPanel += '</label>';
	linkPanel += '</div>';
	linkPanel += '<div class="col span5">';
	linkPanel += '<label>URL';
	linkPanel += '<input type="text" name="links[' + panelNumber + '][url]">';
	linkPanel += '</label>';
	linkPanel += '</div>';
	linkPanel += '<div class="col span1 delete-wrapper">';
	linkPanel += '<div class="btn btn-danger">?</div>';
	linkPanel += '</div>';
	linkPanel += '<input type="hidden" name="links[' + panelNumber + '][id]" value="0">';
	linkPanel += '</div>';

	return linkPanel;
};