<?php 

class Dashboard_model extends CI_Model {
	function __construct() {
		// nada
	}

	public function dummyApi($method) {
		$response = array();

		switch ($method) {
			case 'getServersSummary':
				$response = array(
					'servers' => array(
						array(
							'name' => 'Test Server 1',
							'stats' => array(
								'cpu' => '80%',
								'health' => '44'
							)
						),
						array(
							'name' => 'Test Server 2',
							'stats' => array(
								'cpu' => '30%',
								'health' => '85'
							)
						),
						array(
							'name' => 'Test Server 3',
							'stats' => array(
								'cpu' => '53%',
								'health' => '71'
							)
						),
						array(
							'name' => 'Test Server 4',
							'stats' => array(
								'cpu' => '72%',
								'health' => '53'
							)
						),
						array(
							'name' => 'Test Server 5',
							'stats' => array(
								'cpu' => '12%',
								'health' => '96'
							)
						)
					)
				);

				break;

			case 'getServersByHealth':
				$response = array(
					'servers' => array(
						array(
							'name' => 'Test Server 1',
							'workers' => 10,
							'stats' => array(
								'cpu' => '80%',
								'health' => '44',
								'health_bad' => 1
							)
						),
						array(
							'name' => 'Test Server 4',
							'workers' => 30,
							'stats' => array(
								'cpu' => '72%',
								'health' => '53',
								'health_bad' => 1
							)
						),
						array(
							'name' => 'Test Server 3',
							'workers' => 50,
							'stats' => array(
								'cpu' => '53%',
								'health' => '71',
								'health_okay' => 1
							)
						),
						array(
							'name' => 'Test Server 2',
							'workers' => 20,
							'stats' => array(
								'cpu' => '30%',
								'health' => '85',
								'health_good' => 1
							)
						),
						array(
							'name' => 'Test Server 5',
							'workers' => 10,
							'stats' => array(
								'cpu' => '12%',
								'health' => '96',
								'health_good' => 1
							)
						)
					)
				);

				break;

			case 'getServersByWorkers':
				$response = array(
					'servers' => array(
						array(
							'name' => 'Test Server 3',
							'expected_workers' => 100,
							'workers' => 50,
							'stats' => array(
								'cpu' => '53%',
								'health' => '71',
								'health_bad' => 1
							)
						),
						array(
							'name' => 'Test Server 1',
							'expected_workers' => 40,
							'workers' => 10,
							'stats' => array(
								'cpu' => '80%',
								'health' => '44',
								'health_bad' => 1
							)
						),
						array(
							'name' => 'Test Server 4',
							'expected_workers' => 40,
							'workers' => 30,
							'stats' => array(
								'cpu' => '72%',
								'health' => '53',
								'health_okay' => 1
							)
						),
						
						array(
							'name' => 'Test Server 2',
							'expected_workers' => 20,
							'workers' => 20,
							'stats' => array(
								'cpu' => '30%',
								'health' => '85',
								'health_good' => 1
							)
						),
						array(
							'name' => 'Test Server 5',
							'expected_workers' => 10,
							'workers' => 10,
							'stats' => array(
								'cpu' => '12%',
								'health' => '96',
								'health_good' => 1
							)
						)
					)
				);

				break;
		}

		return $response;
	}
}
