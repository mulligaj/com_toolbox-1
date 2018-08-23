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

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/authHelper.php";

use Components\Toolbox\Helpers\AuthHelper;
use User;

class ToolAuthHelper extends AuthHelper
{

	/*
	 * Determines if current user has necessary permission
	 *
	 * @param    string   $action   Category of access
	 * @return   bool
	 */
	public static function currentIsAuthorized($action)
	{
		$component = static::_getComponentName();

		$isAuthorized = User::authorize($action, $component);

		return $isAuthorized;
	}

	/*
	 * Returns owning component's name
	 *
	 * @return   string
	 */
	protected static function _getComponentName()
	{
		$componentName = 'com_toolbox';

		return $componentName;
	}

	/*
	 * Returns default redirect message
	 *
	 * @return   string
	 */
	protected static function _getDefaultRedirectMessage()
	{
		$messageKey = 'COM_TOOLBOX_AUTH_ADMINS_ONLY';
		$message = Lang::txt($messageKey);

		return $message;
	}

	/*
	 * Returns default redirect URL
	 *
	 * @return   string
	 */
	protected static function _getDefaultRedirectUrl()
	{
		$url = Route::url('/toolbox/tools');

		return $url;
	}

	/*
	 * Returns default guest redirect URL
	 *
	 * @return   string
	 */
	protected static function _getDefaultGuestRedirectUrl()
	{
		$url = '/login?' . static::_friendlyForward();

		return $url;
	}

	/*
	 * Returns default guest redirect URL
	 *
	 * @return   string
	 */
	protected static function _getDefaultGuestRedirectMessage()
	{
		$messageKey = 'COM_TOOLBOX_AUTH_REQUEST_SIGN_IN';
		$message = Lang::txt($messageKey);

		return $message;
	}

	/*
	 * Redirects user if not authorized to edit given tool
	 *
	 * @param    object   $tool   Tool
	 * @return   void
	 */
	public static function authorizeEditing($tool)
	{
		$userCanEdit = static::userCanEdit($tool);

		if (!$userCanEdit)
		{
			ToolAuthHelper::redirectUnlessAuthorized('core.edit');
		}
	}

	/*
	 * Determines if user is authorized to edit given tool
	 *
	 * @param    object   $tool   Tool
	 * @return   bool
	 */
	public static function userCanEdit($tool)
	{
		$canEdit = static::currentIsAuthorized('core.edit');
		$canEditOwn = static::currentIsAuthorized('core.edit.own');
		$ownsTool = $tool->get('user_id') == User::get('id');

		$userCanEdit = $canEdit || ($canEditOwn && $ownsTool);

		return $userCanEdit;
	}

	/*
	 * Redirects user if not authorized to view given tool
	 *
	 * @param    object   $tool   Tool
	 * @return   void
	 */
	public static function authorizeViewing($tool)
	{
		$userCanView = static::userCanView($tool);

		if (!$userCanView)
		{
			ToolAuthHelper::redirectUnlessAuthorized('core.unpublished');
		}
	}

	/*
	 * Determines if user is authorized to view given tool
	 *
	 * @param   object   $tool   Tool
	 * @return   bool
	 */
	public static function userCanView($tool)
	{
		$canView = $tool->get('published') || static::currentIsAuthorized('core.unpublished');
		$canViewOwn = static::currentIsAuthorized('core.unpublished.own');
		$ownsTool = $tool->get('user_id') == User::get('id');

		$userCanView = $canView || ($canViewOwn && $ownsTool);

		return $userCanView;
	}

}
