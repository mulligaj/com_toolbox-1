'use strict';

var _extends = Object.assign || function (target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i]; for (var key in source) { if (Object.prototype.hasOwnProperty.call(source, key)) { target[key] = source[key]; } } } return target; };

var _createClass = function () { function defineProperties(target, props) { for (var i = 0; i < props.length; i++) { var descriptor = props[i]; descriptor.enumerable = descriptor.enumerable || false; descriptor.configurable = true; if ("value" in descriptor) descriptor.writable = true; Object.defineProperty(target, descriptor.key, descriptor); } } return function (Constructor, protoProps, staticProps) { if (protoProps) defineProperties(Constructor.prototype, protoProps); if (staticProps) defineProperties(Constructor, staticProps); return Constructor; }; }();

function _classCallCheck(instance, Constructor) { if (!(instance instanceof Constructor)) { throw new TypeError("Cannot call a class as a function"); } }

var Notify = function () {
	function Notify() {
		_classCallCheck(this, Notify);
	}

	_createClass(Notify, null, [{
		key: 'error',
		value: function error(message) {
			this._notification(message, 'error');
		}
	}, {
		key: 'success',
		value: function success(message) {
			this._notification(message, 'success');
		}
	}, {
		key: 'warn',
		value: function warn(message) {
			this._notification(message, 'warn');
		}
	}, {
		key: '_notification',
		value: function _notification(message, type) {
			var color = this._getNotificationColor(type);
			var notificationModal = new NotificationModal({ text: message });
			notificationModal.addCss({ 'background-color': color });
			var $modalElement = notificationModal.render();

			this.displayNotification($modalElement);
		}
	}, {
		key: '_getNotificationColor',
		value: function _getNotificationColor(type) {
			var typeColorMappings = {
				success: '#A3CA60',
				warn: '#FDD023',
				error: '#CC0000'
			};
			var typeColor = typeColorMappings[type];

			return typeColor;
		}
	}, {
		key: 'displayNotification',
		value: function displayNotification($notification) {
			$('#content').append($notification);
			setTimeout(function () {
				$notification.hide();
			}, 5000);
		}
	}]);

	return Notify;
}();

var NotificationModal = function () {
	function NotificationModal(_ref) {
		var css = _ref.css,
		    text = _ref.text;

		_classCallCheck(this, NotificationModal);

		this.css = css || {
			'border-radius': '3px',
			color: '#FFFFFF',
			'font-size': '1.25em',
			'font-weight': '500',
			margin: '0 -15em 0 0',
			'min-height': '4em',
			'min-width': '20em',
			'max-width': '40em',
			position: 'fixed',
			right: '50%',
			'text-align': 'center',
			top: '115px',
			width: '30em',
			'z-index': '50000'
		};
		this.html = text;
	}

	_createClass(NotificationModal, [{
		key: 'render',
		value: function render() {
			var $element = $('<div>');

			this._applyStyles($element);
			this._addHtml($element);
			this._addCloseButton($element);
			this._addEventHandlers($element);

			return $element;
		}
	}, {
		key: '_addCloseButton',
		value: function _addCloseButton($element) {
			var $closeButton = $('<div>');

			$closeButton.html('×');
			$closeButton.attr('id', 'close-notify');
			$closeButton.css({
				cursor: 'pointer',
				'font-size': '1.25em',
				'font-weight': 'bold',
				margin: '0 0 .2em 0',
				padding: '.25em .5em 0 0',
				'text-align': 'right'
			});
			this.closeButton = $closeButton;

			$element.prepend($closeButton);
		}
	}, {
		key: '_applyStyles',
		value: function _applyStyles($element) {
			var _iteratorNormalCompletion = true;
			var _didIteratorError = false;
			var _iteratorError = undefined;

			try {
				for (var _iterator = Object.keys(this.css)[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
					var key = _step.value;

					$element.css(key, this.css[key]);
				}
			} catch (err) {
				_didIteratorError = true;
				_iteratorError = err;
			} finally {
				try {
					if (!_iteratorNormalCompletion && _iterator.return) {
						_iterator.return();
					}
				} finally {
					if (_didIteratorError) {
						throw _iteratorError;
					}
				}
			}
		}
	}, {
		key: '_addHtml',
		value: function _addHtml($element) {
			$element.html(this.html);
		}
	}, {
		key: '_addEventHandlers',
		value: function _addEventHandlers($element) {
			var $closeButton = this.closeButton;

			var hideNotification = function hideNotification(e) {
				if ($(e.target).attr('id') == $closeButton.attr('id')) {
					$element.hide();
				}
			};

			$element.click(hideNotification);
		}
	}, {
		key: 'addCss',
		value: function addCss(newCss) {
			var combinedCss = _extends({}, this.css, newCss);

			this.css = combinedCss;
		}
	}]);

	return NotificationModal;
}();