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

class MultiBatchResult
{
	/*
	 * Set of create and/ or delete batches
	 *
	 * @var   array
	 */
	protected $batches = null;

	/*
	 * Constructor function
	 *
	 * @param    array   $batches   Set of create and/ or delete batches
	 * @return   void
	 */
	public function __construct($batches)
	{
		$this->batches = $batches;
	}

	/*
	 * Indicates whether all batches succeeded
	 *
	 * @return   bool
	 */
	public function succeeded()
	{
		foreach ($this->batches as $batch)
		{
			if (!$batch->succeeded())
			{
				return false;
			}
		}

		return true;
	}

	/*
	 * Getter for failed saves
	 *
	 * @return   array
	 */
	public function getFailedSaves()
	{
		$failedSaves = [];

		foreach ($this->batches as $batch)
		{
			if ($batch->isCreateBatch() && !$batch->succeeded())
			{
				$failedSaves = array_merge($failedSaves, $batch->getFailedSaves());
			}
		}

		return $failedSaves;
	}

	/*
	 * Getter for failed destroys
	 *
	 * @return   array
	 */
	public function getFailedDestroys()
	{
		$failedDestroys = [];

		foreach ($this->batches as $batch)
		{
			if ($batch->isDestroyBatch() && !$batch->succeeded())
			{
				$failedDestroys = array_merge(
					$failedDestroys,
					$batch->getFailedDestroys()
				);
			}
		}

		return $failedDestroys;
	}

}
