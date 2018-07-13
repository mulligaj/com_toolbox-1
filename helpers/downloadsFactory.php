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

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/factory.php";
require_once "$toolboxPath/models/download.php";

use Components\Toolbox\Helpers\Factory;
use Components\Toolbox\Models\Download;

class DownloadsFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Toolbox\Models\Download';

	/*
	 * Updates preexisting records, creates records for new data
	 *
	 * @param    array    $downloadsData   Downloads' data
	 * @return   object
	 */
	public static function createOrUpdateMany($downloadsData)
	{
		$downloads = self::instantiateModels($downloadsData);

		$saveResult = self::save($downloads);

		return $saveResult;
	}

	/*
	 * Instantiates Download models using given data
	 *
	 * @param    array   $downloadsData   Downloads' data
	 * @return   array
	 */
	protected static function instantiateModels($downloadsData)
	{
		$records = array_map(function($attributes) {
			$toolId = $attributes['tool_id'];
			$name = $attributes['name'];

			$record = Download::oneByOrNew([
				'tool_id' => $toolId,
				'name' => $name
			]);

			$record->set($attributes);

			return $record;
		}, $downloadsData);

		return $records;
	}

	/*
	 * Translates instances' destory errors into error message for user
	 *
	 * @param   object   $result   Result of attempting to destroy records
	 * @return  array
	 */
	public static function parseDestroyErrors($result)
	{
		$failedDestroys = $result->getFailedDestroys();

		$errors = array_map(function($model) {
			$error = self::_generateDestroyErrorMessage($model);
			return $error;
		}, $failedDestroys);

		return $errors;
	}

	/*
	 * Generates an error message for a model that was not destroyed
	 *
	 * @param    object   $model   Given Download model
	 * @return   string
	 */
	protected static function _generateDestroyErrorMessage($model)
	{
		$fileName = $model->get('name');
		$error = Lang::txt('COM_TOOLBOX_DOWNLOAD_DESTROY_ERROR', $fileName);

		$error = self::_generateCombinedErrorMessage($error, $model);

		return $error;
	}

	/*
	 * Translates instances' save errors into error message for user
	 *
	 * @param   object   $result   Result of attempting to save records
	 * @return  array
	 */
	public static function parseSaveErrors($result)
	{
		$failedSaves = $result->getFailedSaves();

		$errors = array_map(function($model) {
			$error = self::_generateSaveErrorMessage($model);
			return $error;
		}, $failedSaves);

		return $errors;
	}

	/*
	 * Generates an error message for a model that was not saved
	 *
	 * @param    object   $model   Given Download model
	 * @return   string
	 */
	protected static function _generateSaveErrorMessage($model)
	{
		if ($model->isNew())
		{
			$error = self::_generateCreateErrorMessage($model);
		}
		else
		{
			$error = self::_generateUpdateErrorMessage($model);
		}

		return $error;
	}

	/*
	 * Generates an error message for a model that was not created
	 *
	 * @param    object   $model   Given Download model
	 * @return   string
	 */
	protected static function _generateCreateErrorMessage($model)
	{
		$fileName = $model->get('name');
		$error = Lang::txt('COM_TOOLBOX_DOWNLOAD_RECORD_CREATE_ERROR', $fileName);

		$error = self::_generateCombinedErrorMessage($error, $model);

		return $error;
	}

	/*
	 * Generates an error message for a model that was not created
	 *
	 * @param    object   $model   Given Download model
	 * @return   string
	 */
	protected static function _generateUpdateErrorMessage($model)
	{
		$fileName = $model->get('name');
		$error = Lang::txt('COM_TOOLBOX_DOWNLOAD_RECORD_CREATE_ERROR', $fileName);

		$error = self::_generateCombinedErrorMessage($error, $model);

		return $error;
	}

	/*
	 * Adds a Download model's specific errors to the user error message
	 *
	 * @param    string   $errorSummary   Base error message
	 * @param    object   $model          Given Download model
	 * @return   string
	 */
	protected static function _generateCombinedErrorMessage($errorSummary, $model)
	{
		$error = $errorSummary;
		$errors = $model->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

}
