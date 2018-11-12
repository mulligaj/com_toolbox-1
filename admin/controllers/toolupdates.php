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
require_once "$toolboxPath/helpers/toolActivityHelper.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Admin\Helpers\FilterHelper;
use Components\Toolbox\Admin\Helpers\Permissions;
use Components\Toolbox\Helpers\ToolActivityHelper;
use Components\Toolbox\Models\Tool;
use Hubzero\Component\AdminController;

class ToolUpdates extends AdminController
{

	/*
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'list',
	];

	/*
	 * Administrator toolbar title
	 *
	 * @var  string
	 */
	protected static $_toolbarTitle = 'Toolbox';

	/*
	 * Returns tool updates list view
	 *
	 * @return   void
	 */
	public function listTask()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$filters = FilterHelper::getFilters($component, $controller);
		$permissions = Permissions::getActions('tool');

		$toolId = Request::getInt('id');
		$tool = Tool::one($toolId);
		$toolUpdates = ToolActivityHelper::getUpdates($toolId);
		$toolbarTitle = Lang::txt('COM_TOOLBOX_UPDATES_TITLE', $tool->get('name'));

		$toolUpdates = $toolUpdates->order($filters['sort'], $filters['sort_Dir'])
			->paginated('limitstart', 'limit');

		$this->view
			->set('filters', $filters)
			->set('permissions', $permissions)
			->set('title', $toolbarTitle)
			->set('toolId', $toolId)
			->set('toolUpdates', $toolUpdates)
			->display();
	}

}
