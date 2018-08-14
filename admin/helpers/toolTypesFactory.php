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

namespace Components\Toolbox\Admin\Helpers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/factory.php";
require_once "$toolboxPath/models/toolType.php";

use Components\Toolbox\Helpers\Factory;
use Components\Toolbox\Models\ToolType;
use Hubzero\Database\Query;

class ToolTypesFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Toolbox\Models\ToolType';

	/*
	 * Archives type(s) with given IDs
	 *
	 * @param    array   $typesIds   IDs of type records
	 * @return
	 */
	public static function archive($typesIds)
	{
		$types = self::getRecordsById($typesIds);
		$typesArray = [];

		foreach ($types as $type)
		{
			$type->set(['archived' => 1]);
			$typesArray[] = $type;
		}

		$updateResult = self::save($typesArray);

		return $updateResult;
	}

	/*
	 * Unarchives type(s) with given IDs
	 *
	 * @param    array   $typesIds   IDs of type records
	 * @return   bool
	 */
	public static function unarchive($typesIds)
	{
		$typesTable = (new ToolType())->getTableName();

		$updateQuery = (new Query())
			->update($typesTable)
			->set(['archived' => 0])
			->whereIn('id', $typesIds);

		$typesUnarchived = $updateQuery->execute();

		return $typesUnarchived;
	}

}
