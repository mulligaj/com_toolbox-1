<?php
/*
 * HUBzero CMS
 *
 * Copyright 2005-2015 HUBzero Foundation, LLC.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * HUBzero is a registered trademark of Purdue University.
 *
 * @package   hubzero-cms
 * @author    Anthony Fuentes <fuentesa@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Toolbox\Helpers;

use App;
use User;

class AuthHelper
{

	/*
	 * Current user
	 *
	 * @var   object
	 */
	protected static $_currentUser = null;

	/*
	 * Redirects user unless user has permission to take given action
	 *
	 * @param    string   $action   Category of access
	 * @return   void
	 */
	public static function redirectUnlessAuthorized($action, $url = null, $message = null)
	{
		static::redirectIfGuest();

		$currentUser = static::_getCurrentUser();
		$isAuthorized = static::currentIsAuthorized($action);

		if (!$isAuthorized)
		{
			$message = isset($message) ? $message : static::_getDefaultRedirectMessage();
			$url = isset($url) ? $url : static::_getdefaultRedirectUrl();

			static::redirect($url, $message);
		}
	}

	/*
	 * Redirects to given URL unless the user is authenticated
	 *
	 * @param    string   $url    URL to redirect to
	 * @return   void
	 */
	public static function redirectIfGuest($url = null)
	{
		$currentUser = static::_getCurrentUser();
		$url = $url ? $url : static::_getDefaultGuestRedirectUrl();
		$message = static::_getDefaultGuestRedirectMessage();

		if ($currentUser->isGuest())
		{
			static::redirect($url, $message);
		}
	}

	/*
	 * Generates friendly forwarding URL param
	 *
	 * @return   string
	 */
	protected static function _friendlyForward()
	{
		$currentUrl = Route::url(Request::current());

		$friendlyForwardParam = 'return=' . base64_encode($currentUrl);

		return $friendlyForwardParam;
	}

	/*
	 * Returns current user
	 *
	 * @return   object
	 */
	protected static function _getCurrentUser()
	{
		if (static::$_currentUser === null)
		{
			$currentUser = User::getCurrentUser();
		}

		return $currentUser;
	}

	/*
	 * Redirects usedr to given URL and shows given message
	 *
	 * @param    string   $url           URL to redirect to
	 * @param    string   $langKey       Key corresponding to message to display
	 * @param    string   $messageType   Determines color of the message box
	 * @return   void
	 */
	protected static function redirect($url, $langKey, $messageType = 'warning')
	{
		$message = Lang::txt($langKey);

		App::redirect($url, $message, $messageType);
	}

}
