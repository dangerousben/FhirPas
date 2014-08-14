<?php
/**
 * (C) OpenEyes Foundation, 2014
 * This file is part of OpenEyes.
 * OpenEyes is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.
 * OpenEyes is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License along with OpenEyes in a file titled COPYING. If not, see <http://www.gnu.org/licenses/>.
 *
 * @package OpenEyes
 * @link http://www.openeyes.org.uk
 * @author OpenEyes <info@openeyes.org.uk>
 * @copyright Copyright (C) 2014, OpenEyes Foundation
 * @license http://www.gnu.org/licenses/gpl-3.0.html The GNU General Public License V3.0
 */
?>
<?php $this->renderPartial('//site/search_header'); ?>
<div class="row">
	<div class="large-8 large-centered column">
		<?php $this->renderPartial('//base/_messages'); ?>
		<?= CHtml::beginForm('', 'POST', array('class' => 'panel')) ?>
			<?php foreach (array('hos_num', 'nhs_num', 'first_name', 'last_name') as $field): ?>
				<div class="row field-row">
					<div class="large-2 column"><label for="<?= $field ?>"><?= CHtml::encode(\Patient::model()->getAttributeLabel($field)) ?>:</label></div>
					<div class="large-8 column end"><?= CHtml::textField($field, @$input[$field]) ?></div>
				</div>
			<?php endforeach ?>
			<div class="row field-row">
				<div class="large-offset-10 large-2 column">
					<button type="submit">Search</button>
				</div>
			</div>
		<?= CHtml::endForm() ?>

		<?php if ($error): ?>
			<div class="alert-box warning"><?= CHtml::encode($error) ?></div>
		<?php endif ?>

		<?php if ($patients): ?>
			<table id="patient-grid" class="grid">
				<thead>
					<tr>
						<?php foreach (array('hos_num', 'title', 'first_name', 'last_name', 'dob', 'gender', 'nhs_num') as $field): ?>
							<th><?= CHtml::encode(\Patient::model()->getAttributeLabel($field)) ?></th>
						<?php endforeach ?>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($patients as $patient): ?>
						<tr class="clickable" data-hos-num="<?= $patient->hos_num ?>">
							<?php foreach (array('hos_num', 'title', 'family_name', 'given_name', 'birth_date', 'gender', 'nhs_num')  as $field): ?>
								<td><?= CHtml::encode($patient->$field) ?></td>
							<?php endforeach ?>
						</tr>
					<?php endforeach ?>
				</tbody>
			</table>
		<?php endif ?>
	</div>
</div>
<script type="text/javascript">
	$('#patient-grid tr.clickable').click(function() {
		location.href = '<?php echo Yii::app()->createUrl('FhirPas/default/import') ?>?hos_num=' + $(this).data('hos-num');
	});
</script>
