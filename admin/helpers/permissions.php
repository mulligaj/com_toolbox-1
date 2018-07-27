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

namespace Components\Toolbox\Admin\Helpers;

use Hubzero\Base\Object;
use User;

class Permissions
{

	/*
	 * Component name
	 *
	 * @var   string
	 */
	public static $extension = 'com_toolbox';

	/*
	 * Set of actions
	 *
	 * @var   array
	 */
	protected static $_actions = array(
		'admin',
		'manage',
		'create',
		'edit',
		'edit.state',
		'delete'
	);

	/*
	 * Gets a list of the actions that can be performed
	 *
	 * @param    string    $assetType   Asset type
	 * @param    integer   $assetId     Category ID
	 * @return   object
	 */
	public static function getActions($assetType = 'component', $assetId = 0)
	{
		$assetName  = static::_buildAssetName($assetType, $assetId);
		$result = new Object;

		foreach (static::$_actions as $action)
		{
			$action = "core.$action";
			$isAuthorized = User::authorise($action, $assetName);

			$result->set($action, $isAuthorized);
		}

		return $result;
	}

	protected static function _buildAssetName($assetType, $assetId)
	{
		$assetId = (int) $assetId;
		$assetName  = self::$extension;

		$assetName .= ".$assetType";

		if ($assetId)
		{
			$assetName .= ".$assetId";
		}

		return $assetName;
	}

}
