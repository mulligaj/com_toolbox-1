'use strict';

var enterKeyCode = 13;

var toolSearchFormId = 'search-form';
var searchResultsContainerId = 'results';

var $nameInput = void 0,
    $toolSearchForm = void 0;

var nameInputAttributes = {
	name: 'query[name]',
	placeholder: 'Search by name...',
	type: 'text'
};

$(document).ready(function () {

	getToolSearchForm();
	prependNameInput();
	setNameInputValue();
	registerNameInputEventHandlers();
	registerSearchFormEventHandlers();
});

var getToolSearchForm = function getToolSearchForm() {
	$toolSearchForm = $('#' + toolSearchFormId);

	return $toolSearchForm;
};

var prependNameInput = function prependNameInput() {
	var $searchResultsContainer = getSearchResultsContainer();
	$nameInput = generateNameInput();

	$searchResultsContainer.prepend($nameInput);
};

var getSearchResultsContainer = function getSearchResultsContainer() {
	var $searchResultsContainer = $('#' + searchResultsContainerId);

	return $searchResultsContainer;
};

var generateNameInput = function generateNameInput() {
	var $nameInput = $('<input>');

	addNameInputAttributes($nameInput);

	return $nameInput;
};

var addNameInputAttributes = function addNameInputAttributes($nameInput) {
	for (var attr in nameInputAttributes) {
		$nameInput.attr(attr, nameInputAttributes[attr]);
	}
};

var setNameInputValue = function setNameInputValue() {
	var value = sourceNameInputValue();

	$nameInput.val(value);
};

var sourceNameInputValue = function sourceNameInputValue() {
	var $valueContainer = $('[data-query-name]');
	var value = $valueContainer.data('query-name');

	return value;
};

var registerNameInputEventHandlers = function registerNameInputEventHandlers() {
	$nameInput.on('keypress', submitToolSearchFormOnEnter);
};

var submitToolSearchFormOnEnter = function submitToolSearchFormOnEnter(e) {
	if (e.which == enterKeyCode) {
		submitToolSearchFormIncludingName();
	}
};

var registerSearchFormEventHandlers = function registerSearchFormEventHandlers() {
	$toolSearchForm.on('click', submitToolSearchFormOnClick);
};

var submitToolSearchFormOnClick = function submitToolSearchFormOnClick(e) {
	e.preventDefault();
	var targetType = $(e.target).attr('type');

	if (targetType === 'submit') {
		submitToolSearchFormIncludingName();
	}
};

var submitToolSearchFormIncludingName = function submitToolSearchFormIncludingName(e) {
	appendHiddenNameInput($toolSearchForm);
	$toolSearchForm.submit();
};

var appendHiddenNameInput = function appendHiddenNameInput($element) {
	var $hiddenNameInput = generateHiddenNameInput();

	$element.append($hiddenNameInput);
};

var generateHiddenNameInput = function generateHiddenNameInput() {
	var $hiddenNameInput = generateNameInput();
	var value = $nameInput.val();

	$hiddenNameInput.attr('type', 'hidden');
	$hiddenNameInput.val(value);

	return $hiddenNameInput;
};