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
require_once "$toolboxPath/models/link.php";

use Components\Toolbox\Helpers\Factory;
use Components\Toolbox\Models\Link;

class LinksFactory extends Factory
{

	/*
	 * Model name
	 *
	 * @var string
	 */
	protected static $modelName = 'Components\Toolbox\Models\Link';

	/*
	 * Updates preexisting records, creates records for new data
	 *
	 * @param    array    $linksData   Links' data
	 * @return   object
	 */
	public static function createOrUpdateMany($linksData)
	{
		$links = self::instantiateModels($linksData);

		$saveResult = self::save($links);

		return $saveResult;
	}

	/*
	 * Instantiates Link models using given data
	 *
	 * @param    array   $linksData   Links' data
	 * @return   array
	 */
	protected static function instantiateModels($linksData)
	{
		$records = array_map(function($attributes) {
			$id = $attributes['id'];
			$record = Link::oneOrNew($id);
			$record->set($attributes);

			return $record;
		}, $linksData);

		return $records;
	}

}
