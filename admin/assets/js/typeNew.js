
var HUB = HUB || {}

HUB.toolbox = HUB.toolbox || {}

HUB.toolbox.newForm = HUB.toolbox.newForm || {}

const newForm = HUB.toolbox.newForm

newForm.DESCRIPTION_FIELD_NAME = 'type[description]'
newForm.FORM_ID = 'new-form'
newForm.SUBMIT_BUTTON_ID = 'new-form-submit'

newForm.init = () => {
	newForm.descriptionField = $(`input[name="${newForm.DESCRIPTION_FIELD_NAME}"]`)
	newForm.form = $(`#${newForm.FORM_ID}`)
	newForm.submitButton = $(`#${newForm.SUBMIT_BUTTON_ID}`)
}

newForm.submitButtonHandler = (e) => {
	e.preventDefault()

	const typeListUrl = "/administrator/index.php?option=com_toolbox&controller=tooltypes&task=list"

	if (newForm.validate())
	{
		newForm.submit()
		window.top.setTimeout(`window.parent.location='${typeListUrl}'`, 100);
	}
	else
	{
		alert('Type description can\'t be empty')
	}

}

newForm.validate = () => {
	const descriptionValue = newForm.descriptionField.val()
	const isValid = !!descriptionValue

	return isValid
}

newForm.submit = () => {
	newForm.form.submit()
}

$(document).ready(() => {

	newForm.init()

	newForm.submitButton.click(newForm.submitButtonHandler)

})
