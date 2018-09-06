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

require_once "$toolboxPath/models/toolType.php";
require_once "$toolboxPath/helpers/query.php";
require_once "$toolboxPath/helpers/toolAuthHelper.php";
require_once "$toolboxPath/helpers/urlHelper.php";

use Components\Toolbox\Models\ToolType;
use Components\Toolbox\Helpers\Query;
use Components\Toolbox\Helpers\ToolAuthHelper;
use Components\Toolbox\Helpers\UrlHelper;
use Hubzero\Component\SiteController;

class Advancedsearch extends SiteController
{

	/*
	 * Task mapping
	 *
	 * @var  array
	 */
	protected $_taskMap = [
		'__default' => 'type'
	];

	/*
	 * Executes given task
	 *
	 * @return  void
	 */
	public function execute()
	{
		ToolAuthHelper::redirectIfGuest();

		parent::execute();
	}

	/*
	 * Returns the tool type page of the advanced search process
	 *
	 * @return   void
	 */
	public function typeTask()
	{
		if (Request::has('query'))
		{
			$queryData = Request::getArray('query');
			$query = new Query($queryData);
		}
		else
		{
			$query = Query::getCurrent();
		}
		$types = ToolType::all()
			->whereEquals('archived', 0);

		$this->view
			->set('query', $query)
			->set('types', $types)
			->display();
	}

	/*
	 * Returns the frameworks page of the advanced search process
	 *
	 * @return   void
	 */
	public function frameworksTask()
	{
		if (Request::has('query'))
		{
			$queryData = Request::getArray('query');
			$query = new Query($queryData);
		}
		else
		{
			$query = Query::getCurrent();
		}

		$this->view
			->set('query', $query)
			->display();
	}

	/*
	 * Returns the context page of the advanced search process
	 *
	 * @return   void
	 */
	public function contextTask()
	{
		if (Request::has('query'))
		{
			$queryData = Request::getArray('query');
			$query = new Query($queryData);
		}
		else
		{
			$query = Query::getCurrent();
		}

		$this->view
			->set('query', $query)
			->display();
	}

	/*
	 * Updates current users tool search query
	 *
	 * @return   void
	 */
	public function updateTypeTask()
	{
		Request::checkToken();

		// get posted query data
		$queryData = Request::getArray('query');

		// get current query
		$query = Query::getCurrent();

		// update query
		$query->setType($queryData);
		$query->setKinesthetic($queryData);

		if ($query->save())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($query);
		}
	}

	/*
	 * Updates current users tool search query frameworks attributes
	 *
	 * @return   void
	 */
	public function updateFrameworksTask()
	{
		Request::checkToken();

		// get posted query data
		$queryData = Request::getArray('query');

		// get current query
		$query = Query::getCurrent();

		// apply frameworks validation & defaults
		$query->setAacu($queryData);
		$query->setIdc($queryData);
		$query->setBergs($queryData);
		$query->setOtherSkills($queryData);

		if ($query->save())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($query);
		}
	}

	/*
	 * Updates current users tool search query context attributes
	 *
	 * @return   void
	 */
	public function updateContextTask()
	{
		Request::checkToken();

		// get posted query data
		$queryData = Request::getArray('query');

		// get current query
		$query = Query::getCurrent();

		// apply context validation
		$query->setSubgroupSize($queryData);
		$query->setExternalCost($queryData);
		$query->setDuration($queryData);

		if ($query->save())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($query);
		}
	}

	/*
	 * Updates all attributes of current users tool search query
	 *
	 * @return   void
	 */
	public function updateAllTask()
	{
		Request::checkToken();

		// get posted query data
		$queryData = Request::getArray('query');

		// get current query
		$query = Query::getCurrent();

		// update query
		$query->setType($queryData);
		$query->setKinesthetic($queryData);
		$query->setAacu($queryData);
		$query->setIdc($queryData);
		$query->setBergs($queryData);
		$query->setOtherSkills($queryData);
		$query->setSubgroupSize($queryData);
		$query->setExternalCost($queryData);
		$query->setDuration($queryData);

		if ($query->save())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($query);
		}
	}

  /*
   * Clears all current filters
   *
   * @return   void
   */
  public function clearAllTask()
  {
		Request::checkToken();

    $newQuery = new Query();

		if ($newQuery->save())
		{
			$this->_successfulUpdate();
		}
		else
		{
			$this->_failedUpdate($query);
		}
  }

	/*
	 * Process successful update of tool search query
	 *
	 * @return  void
	 */
	protected function _successfulUpdate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_GUIDED_QUERY_UPDATE_SUCCESS'),
			'passed'
		);
	}

	/*
	 * Process failed update of tool search query
	 *
	 * @param   object   $query   Current tool search query
	 * @return  void
	 */
	protected function _failedUpdate($query)
	{
		$queryParams = UrlHelper::buildQueryString(['query' => $query->toArray()]);
		$originUrl = Request::getString('origin') . "?$queryParams";
		$errorMessage = $this->_generateErrorMessage($query);

		Notify::error($errorMessage);

		App::redirect($originUrl);
	}

	/*
	 * Generates an error notification to display to the user
	 *
	 * @pram     object   $query   Query instance
	 * @return   string
	 */
	protected function _generateErrorMessage($query)
	{
		$errors = $query->getErrors();

		$errorMessage = Lang::txt('COM_TOOLBOX_GUIDED_QUERY_UPDATE_ERROR') . '</br></br>';

		foreach ($errors as $error)
		{
			$errorMessage .= "• $error</br></br>";
		}

		return $errorMessage;
	}

}
