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

namespace Components\Toolbox\Site\Controllers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/authHelper.php";
require_once "$toolboxPath/helpers/linksFactory.php";

use Components\Toolbox\Helpers\AuthHelper;
use Components\Toolbox\Helpers\LinksFactory;
use Hubzero\Component\SiteController;

class Links extends SiteController
{

	/*
	 * Updates link records
	 *
	 * @return  void
	 */
	public function updateTask()
	{
		AuthHelper::redirectUnlessAuthorized('core.edit');
		Request::checkToken();

		// get tool ID
		$toolId = Request::getInt('id');

		// get posted link(s) data
		$linksData = Request::getArray('links');

		// add tool ID to link(s) data
		$linksData = array_map(function($linkData) use ($toolId) {
			$linkData['tool_id'] = $toolId;
			return $linkData;
		}, $linksData);

		// attempt to associate link(s) with tool
		$updateResult = LinksFactory::createOrUpdateMany($linksData);

		if ($updateResult->succeeded())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$errors = LinksFactory::parseCreateErrors($updateResult);
			$this->_failedUpdate($errors, $linksData);
		}
	}

	/*
	 * Process successful creation of Link records
	 *
	 * @return  void
	 */
	protected function _successfulUpdate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_LINK_UPDATES_SUCCESSFUL'),
			'passed'
		);
	}

	/*
	 * Process failed creation of Link records
	 *
	 * @return  void
	 */
	protected function _failedUpdate($errors, $linksData)
	{
		$originUrl = Request::getString('origin');
		$errorsMessage = implode($errors, '<br><br>');

		Notify::error($errorsMessage);

		App::redirect($originUrl);
	}

}
