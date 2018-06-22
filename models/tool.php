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

namespace Components\Toolbox\Models;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/models/download.php";

use Hubzero\Database\Relational;

class Tool extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_tools';

	/*
	 * Attributes to be populated on record creation
	 *
	 * @var array
	 */
	public $initiate = ['created'];

	/*
	 * Attribute validation
	 *
	 * @var  array
	 */
	protected $rules = [
		'name' => 'notempty',
		'minimum_participants' => 'positive',
		'suggested_participants' => 'positive',
		'maximum_participants' => 'positive',
		'duration' => 'positive',
		'cost' => 'positive',
		'source' => 'notempty',
		'subgroup_size' => 'notempty'
	];

	/*
	 * Instantiates a Tool model
	 */
	public static function blank()
	{
		$tool = parent::blank();

		$defaults = [
			'minimum_participants' => '0',
			'suggested_participants' => '0',
			'maximum_participants' => '0',
			'duration' => '0',
			'cost' => 0
		];

		$tool->set($defaults);

		return $tool;
	}

	/*
	 * Returns an array containing the associated types' IDs
	 *
	 * @return   array
	 */
	public function typeIds()
	{
		$types = $this->types()->rows()->toArray();

		$typeIds = array_map(function($type) {
			return $type['id'];
		}, $types);

		return $typeIds;
	}

	/*
	 * Returns associated Type records
	 *
	 * @return   object
	 */
	public function types()
	{
		$toolTypeModelName = 'Components\Toolbox\Models\ToolType';
		$associativeTable = '#__toolbox_tools_types';
		$primaryKey = 'tool_id';
		$foreignKey = 'type_id';

		$types = $this->manyToMany(
			$toolTypeModelName,
			$associativeTable,
			$primaryKey,
			$foreignKey
		);

		return $types;
	}

	/*
	 * Returns associated Download records
	 *
	 * @return   object
	 */
	public function downloads()
	{
		$downloadModelName = 'Components\Toolbox\Models\Download';

		$downloads = $this->oneToMany($downloadModelName, 'tool_id')->rows();

		return $downloads;
	}

}
