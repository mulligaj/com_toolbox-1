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
	<legend>
		<?php
			$this->view('_aacu_tooltip')
				->display();
		?>
	</legend>
	<div class="grid">

		<div class="col span5">
			<label>
					<input name="tool[self_awareness]" type="hidden" value="0">
					<input name="tool[self_awareness]" type="checkbox" value="1"
						<?php if ($tool->get('self_awareness')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_SELF_AWARENESS'); ?>
			</label>

			<label>
					<input name="tool[openness]" type="hidden" value="0">
					<input name="tool[openness]" type="checkbox" value="1"
						<?php if ($tool->get('openness')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_OPENNESS'); ?>
			</label>

			<label>
					<input name="tool[communication]" type="hidden" value="0">
					<input name="tool[communication]" type="checkbox" value="1"
						<?php if ($tool->get('communication')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_COMMUNICATION'); ?>
			</label>
		</div>

		<div class="col span5 offset1">
			<label>
					<input name="tool[empathy]" type="hidden" value="0">
					<input name="tool[empathy]" type="checkbox" value="1"
						<?php if ($tool->get('empathy')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_EMPATHY'); ?>
			</label>

			<label>
					<input name="tool[curiosity]" type="hidden" value="0">
					<input name="tool[curiosity]" type="checkbox" value="1"
						<?php if ($tool->get('curiosity')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_CURIOSITY'); ?>
			</label>

			<label>
					<input name="tool[worldview]" type="hidden" value="0">
					<input name="tool[worldview]" type="checkbox" value="1"
						<?php if ($tool->get('worldview')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_AACU_RUBRIC_WORLDVIEW'); ?>
			</label>
		</div>

	</div>
</fieldset>
