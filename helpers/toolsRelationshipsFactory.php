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

require_once "$toolboxPath/models/toolsRelationship.php";
require_once "$toolboxPath/helpers/multiBatchResult.php";
require_once "$toolboxPath/helpers/factory.php";

use Components\Toolbox\Models\ToolsRelationship;
use Components\Toolbox\Helpers\MultiBatchResult;
use Components\Toolbox\Helpers\Factory;

class ToolsRelationshipsFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Toolbox\Models\ToolsRelationship';

	/*
	 * Updates given tools relation join records
	 *
	 * @param    object   $tool             Tool record
	 * @param    array    $relatedToolIds   IDs of tools to relate to given tool
	 * @return   object
	 */
	public static function update($tool, $updateRelatedToolIds)
	{
		$toolId = $tool->get('id');
		$difference = AssociationHelper::updateDelta($tool->relatedToolsIds(), $updateRelatedToolIds);

		$createResult = self::associateManyToMany($toolId, $difference['create']);
		$deleteResult = self::disassociateManyToMany($toolId, $difference['delete']);
		$combinedResult = new MultiBatchResult([$createResult, $deleteResult]);

		return $combinedResult;
	}

	/*
	 * Retrieves association records
	 *
	 * @param    integer   $toolId           Given tool's ID
	 * @param    array     $relatedToolIds   Related tools' IDs
	 * @return   object
	 */
	protected static function _retrieveRecords($toolId, $relatedToolIds)
	{
		$joinRecords = ToolsRelationship::all()
			->whereEquals('origin_id', $toolId)
			->whereIn('related_id', $relatedToolIds)
			->rows();

		return $joinRecords;
	}

	/*
	 * Collates data to create a ToolsRelationship record
	 *
	 * @param    int     $toolId           Tool to relate other tools to
	 * @param    array   $relatedToolIds   IDs of tools to relate to given tool
	 * @return   array
	 */
	protected static function _collateJoinData($toolId, $relatedToolIds)
	{
		$relationshipsData = array_map(function($relatedId) use ($toolId) {
			return [
				'origin_id' => $toolId,
				'related_id' => $relatedId,
			];
		}, $relatedToolIds);

		return $relationshipsData;
	}

	/*
	 * Generates an error message for a ToolsRelationship that was not saved
	 *
	 * @param    object   $relationship   Given relationship model
	 * @return   string
	 */
	protected static function _generateCreateErrorMessage($relationship)
	{
		$relatedToolName = $relationship->relatedToolName();
		$error = Lang::txt('COM_TOOLBOX_RELATED_CREATE_ERROR', $relatedToolName);
		$errors = $relationship->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

	/*
	 * Generates an error message for a relationship that was not destroyed
	 *
	 * @param    object   $relationship   Given relationship model
	 * @return   string
	 */
	protected static function _generateDestroyErrorMessage($relationship)
	{
		$relatedToolName = $relationship->relatedToolName();
		$error = Lang::txt('COM_TOOLBOX_RELATED_DESTROY_ERROR', $relatedToolName);
		$errors = $relationship->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

}
