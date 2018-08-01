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

$component = $this->option;
$controller = $this->controller;
$i = $this->i;
$k = $this->k;
$tool = $this->tool;
$toolDuration = $this->escape($tool->get('duration'));
$toolExternalCost = $tool->get('external_cost');
$toolId = $tool->get('id');
$toolName = $this->escape($tool->get('name'));
$toolPublished = $tool->get('published');
$toolUrl = Route::url("/toolbox/tools/$toolId/downloads");

$publishUrl = Route::url("index.php?option=$component&controller=$controller&task=publish&id=$toolId");
$unpublishUrl = Route::url("index.php?option=$component&controller=$controller&task=unpublish&id=$toolId");
?>

<tr class="<?php echo "row$k"; ?>">

	<td>
		<input class="record-checkbox" type="checkbox" name="toolIds[]" id="cb<?php echo $i; ?>"
			value="<?php echo $toolId; ?>" />
	</td>

	<td class="priority-5">
		<a href="<?php echo $toolUrl; ?>">
			<?php echo $toolId; ?>
		</a>
	</td>

	<td>
		<?php echo $toolName; ?>
	</td>

	<td>
		<?php echo $toolDuration; ?>
	</td>

	<td>
		<?php if ($toolExternalCost): ?>
			<span class="state publish">
				<span><?php echo Lang::txt('UNPUBLISH'); ?></span>
			</span>
		<?php else: ?>
			<span class="state unpublish">
				<span><?php echo Lang::txt('PUBLISH'); ?></span>
			</span>
		<?php endif; ?>
	</td>

	<td>
		<?php if ($toolPublished): ?>
			<a href="<?php echo $unpublishUrl; ?>">
				<span class="state publish">
					<span><?php echo Lang::txt('UNPUBLISH'); ?></span>
				</span>
			</a>
		<?php else: ?>
			<a href="<?php echo $publishUrl; ?>">
				<span class="state unpublish">
					<span><?php echo Lang::txt('PUBLISH'); ?></span>
				</span>
			</a>
		<?php endif; ?>
	</td>

</tr>
