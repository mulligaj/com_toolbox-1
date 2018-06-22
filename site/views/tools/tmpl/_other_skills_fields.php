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
?>

<fieldset>
	<legend><?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS'); ?></legend>
	<div class="grid">

		<div class="col span5">
			<label>
					<input name="tool[friendship]" type="hidden" value="0">
					<input name="tool[friendship]" type="checkbox" value="1"
						<?php if ($tool->get('friendship')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS_FRIENDSHIP'); ?>
			</label>

			<label>
					<input name="tool[teamwork]" type="hidden" value="0">
					<input name="tool[teamwork]" type="checkbox" value="1"
						<?php if ($tool->get('teamwork')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS_TEAMWORK'); ?>
			</label>

			<label>
					<input name="tool[mentorship]" type="hidden" value="0">
					<input name="tool[mentorship]" type="checkbox" value="1"
						<?php if ($tool->get('mentorship')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS_MENTORSHIP'); ?>
			</label>
		</div>

		<div class="col span5 offset1">
			<label>
					<input name="tool[diversity_inclusion]" type="hidden" value="0">
					<input name="tool[diversity_inclusion]" type="checkbox" value="1"
						<?php if ($tool->get('diversity_inclusion')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS_DIVERSITY_INCLUSION'); ?>
			</label>

			<label>
					<input name="tool[leadership]" type="hidden" value="0">
					<input name="tool[leadership]" type="checkbox" value="1"
						<?php if ($tool->get('leadership')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_OTHER_SKILLS_LEADERSHIP'); ?>
			</label>
		</div>

	</div>
</fieldset>
