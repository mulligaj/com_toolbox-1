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

// No direct access
defined('_HZEXEC_') or die();

use Hubzero\Utility\Number;

$i = $this->i;
$k = $this->k;

$component = $this->option;
$controller = $this->controller;
$download = $this->download;
$id = $download->get('id');
$toolId = $download->get('tool_id');
$name = $download->get('name');
$type = $download->get('type');
$size = Number::formatBytes($download->get('size'));
?>

<tr class="<?php echo "row$k"; ?>">

	<td>
		<input class="record-checkbox" type="checkbox" name="downloadsIds[]" id="cb<?php echo $i; ?>"
			value="<?php echo $id; ?>" />
	</td>

	<td class="priority-5">
		<?php echo $id; ?>
	</td>

	<td>
		<a href="<?php echo Route::url("/toolbox/tools/$toolId"); ?>">
			<?php echo $toolId; ?>
		</a>
	</td>

	<td>
		<?php echo $name; ?>
	</td>

	<td>
		<?php echo $type; ?>
	</td>

	<td>
		<?php echo $size; ?>
	</td>

</tr>
