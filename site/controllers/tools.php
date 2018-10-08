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
$tagsPath = PATH_CORE . '/components/com_tags';

require_once "$toolboxPath/models/link.php";
require_once "$toolboxPath/models/tool.php";
require_once "$toolboxPath/models/toolType.php";
require_once "$toolboxPath/helpers/toolAuthHelper.php";
require_once "$toolboxPath/helpers/query.php";
require_once "$toolboxPath/helpers/toolsTypesFactory.php";
require_once "$toolboxPath/helpers/toolUpdateHelper.php";
require_once "$tagsPath/models/tag.php";

use Components\Toolbox\Models\Link;
use Components\Toolbox\Models\Tool;
use Components\Toolbox\Models\ToolType;
use Components\Toolbox\Helpers\Query;
use Components\Toolbox\Helpers\ToolAuthHelper;
use Components\Toolbox\Helpers\ToolsTypesFactory;
use Components\Toolbox\Helpers\ToolUpdateHelper;
use Components\Tags\Models\Tag;
use Hubzero\Component\SiteController;

class Tools extends SiteController
{

	/*
	 * Parameter whitelist
	 *
	 * @var   array
	 */
	protected static $paramWhitelist = [
		'name', 'source', 'external_cost',
		'duration', 'duration_hours', 'materials', 'notes', 'learning_objectives', 'links',
		'kinesthetic', 'subgroup_size', 'published', 'archived',
		'self_awareness', 'openness', 'communication', 'empathy', 'curiosity', 'worldview',
		'denial', 'polarization', 'minimization', 'acceptance',
		'self', 'other', 'emotions', 'bridging',
		'friendship', 'teamwork', 'mentorship', 'diversity_inclusion', 'leadership'
	];

	/*
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'list'
	];

	/*
	 * Return the basic info page of the tool creation process
	 *
	 * @param   Hubzero\Relational  $tool     Tool instance
	 * @param   array               $typeIds  IDs of types to associate
	 * @return  void
	 */
	public function newTask($tool = null, $typeIds = [])
	{
		ToolAuthHelper::redirectUnlessAuthorized('core.create');

		$tool = $tool ? $tool : Tool::blank();
		$types = ToolType::all()
			->whereEquals('archived', 0);

		$this->view
			->set('tool', $tool)
			->set('toolsTypeIds', $typeIds)
			->set('types', $types);

		$this->view->display();
	}

	/*
	 * Create a tool record
	 *
	 * @return  void
	 */
	public function createTask()
	{
		ToolAuthHelper::redirectUnlessAuthorized('core.create');
		Request::checkToken();

		// instantiate tool
		$tool = Tool::blank();

		// get posted tool data
		$toolData = Request::getArray('tool');

		// add user ID to tool data
		$toolData['user_id'] = User::get('id');

		// calculate duration as minutes
		$toolData['duration'] = $toolData['duration'] + $toolData['duration_hours'] * 60;
		unset($toolData['duration_hours']);

		// get IDs of types to associate tool with
		$typeIds = Request::getArray('types');

		// set tool attributes
		$tool->set($toolData);

		if ($tool->save())
		{
			$originStep = Request::getString('step');
			$this->_successfulCreate($tool->get('id'), $typeIds, $originStep);
		}
		else
		{
			$this->_failedCreate($tool, $typeIds);
		}
	}

	/*
	 * Renders the basic info page of the tool update process
	 *
	 * @param   Hubzero\Relational  $tool     Tool instance
	 * @param   array               $typeIds  IDs of types to associate
	 * @return  void
	 */
	public function editBasicTask($tool = null, $typeIds = null)
	{
		$id = Request::getInt('id');
		$tool = $tool ? $tool : Tool::one($id);
		$typeIds = $typeIds ? $typeIds : $tool->typeIds();
		$types = ToolType::all()
			->whereEquals('archived', 0);

		ToolAuthHelper::authorizeEditing($tool);

		$this->view
			->set('tool', $tool)
			->set('toolsTypeIds', $typeIds)
			->set('types', $types);

		$this->view->display();
	}

	/*
	 * Renders the frameworks info page of the tool update process
	 *
	 * @param   Hubzero\Relational  $tool     Tool instance
	 * @return  void
	 */
	public function editFrameworksTask($tool = null)
	{
		$id = Request::getInt('id');
		$tool = $tool ? $tool : Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Renders the materials page of the tool update process
	 *
	 * @param   Hubzero\Relational  $tool     Tool instance
	 * @return void
	 */
	public function editMaterialsTask($tool = null)
	{
		$id = Request::getInt('id');
		$tool = $tool ? $tool : Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Renders the links page of the tool update process
	 *
	 * @return void
	 */
	public function editLinksTask()
	{
		$id = Request::getInt('id');
		$tool = Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		$blankLink = Link::blank();

		$this->view
			->set('blankLink', $blankLink)
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Renders the downloads page of the tool update process
	 *
	 * @return void
	 */
	public function editDownloadsTask()
	{
		$id = Request::getInt('id');
		$tool = Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Renders the related tools page of the tool update process
	 *
	 * @return void
	 */
	public function editRelatedTask()
	{
		$id = Request::getInt('id');
		$tool = Tool::one($id);
		$otherTools = Tool::otherTools([$tool])
			->sort('name');

		ToolAuthHelper::authorizeEditing($tool);

		if (Request::has('selectedToolsIds'))
		{
			$selectedToolsIds = Request::getArray('selectedToolsIds');
		}
		else
		{
			$selectedToolsIds = $tool->relatedToolsIds();
		}

		$this->view
			->set('otherTools', $otherTools)
			->set('selectedToolsIds', $selectedToolsIds)
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Renders the tags page of the tool update process
	 *
	 * @return void
	 */
	public function editTagsTask()
	{
		$id = Request::getInt('id');
		$tags = Tag::all()
			->order('tag', 'asc');
		$tool = Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		if (Request::has('selectedTagsIds'))
		{
			$selectedTagsIds = Request::getArray('selectedTagsIds');
		}
		else
		{
			$selectedTagsIds = $tool->tagsIds();
		}

		$this->view
			->set('selectedTagsIds', $selectedTagsIds)
			->set('tags', $tags)
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Updates tool record
	 *
	 * @return void
	 */
	public function updateTask()
	{
		Request::checkToken();

		// fetch tool record
		$id = Request::getInt('id');
		$tool = Tool::one($id);

		ToolAuthHelper::authorizeEditing($tool);

		// get posted tool data
		$toolData = $this->_getSanitizedToolParams();

		// update duration
		if (isset($toolData['duration']))
		{
			$toolData['duration'] = $toolData['duration'] + $toolData['duration_hours'] * 60;
			unset($toolData['duration_hours']);
		}

		// get tools type associations
		$typeIds = Request::has('types') ? Request::getArray('types') : null;

		// unpublish tool if user not an admin
		if (!ToolAuthHelper::currentIsAuthorized('core.manage'))
		{
			$toolData['published'] = 0;
		}

		// set tool attributes
		$tool->set($toolData);

		// get step in update process user submitted data from
		$originStep = Request::getString('step');

		if ($tool->save())
		{
			$this->_successfulUpdate($tool, $originStep, $typeIds);
		}
		else
		{
			$this->_failedUpdate($tool, $originStep, $typeIds);
		}
	}

	/*
	 * Process successful update of a tool record
	 *
	 * @param   object    $tool        Tool object
	 * @param   string    $originStep  Name of the submitted step
	 * @param   array     $typeIds     IDs of types to associate
	 * @return  void
	 */
	protected function _successfulUpdate($tool, $originStep, $typeIds)
	{
		Notify::success('Tool updated.');
		$associationResult = ToolsTypesFactory::update($tool, $typeIds);

		if ($associationResult->succeeded())
		{
			$this->_sendToNextStep($originStep, $tool);
		}
		else
		{
			$this->_failedAssociationUpdate($tool, $typeIds, $originStep, $associationResult);
		}
	}

	/*
	 * Process failed update of a tool record
	 *
	 * @param   object    $tool        Tool object
	 * @param   string    $originStep  Name of the submitted step
	 * @param   array     $typeIds     IDs of types to associate
	 * @return  void
	 */
	protected function _failedUpdate($tool, $originStep, $typeIds)
	{
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_TOOL_UPDATE_ERROR') . '<br/>';

		foreach ($tool->getErrors() as $error)
		{
			$errorMessage .= "<br/>• $error";
		}

		Notify::error($errorMessage);

		$this->_sendToOriginStep($tool, $typeIds, $originStep);
	}

	/*
	 * Handles case in which a tool is updated but its associations are not
	 *
	 * @param   object    $tool               Tool object
	 * @param   array     $typeIds            IDs of types to associate
	 * @param   string    $originStep         Name of the submitted step
	 * @param   object    $associationResult  Result of attempting to update tools associations
	 * @return   void
	 */
	protected function _failedAssociationUpdate($tool, $typeIds, $originStep, $associationResult)
	{
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_TYPES_UPDATE_ERROR') . '<br/>';
		$errors = ToolsTypesFactory::parseUpdateErrors($associationResult);

		foreach ($errors as $error)
		{
			$errorMessage .= "<br/>• $error";
		}

		Notify::error($errorMessage);

		$this->_sendToOriginStep($tool, $typeIds, $originStep);
	}

	/*
	 * Sends user back to current step
	 *
	 * @param    object    $tool        Tool object
	 * @param    array     $typeIds     IDs of types to associate
	 * @param    string    $originStep  Name of the submitted step
	 * @return   void
	 */
	protected function _sendToOriginStep($tool, $typeIds, $originStep)
	{
		$originView = ToolUpdateHelper::stepToView($originStep);
		$originTask = ToolUpdateHelper::stepToTask($originStep);

		$this->setView(null, $originView);
		$this->$originTask($tool, $typeIds);
	}

	/*
	 * Redirects user to next step in tool editing process
	 *
	 * @param   string    $originStep  Name of the submitted step
	 * @param   integer   $tool        Newly created Tool record
	 * @return  void
	 */
	protected function _sendToNextStep($originStep, $tool)
	{
		$toolId = $tool->get('id');
		$nextStepUrl = ToolUpdateHelper::nextStepUrl($originStep, $toolId);

		App::redirect($nextStepUrl);
	}

	/*
	 * Process successful creation of a tool record
	 *
	 * @param   integer   $toolId      ID for newly created Tool record
	 * @param   array     $typeIds     IDs of types to associate
	 * @param   string    $originStep  Name of the submitted step
	 * @return  void
	 */
	protected function _successfulCreate($toolId, $typeIds, $originStep)
	{
		$associationResult = ToolsTypesFactory::associateManyToMany($toolId, $typeIds);

		if ($associationResult->succeeded())
		{
			$this->_sendToFrameworksStep($toolId, $originStep);
		}
		else
		{
			$errors = ToolsTypesFactory::parseCreateErrors($associationResult);
			$this->_sendToEditBasic($toolId, $errors, 'Tool created.');
		}
	}

	/*
	 * Redirects user to framework editing page
	 *
	 * @param   integer    $toolId      ID for newly created Tool record
	 * @param   string     $originStep  Name of the submitted step
	 * @return  void
	 */
	protected function _sendToFrameworksStep($toolId, $originStep)
	{
		$nextStepUrl = ToolUpdateHelper::nextStepUrl($originStep, $toolId);

		App::redirect(
			$nextStepUrl,
			'Tool created.',
			'passed'
		);
	}

	/*
	 * Redirects user to basic information editing page
	 *
	 * @param   integer   $toolId   ID for newly created Tool record
	 * @param   array     $errors   Errors to notify user of
	 * @param   string    $message     Message to display to user
	 * @return  void
	 */
	protected function _sendToEditBasic($toolId, $errors, $message)
	{
		$url = Route::url(
			"index.php?option=$this->_option&controller=$this->_controller&task=editbasic&id=$toolId",
			false
		);
		$errorMessage = implode($errors, '<br><br>');

		Notify::error($errorMessage);

		App::redirect(
			$url,
			$message,
			'passed'
		);
	}

	/*
	 * Process failed creation of a tool record
	 *
	 * @param   Hubzero\Relational  $tool     Tool instance
	 * @param   array               $typeIds  IDs of types to associate
	 * @return  void
	 */
	protected function _failedCreate($tool, $typeIds)
	{
		$errorMessage = Lang::txt('COM_TOOLBOX_TOOLS_TOOL_CREATE_ERROR') . '<br/>';

		foreach ($tool->getErrors() as $error)
		{
			$errorMessage .= "<br/>• $error";
		}

		Notify::error($errorMessage);

		$this->setView(null, 'new');
		$this->newTask($tool, $typeIds);
	}

	/*
	 * Compares posted tool data to whitelist
	 *
	 * @return   array
	 */
	protected static function _getSanitizedToolParams()
	{
		$postedData = Request::getArray('tool');
		$paramWhitelist = self::$paramWhitelist;

		foreach ($postedData as $attribute => $value)
		{
			if (!in_array($attribute, $paramWhitelist))
			{
				unset($postedData[$attribute]);
			}
		}

		return $postedData;
	}

	/*
	 * Lists the given tools downloads
	 *
	 * @return   void
	 */
	public function downloadsTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

    ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools links
	 *
	 * @return   void
	 */
	public function linksTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools materials
	 *
	 * @return   void
	 */
	public function materialsTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools notes
	 *
	 * @return   void
	 */
	public function notesTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools learning objectives
	 *
	 * @return   void
	 */
	public function objectivesTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools theoretical frameworks
	 *
	 * @return   void
	 */
	public function frameworksTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools related tools
	 *
	 * @return   void
	 */
	public function relatedTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);
		$relatedTools = $tool->relatedTools()->rows();

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('relatedTools', $relatedTools)
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists the given tools reviews
	 *
	 * @return   void
	 */
	public function reviewsTask()
	{
		ToolAuthHelper::redirectIfGuest();

		// retrieve given tool record
		$toolId = Request::getInt('id');
		$tool = Tool::oneOrFail($toolId);
		$reviews = $tool->reviews()
			->whereEquals('approved', 1)
			->paginated('limitstart', 'limit')
			->rows();

		ToolAuthHelper::authorizeViewing($tool);

		$this->view
			->set('reviews', $reviews)
			->set('tool', $tool);

		$this->view->display();
	}

	/*
	 * Lists tools that match given criteria
	 *
	 * @return   void
	 */
	public function listTask()
	{
		$query = Query::getCurrent();
		$formQuery = $query;
		$types = ToolType::all()
			->whereEquals('archived', 0);
		$userIsAdmin = ToolAuthHelper::currentIsAuthorized('core.manage');

		if (Request::has('query'))
		{
			$queryData = Request::getArray('query');
			$formQuery = new Query($queryData);
		}

		$tools = $query->findRecords(Tool::class)
			->whereEquals('archived', 0)
			->order('name', 'asc');

		// hide the tool if the user is not authorized to view it
		if (!$userIsAdmin)
		{
			$tools->whereEquals('published', 1)
				->orWhereEquals('user_id', User::get('id'));
		}

		$tools = $tools
			->paginated('limitstart', 'limit');

		$this->view
			->set('query', $formQuery)
			->set('tools', $tools)
			->set('types', $types);

		$this->view->display();
	}

}
