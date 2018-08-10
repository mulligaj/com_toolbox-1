'use strict';

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var TOOLBOX = TOOLBOX || {};

var Link = function () {
	function Link(_ref) {
		var id = _ref.id;

		_classCallCheck(this, Link);

		this.id = id;
	}

	_createClass(Link, [{
		key: 'destroy',
		value: function destroy() {
			var data = { id: this.id };

			var promise = Api.delete('/api/v1.0/toolbox/links/destroy', data);

			return promise;
		}
	}]);

	return Link;
}();

TOOLBOX.Link = Link;