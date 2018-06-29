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
$tagsPath = Component::path('com_tags');

require_once "$toolboxPath/helpers/associationHelper.php";
require_once "$toolboxPath/helpers/multiBatchResult.php";
require_once "$toolboxPath/helpers/factory.php";
require_once "$tagsPath/models/object.php";

use Components\Toolbox\Helpers\AssociationHelper;
use Components\Toolbox\Helpers\MultiBatchResult;
use Components\Toolbox\Helpers\Factory;
use Components\Tags\Models\Object;

class ToolsTagsFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Tags\Models\Object';

	/*
	 * Unprefixed Tool table
	 *
	 * @var string
	 */
	protected static $toolTable = 'toolbox_tools';

	/*
	 * Updates given tools associated Tags
	 *
	 * @param    object   $tool      Tool record
	 * @param    array    $tagsIds   IDs of tags to associate with given tool
	 * @return   object
	 */
	public static function update($tool, $updateTagsIds)
	{
		$toolId = $tool->get('id');
		$difference = AssociationHelper::updateDelta($tool->tagsIds(), $updateTagsIds);

		$createResult = self::associateManyToMany($toolId, $difference['create']);
		$deleteResult = self::disassociateManyToMany($toolId, $difference['delete']);
		$combinedResult = new MultiBatchResult([$createResult, $deleteResult]);

		return $combinedResult;
	}

	/*
	 * Collates data to create tags Object records
	 *
	 * @param    int     $toolId    Tool to associate tags with
	 * @param    array   $tagsIds   IDs of tags to associate with given tool
	 * @return   array
	 */
	protected static function _collateJoinData($toolId, $tagsIds)
	{
		$currentUserId = User::get('id');
		$currentDate = Date::toSql();

		$tagsObjects = array_map(function($tagId) use ($toolId, $currentUserId, $currentDate) {
			return [
				'objectid' => $toolId,
				'tagid' => $tagId,
				'taggerid' => $currentUserId,
				'taggedon' => $currentDate,
				'tbl' => static::$toolTable
			];
		}, $tagsIds);

		return $tagsObjects;
	}

	/*
	 * Retrieves association records
	 *
	 * @param    integer   $toolId    Given tool's ID
	 * @param    array     $tagsIds   Associated tags' IDs
	 * @return   object
	 */
	protected static function _retrieveRecords($toolId, $tagsIds)
	{
		$joinRecords = Object::all()
			->whereEquals('objectid', $toolId)
			->whereIn('tagid', $tagsIds)
			->rows();

		return $joinRecords;
	}

	/*
	 * Generates an error message for a Tag association that was not saved
	 *
	 * @param    object   $tagsObject   Given tags object model
	 * @return   string
	 */
	protected static function _generateCreateErrorMessage($tagsObject)
	{
		$tagName = $tagsObject->tag()->rows()->get('tag');
		$error = Lang::txt('COM_TOOLBOX_TAGS_CREATE_ERROR', $tagName);
		$errors = $tagsObject->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}

	/*
	 * Generates an error message for a Tag association that was not destroyed
	 *
	 * @param    object   $tagsObject   Given tags object model
	 * @return   string
	 */
	protected static function _generateDestroyErrorMessage($tagsObject)
	{
		$tagName = $tagsObject->tag()->rows()->get('tag');
		$error = Lang::txt('COM_TOOLBOX_TAGS_DESTROY_ERROR', $tagName);
		$errors = $tagsObject->getErrors();

		foreach ($errors as $modelError)
		{
			$error .= "$modelError, ";
		}

		$error = rtrim($error, ', ') . '.';

		return $error;
	}


}
