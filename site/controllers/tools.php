<?php
/**
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

require_once Component::path('com_toolbox') . '/models/tool.php';
require_once Component::path('com_toolbox') . '/models/toolType.php';

use Components\Toolbox\Models\Tool;
use Components\Toolbox\Models\ToolType;
use Hubzero\Component\SiteController;

class Tools extends SiteController
{

	/**
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'newBasic'
	];

	/**
	 * Attribute validation
	 *
	 * @var  array
	 */
	protected $rules = [
		'name' => 'notempty',
		'minimum_participants' => 'positive',
		'suggested_participants' => 'positive',
		'maximum_participants' => 'positive',
		'duration' => 'positive',
		'cost' => 'positive',
		'source' => 'notempty'
	];

	/**
	 * Return the basic info page of the tool creation process
	 *
	 * @param		Hubzero\Relational	$tool	    Tool instance
	 * @param		array	              $typeIds	Tools type IDs
	 * @return  void
	 */
	public function newBasicTask($tool = null, $toolsTypeIds = [])
	{
		$tool = $tool ? $tool : Tool::blank();
		$types = ToolType::all();

		$this->view
			->set('tool', $tool)
			->set('toolsTypeIds', $toolsTypeIds)
			->set('types', $types);

		$this->view->display();
	}

	/**
	 * Create a tool record
	 *
	 * @return  void
	 */
	public function createTask()
	{
		Request::checkToken();

		// instantiate tool
		$tool = Tool::blank();

		// get posted data
		$data = Request::getArray('tool');

		// calculate duration as minutes
		$data['duration'] = $data['duration'] + $data['duration_hours'] * 60;
		unset($data['duration_hours']);

		// get IDs of types to associate tool with
		$typeIds = isset($data['types']) ? $data['types'] : [];
		unset($data['types']);

		// set tool attributes
		$tool->set($data);

		if ($tool->save())
		{
			$this->_successfulCreate($tool->get('id'));
		}
		else
		{
			$this->_failedCreate($tool, $typeIds);
		}
	}

	/**
	 * Process successful creation of a tool record
	 *
	 * @param		integer		$toolId		ID for newly created Tool record
	 * @return  void
	 */
	protected function _successfulCreate($toolId)
	{
		$url = Route::url(
			"index.php?option=$this->_option&controller=$this->_controller&task=editframeworks&id=$toolId",
			false
		);

		App::redirect(
			$url,
			'Tool created.',
			'passed'
		);
	}

	/**
	 * Process failed creation of a tool record
	 *
	 * @param		Hubzero\Relational	$tool	    Tool instance
	 * @param		array	              $typeIds	Tools type IDs
	 * @return  void
	 */
	protected function _failedCreate($tool, $typeIds)
	{
		$this->setView(null, 'newbasic');
		$this->newBasicTask($tool, $typeIds);
	}

	/**
	 * Return the frameworks info page of the tool update process
	 *
	 * @return  void
	 */
	public function editFrameworksTask()
	{
		$id = Request::getInt('id');
		$tool = Tool::one($id);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

}
