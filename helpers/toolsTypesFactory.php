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
require_once "$toolboxPath/helpers/multiBatchResult.php";
require_once "$toolboxPath/models/toolsType.php";

use Components\Toolbox\Helpers\Factory;
use Components\Toolbox\Helpers\MultiBatchResult;
use Components\Toolbox\Models\ToolsType;

class ToolsTypesFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Toolbox\Models\ToolsType';

	/*
	 * Creates association records to link tool with given types
	 *
	 * @param   integer   $toolId    Given tool's ID
	 * @param   array     $typeIds   Given types' IDs
	 * @return  array
	 */
	public static function associate($toolId, $typeIds)
	{
		$modelsData = self::_collateModelData($toolId, $typeIds);

		$result = self::createMany($modelsData);

		return $result;
	}

	/*
	 * Deletes association records
	 *
	 * @param   integer   $toolId    Given tool's ID
	 * @param   array     $typeIds   Given types' IDs
	 * @return  array
	 */
	public static function disassociate($toolId, $typeIds)
	{
		$toolsTypes = self::_retrieveRecords($toolId, $typeIds);

		$result = self::destroyMany($toolsTypes);

		return $result;
	}

	/*
	 * Updates a tool's types associations
	 *
	 * @param   object    $tool               Tool record
	 * @param   array     $updatedTypeIds     Set of updated types' IDs
	 * @return  array
	 */
	public static function updateAssociations($tool, $updatedTypeIds)
	{
		$toolId = $tool->get('id');
		$difference = self::_getDifference($tool->typeIds(), $updatedTypeIds);

		$createResult = self::associate($toolId, $difference['create']);
		$deleteResult = self::disassociate($toolId, $difference['delete']);
		$combinedResult = new MultiBatchResult([$createResult, $deleteResult]);

		return $combinedResult;
	}

	/*
	 * Determines the sets of IDs to create and delete
	 *
	 * @param    array   $currentTypeIds   Tools currently associated types' IDs
	 * @param    array   $updatedTypeIds   Set of updated types' IDs
	 * @return   array
	 */
	protected static function _getDifference($currentTypeIds, $updatedTypeIds)
	{
		$delete = [];

		foreach ($currentTypeIds as $currentTypeId)
		{
			// If ID of current assoc. is not in updated set, delete it
			if (!in_array($currentTypeId, $updatedTypeIds))
			{
				$delete[] = $currentTypeId;
			}
			// If ID of current assoc. is in updated set, remove from updated
			elseif (in_array($currentTypeId, $updatedTypeIds))
			{
				$idIndex = array_search($currentTypeId, $updatedTypeIds);
				unset($updatedTypeIds[$idIndex]);
			}
		}

		return [
			'create' => $updatedTypeIds,
			'delete' => $delete
		];
	}

	/*
	 * Generates array to instantiate models with
	 *
	 * @param   integer   $toolId    Given tool's ID
	 * @param   array     $typeIds   Given types' ID
	 * @return  array
	 */
	protected static function _collateModelData($toolId, $typeIds)
	{
		$modelsData = array_map(function($typeId) use ($toolId) {
			$modelData = ['tool_id' => $toolId, 'type_id' => $typeId];
			return $modelData;
		}, $typeIds);

		return $modelsData;
	}

	/*
	 * Translates instances' errors into final error message
	 *
	 * @param   object   $result   Result of attempting to update records
	 * @return  array
	 */
	public static function parseUpdateErrors($result)
	{
		$saveErrors = self::parseCreateErrors($result);
		$destroyErrors = self::parseDestroyErrors($result);

		$errors = array_merge($saveErrors, $destroyErrors);

		return $errors;
	}

	/*
	 * Translates instances' errors into final error message
	 *
	 * @param   object   $result   Result of attempting to persist records
	 * @return  array
	 */
	public static function parseCreateErrors($result)
	{
		$failedSaves = $result->getFailedSaves();

		$errors = array_map(function($model) {
			$error = self::_generateCreateErrorMessage($model);
			return $error;
		}, $failedSaves);

		return $errors;
	}

	/*
	 * Generates an error message for a model that was not saved
	 *
	 * @param    object   $model   Given ToolsType model
	 * @return   string
	 */
	protected static function _generateCreateErrorMessage($model)
	{
		$typeDescription = $model->getTypeDescription();
		$error = "Unable to associate tool with $typeDescription type because: ";
		$errors = $model->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

	/*
	 * Translates instances' errors into final error message
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
	 * @param    object   $model   Given ToolsType model
	 * @return   string
	 */
	protected static function _generateDestroyErrorMessage($model)
	{
		$typeDescription = $model->getTypeDescription();
		$error = "Unable to disassociate tool from $typeDescription type because: ";
		$errors = $model->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

	/*
	 * Retrieves association records
	 *
	 * @param    integer   $toolId    Given tool's ID
	 * @param    array     $typeIds   Given types' ID
	 * @return   string
	 */
	protected static function _retrieveRecords($toolId, $typeIds)
	{
		$joinRecords = ToolsType::all()
			->whereEquals('tool_id', $toolId)
			->whereIn('type_id', $typeIds)
			->rows();

		return $joinRecords;
	}

}
