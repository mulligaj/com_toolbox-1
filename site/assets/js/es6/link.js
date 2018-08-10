
var TOOLBOX = TOOLBOX || {}

class Link {

	constructor({id}) {
		this.id = id
	}

	destroy() {
		const data = {id: this.id}

		const promise = Api.delete('/api/v1.0/toolbox/links/destroy', data)

		return promise
	}

}

TOOLBOX.Link = Link
