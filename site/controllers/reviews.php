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

require_once "$toolboxPath/helpers/authHelper.php";
require_once "$toolboxPath/models/review.php";

use Components\Toolbox\Helpers\AuthHelper;
use Components\Toolbox\Models\Review;
use Hubzero\Component\SiteController;

class Reviews extends SiteController
{

	/*
	 * Saves a review record
	 *
	 * @return   void
	 */
	public function saveTask()
	{
		AuthHelper::redirectIfGuest();
		Request::checkToken();

		// get posted review data
		$reviewData = Request::getArray('review');

		// add current user's ID to data
		$reviewData['user_id'] = User::get('id');

		// instantiate review record
		$review = Review::blank();
		$review->set($reviewData);

		// attempt to create review record
		if ($review->save())
		{
			$this->_successfulCreate();
		}
		else
		{
			$errors = $review->getErrors();

			$this->_failedCreate($errors);
		}
	}

	/*
	 * Process successful creation of a review record
	 *
	 * @return   void
	 */
	public function _successfulCreate()
	{
		$forwardingUrl = Request::getString('forward');

		App::redirect(
			$forwardingUrl,
			Lang::txt('COM_TOOLBOX_REVIEW_SAVE_SUCCESSFUL'),
			'passed'
		);
	}

	/*
	 * Process failed creation of a review record
	 *
	 * @param    array   $errors   Review models errors
	 * @return   void
	 */
	public function _failedCreate($errors)
	{
		$originUrl = Request::getString('origin');
		$errorMessage = Lang::txt('COM_TOOLBOX_REVIEW_SAVE_FAILED') . ' ';

		foreach ($errors as $error)
		{
			$errorMessage .= "$error, ";
		}
		$error = rtrim($error, ', ') . '.';

		Notify::error($errorMessage);

		App::redirect($originUrl);
	}

}
