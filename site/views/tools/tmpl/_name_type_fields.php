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
$isKinesthetic = $tool->get('kinesthetic');
$toolsTypeIds = $this->toolsTypeIds;
?>

<fieldset>
	<legend><?php echo Lang::txt('COM_TOOLBOX_NEW_NAME_TYPE_FIELDS'); ?></legend>
	<div class="grid">

		<div class="col span5">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_NAME_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?></span>
					<input name="tool[name]" type="text" value="<?php echo $tool->get('name'); ?>">
			</label>
		</div>

		<div class="col span2 offset1">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_TYPE_FIELD'); ?>
					<select name="types[]" multiple>
						<?php	foreach ($this->types as $type): ?>
								<option value="<?php echo $type->get('id'); ?>"
									<?php if (in_array($type->get('id'), $toolsTypeIds)) echo 'selected'; ?>>
									<?php echo $type->get('description'); ?>
								</option>
						<?php endforeach; ?>
					</select>
			</label>
		</div>

		<div class="col span2 offset1">
			<label>
					<?php echo Lang::txt('COM_TOOLBOX_NEW_KINESTHETIC_FIELD'); ?> <span class="required">
						<?php echo Lang::txt('COM_TOOLBOX_COMMON_REQUIRED'); ?>
			</label>
			<label>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_YES'); ?>
					<input type="radio" id="kinesthetic-yes"
						name="tool[kinesthetic]" value="1" <?php if ($isKinesthetic) echo 'checked'; ?>>
			</label>
			<label>
				<?php echo Lang::txt('COM_TOOLBOX_COMMON_NO'); ?>
					<input type="radio" id="kinesthetic-no"
						name="tool[kinesthetic]" value="0" <?php if (!$isKinesthetic) echo 'checked'; ?>>
			</label>
		</div>

	</div>
</fieldset>
