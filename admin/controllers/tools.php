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

namespace Components\Toolbox\Admin\Controllers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/admin/helpers/filterHelper.php";
require_once "$toolboxPath/admin/helpers/redirectHelper.php";
require_once "$toolboxPath/models/tool.php";

use \Components\Toolbox\Admin\Helpers\FilterHelper;
use \Components\Toolbox\Admin\Helpers\RedirectHelper;
use \Components\Toolbox\Admin\Helpers\Permissions;
use \Components\Toolbox\Models\Tool;
use Hubzero\Component\AdminController;
use Hubzero\Database\Query;

class Tools extends AdminController
{

	/*
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'list',
		'archived' => 'archived'
	];

	/*
	 * Administrator toolbar title
	 *
	 * @var  string
	 */
	protected static $_toolbarTitle = 'Toolbox';

	/*
	 * Returns tool list view
	 *
	 * @return   void
	 */
	public function listTask()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$filters = FilterHelper::getFilters($component, $controller);
		$permissions = Permissions::getActions('tool');

		$tools = Tool::all()
			->whereEquals('archived', 0);

		// filter tools by name
		if (!empty($filters['search']))
		{
			$tools->where('name', ' like ', "%{$filters['search']}%");
		}

		// sort tools based on given criteria
		$tools = $tools->order($filters['sort'], $filters['sort_Dir'])
			->paginated('limitstart', 'limit');

		$this->view
			->set('filters', $filters)
			->set('permissions', $permissions)
			->set('title', static::$_toolbarTitle)
			->set('tools', $tools)
			->display();
	}

	/*
	 * Archives given tools
	 *
	 * @return   void
	 */
	public function archiveTask()
	{
		Request::checkToken();

		$toolIds = Request::getArray('toolIds');

		$toolTable = (new Tool())->getTableName();

		$updateQuery = (new Query())
			->update($toolTable)
			->set(['archived' => 1])
			->whereIn('id', $toolIds);

		$toolsUpdated = $updateQuery->execute();

		if ($toolsUpdated)
		{
			$this->_successfulArchive();
		}
		else
		{
			$this->_failedArchive();
		}
	}

	/*
	 * Redirects to tools list w/ success message
	 *
	 * @return   void
	 */
	protected function _successfulArchive()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_TOOLS_ARCHIVE_SUCCESS'),
			'passed'
		);
	}

	/*
	 * Redirects to tools list w/ error message
	 *
	 * @return   void
	 */
	protected function _failedArchive()
	{
		$originUrl = Request::getString('origin');
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_ARCHIVE_FAILURE');

		Notify::error($errorMessage);

		App::redirect($originUrl);
	}

	/*
	 * Updates given tool to be published
	 *
	 * @return   void
	 */
	public function publishTask()
	{
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		$tool->set('published', 1);

		if ($tool->save())
		{
			$this->_successfulPublishUpdate();
		}
		else
		{
			$this->_failedPublishUpdate($tool);
		}
	}

	/*
	 * Updates given tool to not be published
	 *
	 * @return   void
	 */
	public function unpublishTask()
	{
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		$tool->set('published', 0);

		if ($tool->save())
		{
			$this->_successfulPublishUpdate();
		}
		else
		{
			$this->_failedPublishUpdate($tool);
		}
	}

	/*
	 * Handles successful update of a tools published status
	 *
	 * @return   void
	 */
	protected function _successfulPublishUpdate()
	{
		$this->_redirectToToolList();
	}

	/*
	 * Handles failed update of a tools published status
	 *
	 * @param   object   $tool   Tool object
	 * @return   void
	 */
	protected function _failedPublishUpdate($tool)
	{
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_PUBLISH_FAILURE') . '<br/>';

		foreach ($tool->getErrors() as $error)
		{
			$errorMessage .= "<br/>• $error";
		}

		Notify::error($errorMessage);

		$this->_redirectToToolList();
	}

	/*
	 * Redirects user to tool list
	 *
	 * @return   void
	 */
	protected function _redirectToToolList()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$toolListUrl = Route::url("/administrator/index.php?option=$component&controller=$controller");

		App::redirect($toolListUrl);
	}

	/*
	 * Returns archived tool list view
	 *
	 * @return   void
	 */
	public function archivedTask()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$filters = FilterHelper::getFilters($component, $controller);
		$permissions = Permissions::getActions('tool');

		$tools = Tool::all()
			->whereEquals('archived', 1);

		// filter tools by name
		if (!empty($filters['search']))
		{
			$tools->where('name', ' like ', "%{$filters['search']}%");
		}

		// sort tools based on given criteria
		$tools = $tools->order($filters['sort'], $filters['sort_Dir'])
			->paginated('limitstart', 'limit');

		$this->view
			->set('filters', $filters)
			->set('permissions', $permissions)
			->set('title', static::$_toolbarTitle)
			->set('tools', $tools)
			->display();
	}

	/*
	 * Un-archives given tools
	 *
	 * @return   void
	 */
	public function unarchiveTask()
	{
		Request::checkToken();

		$toolIds = Request::getArray('toolIds');

		$toolTable = (new Tool())->getTableName();

		$updateQuery = (new Query())
			->update($toolTable)
			->set(['archived' => 0])
			->whereIn('id', $toolIds);

		$toolsUpdated = $updateQuery->execute();

		if ($toolsUpdated)
		{
			$this->_successfulUnarchive();
		}
		else
		{
			$this->_failedUnarchive();
		}
	}

	/*
	 * Redirects to archived tools list w/ success message
	 *
	 * @return   void
	 */
	protected function _successfulUnarchive()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_TOOLS_UNARCHIVE_SUCCESS'),
			'passed'
		);
	}

	/*
	 * Redirects to archived tools list w/ error message
	 *
	 * @return   void
	 */
	protected function _failedUnarchive()
	{
		$originUrl = Request::getString('origin');
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_UNARCHIVE_FAILURE');

		Notify::error($errorMessage);

		App::redirect($originUrl);
	}

	/*
	 * Destroys the given tools
	 *
	 * @return   void
	 */
	public function destroyTask()
	{
		Request::checkToken();

		$toolIds = Request::getArray('toolIds');

		$toolTable = (new Tool())->getTableName();

		$destroyQuery = (new Query())
			->delete($toolTable)
			->whereIn('id', $toolIds);

		$toolsDestroyed = $destroyQuery->execute();

		if ($toolsDestroyed)
		{
			$this->_successfulDestroy();
		}
		else
		{
			$this->_failedDestroy();
		}
	}

	/*
	 * Handles successful destruction of given tool record(s)
	 *
	 * @return   void
	 */
	protected function _successfulDestroy()
	{
		$forwardingUrl = Request::getString('forward');
		$langKey = 'COM_TOOLBOX_TOOLS_DESTROY_SUCCESS';
		$notificationType = 'passed';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Handles failed destruction of given tool record(s)
	 *
	 * @return   void
	 */
	protected function _failedDestroy()
	{
		$originUrl = Request::getString('origin');
		$langKey = 'COM_TOOLBOX_TOOLS_DESTROY_FAILURE';
		$notificationType = 'error';

		RedirectHelper::redirectAndNotify($originUrl, $langKey, $notificationType);
	}

}
