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

require_once "$toolboxPath/models/tool.php";

use Hubzero\Database\Relational;

class Link extends Relational
{

	/*
	 * Records table
	 *
	 * @var string
	 */
	protected $table = '#__toolbox_links';

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
		'text' => 'notempty',
		'url' => 'notempty'
	];

	/*
	 * Performs any instance-specific setup
	 *
	 * @return   void
	 */
	public function setup()
	{
		$this->addRule('url', function($attributes) {
			$url = $attributes['url'];
			$filteredUrl = filter_var($url, FILTER_VALIDATE_URL, FILTER_FLAG_SCHEME_REQUIRED);

			if (!preg_match('/^https?:\/\//', $url))
			{
				return Lang::txt('COM_TOOLBOX_LINK_URL_VALIDATION_SCHEME_FAIL');
			}
			elseif (!$filteredUrl || ($url != $filteredUrl))
			{
				return Lang::txt('COM_TOOLBOX_LINK_URL_VALIDATION_FAIL');
			}

			return  false;
			});
	}

	/*
	 * Returns associated tool model
	 *
	 * @return   object
	 */
	public function tool()
	{
		$tool = $this->belongsToOne(
			'Components\Toolbox\Models\Tool', 'tool_id', 'id'
		)->rows();

		return $this->tool;
	}

}
