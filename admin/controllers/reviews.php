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
require_once "$toolboxPath/models/review.php";

use \Components\Toolbox\Admin\Helpers\FilterHelper;
use \Components\Toolbox\Admin\Helpers\RedirectHelper;
use \Components\Toolbox\Admin\Helpers\Permissions;
use \Components\Toolbox\Models\Review;
use Hubzero\Component\AdminController;
use Hubzero\Database\Query;

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

	/*
	 * Destroys the given review(s)
	 *
	 * @return   void
	 */
	public function destroyTask()
	{
		Request::checkToken();

		$reviewIds = Request::getArray('reviewIds');

		$reviewTable = (new Review())->getTableName();

		$destroyQuery = (new Query())
			->delete($reviewTable)
			->whereIn('id', $reviewIds);

		$reviewsDestroyed = $destroyQuery->execute();

		if ($reviewsDestroyed)
		{
			$this->_successfulDestroy();
		}
		else
		{
			$this->_failedDestroy();
		}
	}

	/*
	 * Handles successful destruction of given review(s)
	 *
	 * @return   void
	 */
	protected function _successfulDestroy()
	{
		$forwardingUrl = Request::getString('forward');
		$langKey = 'COM_TOOLBOX_REVIEWS_DESTROY_SUCCESS';
		$notificationType = 'passed';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Handles failed destruction of given review(s)
	 *
	 * @return   void
	 */
	protected function _failedDestroy()
	{
		$originUrl = Request::getString('origin');
		$langKey = 'COM_TOOLBOX_REVIEWS_DESTROY_FAILURE';
		$notificationType = 'error';

		RedirectHelper::redirectAndNotify($originUrl, $langKey, $notificationType);
	}

	/*
	 * Renders the review details page
	 *
	 * @return   void
	 */
	public function showTask()
	{
		$permissions = Permissions::getActions();
		$reviewId = Request::getInt('id');

		$review = Review::oneOrFail($reviewId);

		$this->view
			->set('permissions', $permissions)
			->set('review', $review)
			->set('title', static::$_toolbarTitle)
			->display();
	}

	/*
	 * Updates given review
	 *
	 * @return   void
	 */
	public function saveTask()
	{
		$review = $this->_updateReview();

		if ($review->save())
		{
			$this->_successfulSave();
		}
		else
		{
			$reviewId = $review->get('id');
			$this->_failedSave($reviewId);
		}
	}

	/*
	 * Handles the successful save submission of a review record
	 *
	 * @return   void
	 */
	protected function _successfulSave()
	{
		$forwardingUrl = Route::url(
			'/administrator/index.php?option=com_toolbox&controller=reviews&task=list'
		);
		$langKey = 'COM_TOOLBOX_REVIEWS_SAVE_SUCCESS';
		$notificationType = 'passed';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Handles the failed save submission of a review record
	 *
	 * @param    int    $reviewId   ID of given review
	 * @return   void
	 */
	protected function _failedSave($reviewId)
	{
		$forwardingUrl = Route::url(
			"/administrator/index.php?option=com_toolbox&controller=reviews&task=show&id=$reviewId"
		);
		$langKey = 'COM_TOOLBOX_REVIEWS_SAVE_FAILURE';
		$notificationType = 'error';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Updates given review and returns user to review detail page
	 *
	 * @return   void
	 */
	public function applyTask()
	{
		$review = $this->_updateReview();
		$reviewId = $review->get('id');

		if ($review->save())
		{
			$this->_successfulApply($reviewId);
		}
		else
		{
			$this->_failedApply($reviewId);
		}
	}

	/*
	 * Handles the successful apply submission of a review record
	 *
	 * @param    int    $reviewId   ID of given review
	 * @return   void
	 */
	protected function _successfulApply($reviewId)
	{
		$forwardingUrl = Route::url(
			"/administrator/index.php?option=com_toolbox&controller=reviews&task=show&id=$reviewId"
		);
		$langKey = 'COM_TOOLBOX_REVIEWS_SAVE_SUCCESS';
		$notificationType = 'passed';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Handles the failed apply submission of a review record
	 *
	 * @param    int    $reviewId   ID of given review
	 * @return   void
	 */
	protected function _failedApply($reviewId)
	{
		$forwardingUrl = Route::url(
			"/administrator/index.php?option=com_toolbox&controller=reviews&task=show&id=$reviewId"
		);
		$langKey = 'COM_TOOLBOX_REVIEWS_SAVE_FAILURE';
		$notificationType = 'error';

		RedirectHelper::redirectAndNotify($forwardingUrl, $langKey, $notificationType);
	}

	/*
	 * Updates given review record
	 *
	 * @return   object
	 */
	protected function _updateReview()
	{
		$reviewId = Request::getInt('id');
		$review = Review::oneOrFail($reviewId);
		$reviewData = Request::getArray('review');

		$review->set($reviewData);

		return $review;
	}

}
