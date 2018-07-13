
var TOOLBOX = TOOLBOX || {}

TOOLBOX.linksForm = TOOLBOX.linksForm || {}

linksForm = TOOLBOX.linksForm

linksForm.ADD_LINK_BUTTON_ID = 'add-link'
linksForm.ADD_LINK_BUTTON_WRAPPER = 'add-link-button-wrapper'
linksForm.DELETE_BUTTON_CLASS ='btn-danger'
linksForm.DELETE_WRAPPER_CLASS ='delete-wrapper'
linksForm.LINK_PANEL_CLASS = 'link'
linksForm.LINKS_WRAPPER_ID = 'links-wrapper'

linksForm.init = () => {
	// count of links on the page
	const $prefillLinks = $(`.${linksForm.LINK_PANEL_CLASS}`)
	linksForm.linksCount = $prefillLinks.length

	// insert Delete Link button(s)
	linksForm.addLinkDeleteButton()

	// insert Add Link button & save reference
	linksForm.addLinkAddButton()
	linksForm.addLinkButton = $(`#${linksForm.ADD_LINK_BUTTON_ID}`)

	// save reference to links wrapper
	linksForm.linksWrapper = $(`#${linksForm.LINKS_WRAPPER_ID}`)
}

linksForm.appendLinkPanelTo = ($targetElement) => {
	$linkPanel = linksForm.createLinkPanel()

	$targetElement.append($linkPanel)
}

linksForm.addLinkAddButton = () => {
	const $addLinkButton = $(
		`<button id="${linksForm.ADD_LINK_BUTTON_ID}" class="btn">
			Add Link
		</button>`
	)

	$(`#${linksForm.ADD_LINK_BUTTON_WRAPPER}`).append($addLinkButton)
}

linksForm.addLinkHandler = (e) => {
	e.preventDefault()

	const $linksWrapper = linksForm.linksWrapper

	linksForm.appendLinkPanelTo($linksWrapper)
}

linksForm.addLinkDeleteButton = () => {
	const $deleteWrappers = $(`.${linksForm.DELETE_WRAPPER_CLASS}`)
	const $deleteLinkButton = $(`<div class="btn ${linksForm.DELETE_BUTTON_CLASS}">✖</div>`)

	$deleteWrappers.append($deleteLinkButton)
}

linksForm.deleteLinkHandler = (e) => {
	const $target = $(e.target)

	if (!$target.hasClass(linksForm.DELETE_BUTTON_CLASS)) {
		return
	}

	const $linkPanel = $target.closest(`.${linksForm.LINK_PANEL_CLASS}`)
	const linkId = $linkPanel.data('id')

	if (linkId > 0) {
		linksForm.deleteLinkAndRemovePanel(linkId, $linkPanel)
	} else {
		linksForm.removeLinkPanel($linkPanel)
	}
}

linksForm.deleteLinkAndRemovePanel = (linkId, $linkPanel) => {
	const link = new TOOLBOX.Link({id: linkId})

	link.destroy().then(
		(response) => {
			let errors
			const status = response.status

			if (status === 'success') {
				$linkPanel.remove()
				Notify.success('Link deleted.')
			}
			else {
				Notify.error('There was an error attempting to delete the link.')
			}
		},
		(response) => {
			Notify.error('There was an error attempting to communicate with the server.')
		}
	)
}

linksForm.removeLinkPanel = ($linkPanel) => {
	$linkPanel.remove()
}

$(document).ready(() => {
	Hubzero.initApi(() => {

		// initialize links form
		linksForm.init()

		// add click handler to the Add Link button
		linksForm.addLinkButton.click(linksForm.addLinkHandler)

		// add click handler for Delete Link button(s)
		linksForm.linksWrapper.click(linksForm.deleteLinkHandler)

	})
})
