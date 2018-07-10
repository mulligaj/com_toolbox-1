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
$hasExternalCost = $tool->get('external_cost');
$hours = floor($duration / 60);
$minutes = $duration - ($hours * 60);
$subgroupSize = $tool->get('subgroup_size');
$subgroupSizes = $tool->getSubgroupSizes();
?>

<fieldset>
	<legend><?php echo Lang::txt('COM_TOOLBOX_NEW_DURATION_BUDGET_PARTICIPANT_FIELDS'); ?></legend>
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
					<?php echo Lang::txt('COM_TOOLBOX_EXTERNAL_COST_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
			</label>
			<label>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_YES'); ?>
					<input type="radio" id="external-cost-yes"
						name="tool[external_cost]" value="1" <?php if ($hasExternalCost) echo 'checked'; ?>>
			</label>
			<label>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_NO'); ?>
					<input type="radio" id="external-cost-no"
						name="tool[external_cost]" value="0" <?php if (!$hasExternalCost) echo 'checked'; ?>>
			</label>
		</div>

		<div class="col span2 offset1">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_SUBGROUP_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
					<select name="tool[subgroup_size]">
						<?php foreach ($subgroupSizes as $value => $display): ?>
							<option value="<?php echo $value; ?>"
								<?php if ($value === $subgroupSize) echo 'selected'; ?>>
								<?php echo $display; ?>
							</option>
						<?php endforeach; ?>
					</select>
			</label>
		</div>

	</div>
</fieldset>
