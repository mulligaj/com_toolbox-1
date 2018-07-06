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


<h4>
	<?php echo Lang::txt('COM_TOOLBOX_IDC'); ?>
</h4>

<div class="grid">
	<div class="col span5">
		<div class="checkbox-wrapper">
			<input type="checkbox" <?php if ($tool->get('denial')) echo 'checked'; ?> disabled>
			<?php echo Lang::txt('COM_TOOLBOX_IDC_DENIAL'); ?>
		</div>
		<div class="checkbox-wrapper">
			<input type="checkbox" <?php if ($tool->get('polarization')) echo 'checked'; ?> disabled>
			<?php echo Lang::txt('COM_TOOLBOX_IDC_POLARIZATION'); ?>
		</div>
		<div class="checkbox-wrapper">
			<input type="checkbox" <?php if ($tool->get('minimization')) echo 'checked'; ?> disabled>
			<?php echo Lang::txt('COM_TOOLBOX_IDC_MINIMIZATION'); ?>
		</div>
	</div>

	<div class="col span5">
		<div class="checkbox-wrapper">
			<input type="checkbox" <?php if ($tool->get('acceptance')) echo 'checked'; ?> disabled>
			<?php echo Lang::txt('COM_TOOLBOX_IDC_ACCEPTANCE'); ?>
		</div>
	</div>
</div>

