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

namespace Components\Toolbox\Helpers;

class DestroyBatch
{

	/*
	 * Models that failed to be saved
	 *
	 * @var   array
	 */
	protected $_failedDestroys = [];

	/*
	 * Successfully saved models
	 *
	 * @var   array
	 */
	protected $_successfulDestroys = [];

	/*
	 * Adds model to failed destroys
	 *
	 * @param    object   $model   Model that failed to be destroyed
	 * @return   void
	 */
	public function addFailedDestroy($model)
	{
		array_push($this->_failedDestroys, $model);
	}

	/*
	 * Adds model to successful destroys
	 *
	 * @param    object   $model   Model that was destroyed
	 * @return   void
	 */
	public function addSuccessfulDestroy($model)
	{
		array_push($this->_successfulDestroys, $model);
	}

	/*
	 * Indicates whether destruction succeeded or not
	 *
	 * @return   bool
	 */
	public function succeeded()
	{
		$succeeded = empty($this->_failedDestroys);

		return $succeeded;
	}

	/*
	 * Getter for _failedDestroys
	 *
	 * @return   array
	 */
	public function getFailedDestroys()
	{
		return $this->_failedDestroys;
	}

	/*
	 * Indicates that batch is not a create batch
	 *
	 * @return bool
	 */
	public function isCreateBatch()
	{
		return false;
	}

	/*
	 * Indicates that batch is a destroy batch
	 *
	 * @return bool
	 */
	public function isDestroyBatch()
	{
		return true;
	}

}
