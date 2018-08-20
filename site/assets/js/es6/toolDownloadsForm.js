
var TOOLBOX = TOOLBOX || {}

TOOLBOX.downloadsForm = TOOLBOX.downloadsForm || {}

downloadsForm = TOOLBOX.downloadsForm

downloadsForm.ANCHOR_ID = 'uploader-anchor'
downloadsForm.DELETE_FORM_ID = 'delete-form'
downloadsForm.DOWNLOADS_LIST_ID = 'downloads'
downloadsForm.DROP_AREA_CLASS ='qq-upload-drop-area'
downloadsForm.HUB_FORM_ID = 'hubForm'
downloadsForm.UPLOADS_LIST_CLASS ='qq-upload-list'
downloadsForm.UPLOADS_LIST_ID = 'upload-list'
downloadsForm.UPLOAD_BUTTON_CLASS ='qq-upload-button'
downloadsForm.UPLOADER_CLASS = 'qq-uploader'
downloadsForm.UPLOADER_CONTAINER_ID = 'file-uploader'

downloadsForm.init = () => {
	downloadsForm.container = $(`#${downloadsForm.UPLOADER_CONTAINER_ID}`)
	downloadsForm.deleteForm = $(`#${downloadsForm.DELETE_FORM_ID}`)
	downloadsForm.downloadsList = $(`#${downloadsForm.DOWNLOADS_LIST_ID}`)

	downloadsForm.hideDeleteForm()
	downloadsForm.clearUploaderContainer()
	downloadsForm.insertUploaderAnchors()
	downloadsForm.getUploaderAction()
	downloadsForm.renderUploader()
}

downloadsForm.hideDeleteForm = () => {
	const downloadsCount = downloadsForm.downloadsList.find('li').length

	if (downloadsCount <= 0) {
		downloadsForm.deleteForm.hide()
	}
}

downloadsForm.clearUploaderContainer = () => {
	downloadsForm.container.html('')
}

downloadsForm.insertUploaderAnchors = () => {
	const $uploaderAnchor = $(`<div id="${downloadsForm.ANCHOR_ID}"></div>`)
	const $listAnchor = $(`<div id="${downloadsForm.UPLOADS_LIST_ID}"></div>`)

	downloadsForm.container.append($uploaderAnchor)
	downloadsForm.container.append($listAnchor)
}

downloadsForm.getUploaderAction = () => {
	if (!downloadsForm.action) {
		const $hubForm = $(`#${downloadsForm.HUB_FORM_ID}`)
		downloadsForm.action = $hubForm.attr('action')
	}

	return downloadsForm.action
}

downloadsForm.renderUploader = () => {
	downloadsForm.anchor = $(`#${downloadsForm.ANCHOR_ID}`)
	downloadsForm.hasAnchor = !!downloadsForm.anchor.length
	downloadsForm.uploadsList = $(`#${downloadsForm.UPLOADS_LIST_ID}`)

	if (!downloadsForm.hasAnchor) { return;	}

	const action = `${downloadsForm.action}?no_html=1`
	const template = downloadsForm.getUploaderTemplate()

	const uploader = new qq.FileUploader({
		element: downloadsForm.anchor[0],
		action,
		multiple: true,
		debug: true,
		template,
		onComplete: downloadsForm.onUploadComplete
	})
}

downloadsForm.onUploadComplete = (_, fileName, response) => {
	downloadsForm.switchLists(response, fileName)
	downloadsForm._notifyUploadResult(response, fileName)
}

downloadsForm.switchLists = (response, fileName) => {
	downloadsForm.removeUploadLi(fileName)
	downloadsForm.addToDeleteList(response, fileName)
}

downloadsForm.removeUploadLi = (fileName) => {
	const $uploadLi = downloadsForm.findUploadLi(fileName)

	$uploadLi.fadeOut(1000)
}

downloadsForm.addToDeleteList = (response, fileName) => {
	const downloadId = response.id
	const toolId = response.tool_id
	const uploadSucceeded = response.success
	const $deleteLi = downloadsForm.generateDeleteLi(downloadId, toolId, fileName)

	if (uploadSucceeded)
	{
		if (downloadsForm.deleteForm.is(':hidden'))
		{
			downloadsForm.deleteForm.show()
		}

		downloadsForm.downloadsList.append($deleteLi).hide().fadeIn(1000)
	}
}

downloadsForm.generateDeleteLi = (downloadId, toolId, fileName) => {
	const $deleteLi = $(`
		<li>
			<input type="checkbox" name="downloads" value="${downloadId}">
			<a href="/app/site/toolbox/downloads/${toolId}/${fileName}" download>
				${fileName}
			</a>
		</li>
	`)

	return $deleteLi
}

downloadsForm.findUploadLi = (fileName) => {
	const $uploadsList = $(`.${downloadsForm.UPLOADS_LIST_CLASS}`)
	const $uploadSpan = $uploadsList.find(`span:contains('${fileName}')`)
	const $uploadLi = $uploadSpan.closest('li')

	return $uploadLi
}

downloadsForm._notifyUploadResult = (response, fileName) => {
	const uploadSucceeded = response.success
	let errorMessage, errors

	if (uploadSucceeded) {
		Notify.success('Upload(s) succeeded')
	} else {
		errorMessage = `The following errors occurred while attempting to upload ${fileName}:<br/><br/>`
		errors = response.errors

		errors.forEach((error) => {
			errorMessage += `&bull; ${error}<br/>`
		})

		Notify.error(errorMessage)
	}
}

downloadsForm.getUploaderTemplate = () => {
	const uploaderClass = downloadsForm.UPLOADER_CLASS
	const uploadButtonClass = downloadsForm.UPLOAD_BUTTON_CLASS
	const dropAreaClass = downloadsForm.DROP_AREA_CLASS
	const listClass = downloadsForm.UPLOADS_LIST_CLASS

	const template = `<div class="${uploaderClass}">
		<div class="${uploadButtonClass}"><span>Click or drop file(s)</span></div>
		<div class="${dropAreaClass}"><span>Click or drop file(s)</span></div>
		<ul class="${listClass}"></ul>
	</div>`

	return template
}

$(document).ready(() => {
	Hubzero.initApi(() => {

		// initialize downloads form
		downloadsForm.init()

	})
})
