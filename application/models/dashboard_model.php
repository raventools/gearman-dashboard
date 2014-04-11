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

			case 'getPackages':
				$response = array(
					'status' => 'OK',
					'detail' => 'Packages',

					'data' => array(
						'test/package/1' => array(
							'queued' => 12,
							'active' => 20,
							'workers' => 20
						),
						'test/package/2' => array(
							'queued' => 0,
							'active' => 10,
							'workers' => 40
						),
						'test/package/3' => array(
							'queued' => 142,
							'active' => 60,
							'workers' => 60
						)
					)
				);

				break;

			case 'getWorkersServers':
				$response = array(
					'status' => 'OK',
					'detail' => 'Workers -> Servers',

					'data' => array(
						'Test Server 1' => array(
							'metapackages' => 'pie,beer,foods',
							'worker_count' => 20,
							'public_ip' => '127.0.0.1'
						),
						'Test Server 2' => array(
							'metapackages' => 'shuffleboard,tv',
							'worker_count' => 50,
							'public_ip' => '127.0.0.1'
						)
					)
				);

				break;

			case 'getErrorsAll':
				$response = array(
					'status' => 'OK',
					'detail' => 'Errors',

					'data' => array(
						array(
							'message' => 'Unexpected right lane in left lane on line q.',
							'server_name' => 'Test Server 1',
							'ts' => '03/17/2014 02:03:04'
						),
						array(
							'message' => 'Missing id to identify the thing for the action.',
							'server_name' => 'Test Server 4',
							'ts' => '04/02/2014 03:04:05'
						),
						array(
							'message' => 'Terrible exception message. No excuse.',
							'server_name' => 'Test Server 2',
							'ts' => '02/02/2014 04:05:06'
						)
					)
				);

				break;
		}

		return $response;
	}
}
