
const enterKeyCode = 13

const toolSearchFormId = 'search-form'
const searchResultsContainerId = 'results'

let $nameInput, $toolSearchForm

const nameInputAttributes = {
	name: 'query[name]',
	placeholder: 'Search by name...',
	type: 'text'
}

$(document).ready(() => {

	getToolSearchForm()
	prependNameInput()
	setNameInputValue()
	registerNameInputEventHandlers()
	registerSearchFormEventHandlers()

})

const getToolSearchForm = () => {
	$toolSearchForm = $(`#${toolSearchFormId }`)

	return $toolSearchForm
}

const prependNameInput = () => {
	const $searchResultsContainer = getSearchResultsContainer()
	$nameInput = generateNameInput()

	$searchResultsContainer.prepend($nameInput)
}

const getSearchResultsContainer = () => {
	const $searchResultsContainer = $(`#${searchResultsContainerId}`)

	return $searchResultsContainer
}

const generateNameInput = () => {
	const $nameInput = $('<input>')

	addNameInputAttributes($nameInput)

	return $nameInput
}

const addNameInputAttributes = ($nameInput) => {
	for (const attr in nameInputAttributes) {
		$nameInput.attr(attr, nameInputAttributes[attr])
	}
}

const setNameInputValue = () => {
	const value = sourceNameInputValue()

	$nameInput.val(value)
}

const sourceNameInputValue = () => {
	const $valueContainer = $('[data-query-name]')
	const value = $valueContainer.data('query-name')

	return value
}

const registerNameInputEventHandlers = () => {
	$nameInput.on('keypress', submitToolSearchFormOnEnter)
}

const submitToolSearchFormOnEnter = (e) => {
	if (e.which == enterKeyCode) {
		submitToolSearchFormIncludingName()
	}
}

const registerSearchFormEventHandlers = () => {
	$toolSearchForm.on('click', submitToolSearchFormOnClick)
}

const submitToolSearchFormOnClick = (e) => {
	e.preventDefault()
	const targetType = $(e.target).attr('type')

	if (targetType === 'submit') {
		submitToolSearchFormIncludingName()
	}
}

const submitToolSearchFormIncludingName = (e) => {
	appendHiddenNameInput($toolSearchForm)
	$toolSearchForm.submit()
}

const appendHiddenNameInput = ($element) => {
	const $hiddenNameInput = generateHiddenNameInput()

	$element.append($hiddenNameInput)
}

const generateHiddenNameInput = () => {
	const $hiddenNameInput = generateNameInput()
	const value = $nameInput.val()

	$hiddenNameInput.attr('type', 'hidden')
	$hiddenNameInput.val(value)

	return $hiddenNameInput
}

