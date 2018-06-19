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

$tool = $this->tool;
$duration = $tool->get('duration');
$hours = floor($duration / 60);
$minutes = $duration - ($hours * 60);
?>

<fieldset>
	<legend><?php echo Lang::txt('COM_TOOLBOX_NEW_DURATION_BUDGET_FIELDS'); ?></legend>
	<div class="grid">

		<div class="col span2">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_HOURS_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
					</span>
					<input name="tool[duration_hours]" type="number" min="0"
						value="<?php echo $hours; ?>">
			</label>
		</div>

		<div class="col span2 offset1">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_MINUTES_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
					<input name="tool[duration]" type="number" min="0"
						value="<?php echo $minutes; ?>">
			</label>
		</div>

		<div class="col span2 offset1">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_COST_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
					<input name="tool[cost]" type="number" min="0"
						value="<?php echo $tool->get('cost'); ?>">
			</label>
		</div>

	</div>
</fieldset>
