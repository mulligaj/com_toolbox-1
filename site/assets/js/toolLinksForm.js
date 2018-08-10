'use strict';

var TOOLBOX = TOOLBOX || {};

TOOLBOX.linksForm = TOOLBOX.linksForm || {};

var linksForm = TOOLBOX.linksForm;

linksForm.ADD_LINK_BUTTON_ID = 'add-link';
linksForm.ADD_LINK_BUTTON_WRAPPER = 'add-link-button-wrapper';
linksForm.DELETE_BUTTON_CLASS = 'btn-danger';
linksForm.DELETE_WRAPPER_CLASS = 'delete-wrapper';
linksForm.LINK_PANEL_CLASS = 'link';
linksForm.LINKS_WRAPPER_ID = 'links-wrapper';

linksForm.init = function () {
	// count of links on the page
	var $prefillLinks = $('.' + linksForm.LINK_PANEL_CLASS);
	linksForm.linksCount = $prefillLinks.length;

	// insert Delete Link button(s)
	linksForm.addLinkDeleteButton();

	// insert Add Link button & save reference
	linksForm.addLinkAddButton();
	linksForm.addLinkButton = $('#' + linksForm.ADD_LINK_BUTTON_ID);

	// save reference to links wrapper
	linksForm.linksWrapper = $('#' + linksForm.LINKS_WRAPPER_ID);
};

linksForm.appendLinkPanelTo = function ($targetElement) {
	var $linkPanel = linksForm.createLinkPanel();

	$targetElement.append($linkPanel);
};

linksForm.addLinkAddButton = function () {
	var $addLinkButton = $('<button id="' + linksForm.ADD_LINK_BUTTON_ID + '" class="btn">\n\t\t\tAdd Link\n\t\t</button>');

	$('#' + linksForm.ADD_LINK_BUTTON_WRAPPER).append($addLinkButton);
};

linksForm.addLinkHandler = function (e) {
	e.preventDefault();

	var $linksWrapper = linksForm.linksWrapper;

	linksForm.appendLinkPanelTo($linksWrapper);
};

linksForm.addLinkDeleteButton = function () {
	var $deleteWrappers = $('.' + linksForm.DELETE_WRAPPER_CLASS);
	var $deleteLinkButton = $('<div class="btn ' + linksForm.DELETE_BUTTON_CLASS + '">\u2716</div>');

	$deleteWrappers.append($deleteLinkButton);
};

linksForm.deleteLinkHandler = function (e) {
	var $target = $(e.target);

	if (!$target.hasClass(linksForm.DELETE_BUTTON_CLASS)) {
		return;
	}

	var $linkPanel = $target.closest('.' + linksForm.LINK_PANEL_CLASS);
	var linkId = $linkPanel.data('id');

	if (linkId > 0) {
		linksForm.deleteLinkAndRemovePanel(linkId, $linkPanel);
	} else {
		linksForm.removeLinkPanel($linkPanel);
	}
};

linksForm.deleteLinkAndRemovePanel = function (linkId, $linkPanel) {
	var link = new TOOLBOX.Link({ id: linkId });

	link.destroy().then(function (response) {
		var errors = void 0;
		var status = response.status;

		if (status === 'success') {
			$linkPanel.remove();
			Notify.success('Link deleted.');
		} else {
			Notify.error('There was an error attempting to delete the link.');
		}
	}, function (response) {
		Notify.error('There was an error attempting to communicate with the server.');
	});
};

linksForm.removeLinkPanel = function ($linkPanel) {
	$linkPanel.remove();
};

$(document).ready(function () {
	Hubzero.initApi(function () {

		// initialize links form
		linksForm.init();

		// add click handler to the Add Link button
		linksForm.addLinkButton.click(linksForm.addLinkHandler);

		// add click handler for Delete Link button(s)
		linksForm.linksWrapper.click(linksForm.deleteLinkHandler);
	});
});
