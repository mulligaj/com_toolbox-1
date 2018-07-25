
var TOOLBOX = TOOLBOX || {}

TOOLBOX.toolSearchForm = TOOLBOX.toolSearchForm || {}

toolSearchForm = TOOLBOX.toolSearchForm

toolSearchForm.CARET_CLASS = 'caret'
toolSearchForm.CONTENT_CLASS = 'content'
toolSearchForm.MASTER_CARET_ID = 'master-caret'
toolSearchForm.ROW_CLASS = 'row'

toolSearchForm.init = () => {
	// Collect row content wrappers
	toolSearchForm.rowContentWrappers = $(`.${toolSearchForm.CONTENT_CLASS}`)

	// Collect carets
	toolSearchForm.carets = $(`.${toolSearchForm.CARET_CLASS}`)

	// Collect master caret
	toolSearchForm.masterCaret = $(`#${toolSearchForm.MASTER_CARET_ID}`)
}

toolSearchForm.caretHandler = (e) => {
	const $caret = $(e.target)
	const $rowContent = toolSearchForm.findCaretRowContent($caret)

	if ($rowContent.is(':visible')) {
		$rowContent.slideUp(null, () => {
			toolSearchForm.toggleCaretDirection($caret, $rowContent)
		})
	} else if ($rowContent.is(':hidden')) {
		$rowContent.slideDown(null, () => {
			toolSearchForm.toggleCaretDirection($caret, $rowContent)
		})
	}
}

toolSearchForm.toggleCaretDirection = ($caret, $rowContent = null) => {
	if (!$rowContent) {
		$rowContent =	toolSearchForm.findCaretRowContent($caret)
	}
	let html

	if ($rowContent.is(':visible')) {
		html = '&#x2303;'
	} else if ($rowContent.is(':hidden')) {
		html = '&#x2304;'
	}

	$caret.html(html)
}

toolSearchForm.findCaretRowContent = ($caret) => {
	const $row = $caret.closest(`.${toolSearchForm.ROW_CLASS}`)
	const $rowContent = $row.find(`.${toolSearchForm.CONTENT_CLASS}`)

	return $rowContent
}

toolSearchForm.masterCaretHandler = (e) => {
	const $masterCaret = toolSearchForm.masterCaret
	const $rowContentWrappers = toolSearchForm.rowContentWrappers
	const visibleKey = 'visible'
	const rowsVisible = !!$masterCaret.data(visibleKey)

	if (rowsVisible) {
		$rowContentWrappers.slideUp(null, () => {
			toolSearchForm.toggleAllCarets('&#xf0d7;', {[visibleKey]: false})
		})
	} else if (!rowsVisible) {
		$rowContentWrappers.slideDown(null, () => {
			toolSearchForm.toggleAllCarets('&#xf0d8;', {[visibleKey]: true})
		})
	}
}

toolSearchForm.toggleAllCarets = (html, data) => {
	const $masterCaret = toolSearchForm.masterCaret

	$masterCaret.html(html)
	$masterCaret.data(data)
	toolSearchForm.toggleAllCaretDirections()
}

toolSearchForm.toggleAllCaretDirections = () => {
	const $carets = toolSearchForm.carets

	$.each($carets, (_, caret) => {
		toolSearchForm.toggleCaretDirection($(caret))
	})
}

$(document).ready(() => {

	// initialize search form
	toolSearchForm.init()

	// add click handler to carets
	toolSearchForm.carets.click(toolSearchForm.caretHandler)

	// add click handler to master caret
	toolSearchForm.masterCaret.click(toolSearchForm.masterCaretHandler)

})

