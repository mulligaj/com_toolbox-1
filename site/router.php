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

namespace Components\Toolbox\Site;

use Hubzero\Component\Router\Base;

class Router extends Base
{
	public function build(&$query)
	{
		$segments = [];
		$queryParams = ['controller', 'id', 'task'];

		foreach ($queryParams as $param)
		{
			if (!empty($query[$param]))
			{
				$segments[] = $query[$param];
				unset($query[$param]);
			}
		}

		return $segments;
	}

	public function parse(&$segments)
	{
		$vars = [];

		if (isset($segments[0]))
		{
			$vars['controller'] = $segments[0];
		}
		if (isset($segments[1]))
		{
			if (is_numeric($segments[1]))
			{
				$vars['id'] = $segments[1];
			}
			else
			{
				$vars['task'] = $segments[1];
			}
		}
		if (isset($segments[2]))
		{
			$vars['task'] = $segments[2];
		}

		return $vars;
	}
}
