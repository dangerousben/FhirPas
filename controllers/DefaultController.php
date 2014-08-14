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

namespace OEModule\FhirPas\controllers;
use \OEModule\FhirPas\components\PatientSearch;

class DefaultController extends \BaseController
{
	public function accessRules()
	{
		return array(array('allow', 'users' => array('@')));
	}

	public function actionIndex()
	{
		$patients = array();
		$error = null;

		if (\Yii::app()->request->isPostRequest) {
			if (empty($_POST['hos_num']) && empty($_POST['nhs_num']) && empty($_POST['first_name']) && empty($_POST['last_name'])) {
				$error = 'Please enter one or more search terms.';
			} else if (@$_POST['first_name'] xor @$_POST['last_name']) {
				$error = 'Please enter both a first and last name.';
			}

			if (!$error) {
				$patients = PatientSearch::search($_POST);
				if (!$patients) $error = 'No patients found.';
			}
		}

		$this->render('index', array('input' => $_POST, 'patients' => $patients, 'error' => $error));
	}

	public function actionImport($hos_num)
	{
		$patient = \Patient::model()->findByAttributes(array('hos_num' => $hos_num));

		if ($patient) {
			$patient_id = $patient->id;
		} else {
			$patients = PatientSearch::search(array('hos_num' => $hos_num), false);

			if (!$patients) throw new Exception("No patient found for hos_num '{$hos_num}'");

			if (count($patients) > 1) \Yii::app()->user->setFlash('fhirpas_duplicate_patient', "Multiple patients found in PAS with hospital number '{$hos_num}'");

			$patient_id = \Yii::app()->service->Patient->create($patients[0]);
		}

		$this->redirect($this->createUrl('/patient/view', array('id' => $patient_id)));
	}
}
