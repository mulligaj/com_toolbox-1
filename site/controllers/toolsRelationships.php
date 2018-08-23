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

require_once "$toolboxPath/helpers/toolAuthHelper.php";
require_once "$toolboxPath/helpers/toolsRelationshipsFactory.php";
require_once "$toolboxPath/models/toolsRelationship.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Helpers\ToolAuthHelper;
use Components\Toolbox\Helpers\ToolsRelationshipsFactory;
use Components\Toolbox\Models\ToolsRelationship;
use Components\Toolbox\Models\Tool;
use Hubzero\Component\SiteController;

class ToolsRelationships extends SiteController
{

	/*
	 * Updates a tools relationships
	 *
	 * @return   void
	 */
	public function updateTask()
	{
		Request::checkToken();

		// get tool record to relate other tools to
		$toolId = Request::getInt('id');
		$tool = Tool::one($toolId);

		ToolAuthHelper::authorizeEditing($tool);

		// get IDs of related tools
		$relatedToolIds = Request::getArray('toolIds');

		if (count($relatedToolIds) > ToolsRelationship::maximum())
		{
			$errors = [Lang::txt('COM_TOOLBOX_RELATED_LIMIT_ERROR')];
			$this->_failedUpdate($errors, $relatedToolIds);
		}

		// attempt to create tools relationship records
		$updateResult = ToolsRelationshipsFactory::update($tool, $relatedToolIds);

		if ($updateResult->succeeded())
		{
			// unpublish tool if user not an admin
			$tool->unpublishIfNotAdmin();

			$this->_successfulUpdate();
		}
		else
		{
			$errors = ToolsRelationshipsFactory::parseUpdateErrors($updateResult);
			$this->_failedUpdate($errors, $relatedToolIds);
		}
	}

	/*
	 * Process successful update of tool's relationships
	 *
	 * @return   void
	 */
	protected function _successfulUpdate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_RELATED_UPDATES_SUCCESSFUL'),
			'passed'
		);
	}

	/*
	 * Process failed update of tool's relationships
	 *
	 * @param    array   $errors           Errors that occurred during update
	 * @param    array   $relatedToolIds   IDs of Tools that were to be associated
	 * @return   void
	 */
	protected function _failedUpdate($errors, $relatedToolIds)
	{
		$originUrl = Request::getString('origin');
		$originUrl .= '?' . http_build_query(['selectedToolsIds' => $relatedToolIds]);
		$errorsMessage = implode($errors, '<br><br>');

		Notify::error($errorsMessage);

		App::redirect($originUrl);
	}

}
