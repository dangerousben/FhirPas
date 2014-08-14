FhirPas
=======

A very basic implementation of FHIR search and pull for patient demographics.

Configuration
-------------

	'components' => array(
		'fhirClient' => array(
			'servers' => array(
				'pas' => array(  // PAS FHIR server
					'base_url' => 'https://example.com/fhir',
					'auth' => array(
						'type' => 'basic',
						'username' => 'user',
						'password' => 'pass,
					),
				),
			),
		),
	),
	'params' => array(
		'fhir_system_uris' => array(  // System URIs for FHIR identifiers
			'hos_num' => 'http:/example.com/hos_num',
			'nhs_num' => 'http:/example.com/nhs_num',
		),
	),
