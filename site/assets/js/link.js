
var TOOLBOX = TOOLBOX || {}

class Link {

	constructor({id}) {
		this.id = id
	}

	destroy() {
		const data = {id: this.id}

		const promise = Api.delete('/toolbox', data) // AF: update

		return promise
	}

}

TOOLBOX.Link = Link
