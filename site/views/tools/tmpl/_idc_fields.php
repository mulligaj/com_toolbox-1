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
	<legend><?php echo Lang::txt('COM_TOOLBOX_IDC'); ?></legend>
	<div class="grid">

		<div class="col span5">
			<label>
					<input name="tool[denial]" type="hidden" value="0">
					<input name="tool[denial]" type="checkbox" value="1"
						<?php if ($tool->get('denial')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_IDC_DENIAL'); ?>
			</label>

			<label>
					<input name="tool[polarization]" type="hidden" value="0">
					<input name="tool[polarization]" type="checkbox" value="1"
						<?php if ($tool->get('polarization')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_IDC_POLARIZATION'); ?>
			</label>

			<label>
					<input name="tool[minimization]" type="hidden" value="0">
					<input name="tool[minimization]" type="checkbox" value="1"
						<?php if ($tool->get('minimization')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_IDC_MINIMIZATION'); ?>
			</label>
		</div>

		<div class="col span5 offset1">
			<label>
					<input name="tool[acceptance]" type="hidden" value="0">
					<input name="tool[acceptance]" type="checkbox" value="1"
						<?php if ($tool->get('acceptance')) echo 'checked'; ?>>
					<?php echo Lang::txt('COM_TOOLBOX_IDC_ACCEPTANCE'); ?>
			</label>
		</div>

	</div>
</fieldset>
