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
require_once "$toolboxPath/models/review.php";

use \Components\Toolbox\Admin\Helpers\FilterHelper;
use \Components\Toolbox\Admin\Helpers\Permissions;
use \Components\Toolbox\Models\Review;
use Hubzero\Component\AdminController;

class Reviews extends AdminController
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
	 * Returns reviews list view
	 *
	 * @return   void
	 */
	public function listTask()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$filters = FilterHelper::getFilters($component, $controller);
		$permissions = Permissions::getActions();

		$reviews = Review::all()
			->whereEquals('scope', 'tool');

		// sort reviews based on given criteria
		$reviews = $reviews->order($filters['sort'], $filters['sort_Dir'])
			->paginated('limitstart', 'limit');

		$this->view
			->set('filters', $filters)
			->set('permissions', $permissions)
			->set('title', static::$_toolbarTitle)
			->set('reviews', $reviews)
			->display();
	}

	/*
	 * Updates given reviews approved status to true
	 *
	 * @return   void
	 */
	public function approveTask()
	{
		$reviewId = Request::getInt('id');
		$review = Review::oneOrFail($reviewId);

		$review->set('approved', 1);

		if ($review->save())
		{
			$this->_successfulApprovedUpdate();
		}
		else
		{
			$this->_failedApprovedUpdate($review);
		}
	}

	/*
	 * Updates given reviews approved status to false
	 *
	 * @return   void
	 */
	public function unapproveTask()
	{
		$reviewId = Request::getInt('id');
		$review = Review::oneOrFail($reviewId);

		$review->set('approved', 0);

		if ($review->save())
		{
			$this->_successfulApprovedUpdate();
		}
		else
		{
			$this->_failedApprovedUpdate($review);
		}
	}

	/*
	 * Handles successful approval of review(s)
	 *
	 * @return   void
	 */
	protected function _successfulApprovedUpdate()
	{
		$this->_redirectToReviewsList();
	}

	/*
	 * Handles failed approval of review(s)
	 *
	 * @param    object  $review   Given review instance
	 * @return   void
	 */
	protected function _failedApprovedUpdate($review)
	{
		$errorMessage = Lang::txt('COM_TOOLBOX_REVIEWS_APPROVE_FAILURE') . '<br/>';

		foreach ($review->getErrors() as $error)
		{
			$errorMessage .= "<br/>• $error";
		}

		Notify::error($errorMessage);

		$this->_redirectToReviewsList();
	}

	/*
	 * Redirects user to reviews list
	 *
	 * @return   void
	 */
	protected function _redirectToReviewsList()
	{
		$component = $this->_option;
		$controller = $this->_controller;
		$reviewsListUrl = Route::url(
			"/administrator/index.php?option=$component&controller=$controller"
		);

		App::redirect($reviewsListUrl);
	}

}
