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

namespace OEModule\FhirPas\components;

class PatientSearch
{
	/**
	 * @param array $data
	 * @param boolean $summary
	 * @return Patient[]
	 */
	static public function search(array $data, $summary = true)
	{
		$params = array();

		if (!empty($data['hos_num'])) {
			$params[] = 'identifier=' . urlencode(\Yii::app()->params['fhir_system_uris']['hos_num'] . '|' . $data['hos_num']);
		}

		if (!empty($data['nhs_num'])) {
			$params[] = 'identifier=' . urlencode(\Yii::app()->params['fhir_system_uris']['nhs_num'] . '|' . $data['nhs_num']);
		}

		if (!empty($data['first_name'])) {
			$params[] = 'given:exact=' . urlencode($data['first_name']);
		}

		if (!empty($data['last_name'])) {
			$params[] = 'family:exact=' . urlencode($data['last_name']);
		}

		if ($summary) $params[] = '_summary=true';

		$url = \Yii::app()->fhirClient->servers['pas']['base_url'] . '/Patient?' . implode("&", $params);

		$response = \Yii::app()->fhirClient->request($url);

		if (substr($response->getCode(), 0, 1) != 2) {
			throw new \Exception("FHIR request to '{$url}' failed:\n" . \CVarDumper::dumpAsString($response));
		}

		$patients = array();
		$entries = isset($response->getValue()->entry) ? $response->getValue()->entry : array();

		foreach ($entries as $entry) {
			$patients[] = \services\Patient::fromFhir($entry->content);
		}

		return $patients;
	}
}
