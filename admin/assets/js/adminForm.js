
var HUB = HUB || {}

HUB.adminForm = HUB.adminForm || {}

const adminForm = HUB.adminForm

adminForm.FORM_NAME = 'adminForm'
adminForm.CLEAR_SEARCH_BUTTON_ID = 'clear-search'
adminForm.MASTER_CHECKBOX_NAME = 'toggle'
adminForm.SEARCH_FIELD_ID = 'filter_search'
adminForm.RECORD_CHECKBOX_CLASS = 'record-checkbox'

adminForm.init = () => {
	adminForm.form = $(`form[name=${adminForm.FORM_NAME}]`)
	adminForm.clearSearchButton = $(`#${adminForm.CLEAR_SEARCH_BUTTON_ID}`)
	adminForm.masterCheckbox = $(`input[name="${adminForm.MASTER_CHECKBOX_NAME}"]`)
	adminForm.searchField = $(`#${adminForm.SEARCH_FIELD_ID}`)
	adminForm.recordCheckboxes = $(`.${adminForm.RECORD_CHECKBOX_CLASS}`)
}

adminForm.clearSearchField = () => {
	adminForm.searchField.val('')
}

adminForm.clearSearchHandler = () => {
	adminForm.clearSearchField()
	adminForm.submit()
}

adminForm.masterCheckboxHandler = () => {
	const $masterCheckbox = adminForm.masterCheckbox
	const masterIsChecked = $masterCheckbox.prop('checked')

	adminForm.recordCheckboxes.prop('checked', masterIsChecked)
	adminForm.setChecked(masterIsChecked, $masterCheckbox)
}

adminForm.submit = () => {
	adminForm.form.submit()
}

adminForm.checkboxHandler = (e) => {
	const $checkbox = $(e.target)
	const checked = $checkbox.prop('checked')

	adminForm.setChecked(checked, $checkbox)
}

adminForm.setChecked = (checked, $checkbox) => {
	isChecked(checked, $checkbox)
}

$(document).ready(() => {

	adminForm.init()

	adminForm.clearSearchButton.click(adminForm.clearSearchHandler)

	adminForm.recordCheckboxes.click(adminForm.checkboxHandler)

	adminForm.masterCheckbox.click(adminForm.masterCheckboxHandler)
})
