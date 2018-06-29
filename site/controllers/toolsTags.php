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

require_once "$toolboxPath/helpers/toolsTagsFactory.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Helpers\ToolsTagsFactory;
use Components\Toolbox\Models\Tool;
use Hubzero\Component\SiteController;

class ToolsTags extends SiteController
{

	/*
	 * Updates a tools tags
	 *
	 * @return   void
	 */
	public function updateTask()
	{
		Request::checkToken();

		// get tool record to associate tags with
		$toolId = Request::getInt('id');
		$tool = Tool::one($toolId);

		// get Tags IDs
		$selectedTagsIds = Request::getArray('tagsIds');

		// attempt to associate tool with tags
		$updateResult = ToolsTagsFactory::update($tool, $selectedTagsIds);

		if ($updateResult->succeeded())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$errors = ToolsTagsFactory::parseUpdateErrors($updateResult);
			$this->_failedUpdate($errors, $selectedTagsIds);
		}
	}

	/*
	 * Process successful update of tool's tags
	 *
	 * @return   void
	 */
	protected function _successfulUpdate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_TAGS_UPDATES_SUCCESSFUL'),
			'passed'
		);
	}

	/*
	 * Process failed update of tool's tags
	 *
	 * @param    array   $errors            Errors that occurred during update
	 * @param    array   $selectedTagsIds   IDs of Tags that were to be associated
	 * @return   void
	 */
	protected function _failedUpdate($errors, $selectedTagsIds)
	{
		$originUrl = Request::getString('origin');
		$originUrl .= '?' . http_build_query(['selectedTagsIds' => $selectedTagsIds]);
		$errorsMessage = implode($errors, '<br><br>');

		Notify::error($errorsMessage);

		App::redirect($originUrl);
	}

}
