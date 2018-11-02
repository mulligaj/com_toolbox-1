<?php
/**
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
 * @author    Anthony Fuentes <zooley@purdue.edu>
 * @copyright Copyright 2005-2015 HUBzero Foundation, LLC.
 * @license   http://opensource.org/licenses/MIT MIT
 */

namespace Components\Toolbox\Api\Controllers;

$toolboxPath = Component::path('com_toolbox');

require_once "$toolboxPath/helpers/eventHelper.php";
require_once "$toolboxPath/helpers/toolAuthHelper.php";
require_once "$toolboxPath/models/link.php";
require_once "$toolboxPath/models/tool.php";

use Components\Toolbox\Helpers\EventHelper;
use Components\Toolbox\Helpers\ToolAuthHelper;
use Components\Toolbox\Models\Link;
use Components\Toolbox\Models\Tool;
use Hubzero\Component\ApiController;
use Request;

class Linksv1_0 extends ApiController
{

	/**
	 * Controller version
	 *
	 * @var  string
	 */
	protected static $version = '1.0';

	/**
	 * Destroy link based on provided ID
	 *
	 * @apiMethod DELETE
	 * @apiUri    /api/v1.0/toolbox/links/destroy
	 * @apiParameter {
	 * 		"name":          id,
	 * 		"description":   Link record ID,
	 * 		"type":          int,
	 * 		"required":      true
	 * }
	 * @return   object
	 */
	function destroyTask()
	{
		$linkId = Request::getInt('id');
		$link = Link::oneOrFail($linkId);
		$toolId = $link->toolId();
		$tool= Tool::oneOrFail($toolId);

		ToolAuthHelper::authorizeEditing($tool);

		$response = [
			'link' => $link,
			'version' => self::$version
		];

		if ($link->destroy())
		{
			// trigger on update event
			EventHelper::onToolUpdate($tool, 'deleted a link');

			$response['status'] = 'success';
		}
		else
		{
			$response['status'] = 'error';
			$response['errors'] = $link->getErrors();
		}

		$this->send($response);
	}

}
