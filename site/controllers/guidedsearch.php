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

namespace Components\Toolbox\Site\Controllers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/models/toolType.php";
require_once "$toolboxPath/helpers/query.php";

use Components\Toolbox\Models\ToolType;
use Components\Toolbox\Helpers\Query;
use Hubzero\Component\SiteController;

class Guidedsearch extends SiteController
{

	/*
	 * Returns the tool type page of the guided search process
	 *
	 * @return   void
	 */
	public function typeTask()
	{
		$query = Query::getCurrent();
		$types = ToolType::all();

		if (Request::has('selectedTypesIds'))
		{
			$selectedTypesIds = Request::getArray('selectedTypesIds');
		}
		else
		{
			$selectedTypesIds = $query->typesIds();
		}

		$this->view
			->set('selectedTypesIds', $selectedTypesIds)
			->set('types', $types)
			->display();
	}

}
