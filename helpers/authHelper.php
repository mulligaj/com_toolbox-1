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

use User;

class AuthHelper
{

	/*
	 * Toolbox component name
	 *
	 * @var   string
	 */
	protected static $_componentName = 'com_toolbox';

	/*
	 * Redirects user unless the user is in the given access group
	 *
	 * @param    string   $accessGroup   User access group
	 * @return   void
	 */
	public static function redirectUnlessAuthorized($accessGroup)
	{
		$component = static::$_componentName;
		$currentUser = User::getCurrentUser();

		static::redirectIfGuest($currentUser);

		// check if user is in toolbox admin access group
		$isAuthorized = User::authorize($accessGroup, $component);

		if (!$isAuthorized)
		{
			$url = Route::url('/toolbox/tools');

			static::redirect($url, 'COM_TOOLBOX_AUTH_ADMINS_ONLY');
		}
	}

	/*
	 * Redirects to given URL unless the user is authenticated
	 *
	 * @param    object   $user   Hub user
	 * @param    string   $url    URL to redirect to
	 * @return   void
	 */
	public static function redirectIfGuest($user = null, $url = null)
	{
		$user = $user ? $user : User::getCurrentUser();
		$url = $url ? $url : '/login?' . static::_friendlyForward();
		$langKey = 'COM_TOOLBOX_AUTH_REQUEST_SIGN_IN';

		if ($user->isGuest())
		{
			static::redirect($url, $langKey);
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

		\App::redirect($url, $message, $messageType);
	}

}
