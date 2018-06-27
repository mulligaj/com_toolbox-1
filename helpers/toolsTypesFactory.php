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
require_once "$toolboxPath/helpers/associationHelper.php";
require_once "$toolboxPath/helpers/multiBatchResult.php";
require_once "$toolboxPath/helpers/nullBatchResult.php";
require_once "$toolboxPath/models/toolsType.php";

use Components\Toolbox\Helpers\Factory;
use Components\Toolbox\Helpers\AssociationHelper;
use Components\Toolbox\Helpers\MultiBatchResult;
use Components\Toolbox\Helpers\NullBatchResult;
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
	 * Updates a tool's types associations
	 *
	 * @param   object    $tool               Tool record
	 * @param   mixed     $updatedTypeIds     Set of updated types' IDs
	 * @return  array
	 */
	public static function update($tool, $updatedTypeIds)
	{
		if (!$updatedTypeIds)
		{
			return new NullBatchResult();
		}
		$toolId = $tool->get('id');
		$difference = AssociationHelper::updateDelta($tool->typeIds(), $updatedTypeIds);

		$createResult = self::associateManyToMany($toolId, $difference['create']);
		$deleteResult = self::disassociateManyToMany($toolId, $difference['delete']);
		$combinedResult = new MultiBatchResult([$createResult, $deleteResult]);

		return $combinedResult;
	}

	/*
	 * Generates array to instantiate models with
	 *
	 * @param   integer   $toolId    Given tool's ID
	 * @param   array     $typeIds   Given types' ID
	 * @return  array
	 */
	protected static function _collateJoinData($toolId, $typeIds)
	{
		$modelsData = array_map(function($typeId) use ($toolId) {
			$modelData = ['tool_id' => $toolId, 'type_id' => $typeId];
			return $modelData;
		}, $typeIds);

		return $modelsData;
	}

	/*
	 * Generates an error message for a Type that was not saved
	 *
	 * @param    object   $type   Given ToolsType type
	 * @return   string
	 */
	protected static function _generateCreateErrorMessage($type)
	{
		$typeDescription = $type->getTypeDescription();
		$error = Lang::txt('COM_TOOLBOX_TOOLS_TYPES_CREATE_ERROR', $typeDescription);
		$errors = $type->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
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
		$error = Lang::txt('COM_TOOLBOX_TOOLS_TYPES_DESTROY_ERROR', $typeDescription);
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
	 * @return   object
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
