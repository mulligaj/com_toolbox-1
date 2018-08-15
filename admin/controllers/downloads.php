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
require_once "$toolboxPath/models/download.php";

use \Components\Toolbox\Admin\Helpers\FilterHelper;
use \Components\Toolbox\Admin\Helpers\RedirectHelper;
use \Components\Toolbox\Admin\Helpers\Permissions;
use \Components\Toolbox\Models\Download;
use Hubzero\Component\AdminController;
use Hubzero\Database\Query;

class Downloads extends AdminController
{

	/*
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'list'
	];

	/*
	 * Administrator toolbar title
	 *
	 * @var  string
	 */
	protected static $_toolbarTitle = 'Toolbox';

	/*
	 * Returns downloads list view
	 *
	 * @return   void
	 */
	public function listTask()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$filters = FilterHelper::getFilters($component, $controller);
		$permissions = Permissions::getActions();

		$downloads = Download::all()
			->whereEquals('desynchronized', 1);

		// sort downloads based on given criteria
		$downloads = $downloads->order($filters['sort'], $filters['sort_Dir'])
			->paginated('limitstart', 'limit');

		$this->view
			->set('downloads', $downloads)
			->set('filters', $filters)
			->set('permissions', $permissions)
			->set('title', static::$_toolbarTitle)
			->display();
	}

	/*
	 * Changes the desynchronized status of given download(s) to 0
	 *
	 * @return   void
	 */
	public function synchronizeTask()
	{
		$downloadsIds = Request::getArray('downloadsIds');
		$downloadsTable = (new Download())->getTableName();

		$updateQuery = (new Query())
			->update($downloadsTable)
			->set(['desynchronized' => 0])
			->whereIn('id', $downloadsIds);

		$downloadsSynchronized = $updateQuery->execute();

		if ($downloadsSynchronized)
		{
			$this->_successfulSynchronization();
		}
		else
		{
			$this->_failedSynchronization();
		}
	}

	/*
	 * Handles successful synchronization of download record(s)
	 *
	 * @return   void
	 */
	protected function _successfulSynchronization()
	{
		$forwardingUrl = Request::getString('forward');
		$langKey = 'COM_TOOLBOX_DOWNLOADS_SYNCHRONIZATION_SUCCESS';
		$notificationType = 'passed';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Handles failed synchronization of download record(s)
	 *
	 * @return   void
	 */
	protected function _failedSynchronization()
	{
		$originUrl = Request::getString('origin');
		$langKey = 'COM_TOOLBOX_DOWNLOADS_SYNCHRONIZATION_FAILURE';
		$notificationType = 'error';

		RedirectHelper::redirectAndNotify($originUrl, $langKey, $notificationType);
	}

}
