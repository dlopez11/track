<?php

class ApiController extends \Phalcon\Mvc\Controller
{    
	public function gethistoryAction($idUser, $limit = null)
	{
		try {
			$user = $this->validateUser($idUser);
			if (!$user) {
				return $this->set_json_response(array('No se ha encontrado el usuario'), 404);
			}

			$l = (!is_numeric($limit) ? 20 : $limit); 

			$query = $this->modelsManager->createQuery("SELECT Visit.*, Visittype.*, Client.* FROM Visit JOIN Visittype JOIN Client WHERE Visit.idUser = :idUser: ORDER BY Visit.idVisit DESC LIMIT {$l}");
			$res  = $query->execute(array(
			   'idUser' => $idUser
			));

			$data = array();

			if (count($res) > 0) {
				foreach ($res as $value) {
					$obj = new stdClass();
					$obj->idVisit = $value->visit->idVisit;
					$obj->type = $value->visittype->name;
					$obj->client = $value->client->name;
					$obj->start = date('d/M/Y H:s', $value->visit->start);
					$obj->end = (empty($value->visit->end) ? null : date('d/M/Y H:s', $value->visit->end));
					$obj->elapsed = null;
					$obj->finished = 0;

					if (!empty($value->visit->end)) {
						$time1 = date_create(\date('Y-m-d H:i:s', $value->visit->start));
		                $time2 = date_create(\date('Y-m-d H:i:s', $value->visit->end));
		                $interval = date_diff($time1, $time2);
		                $int = $interval->format("%a:%H:%I:%S");
		                $el = explode(":", $int);

		                $obj->elapsed = ($el[0] == "00" ? "" : $el[0] . "Día(s) ") . $el[1] . ":" . $el[2] . ":" . $el[3];
		                $obj->finished = 1;
					}

					$obj->iLatitude = $value->visit->latitude;
					$obj->iLongitude = $value->visit->longitude;
					$obj->fLongitude = $value->visit->finalLongitude;
					$obj->fLatitude = $value->visit->finalLatitude;

					$data[] = $obj;
				}
			}

			return $this->set_json_response(array("history" => $data), 200);	
		}
		catch(Exception $ex) {
			$this->logger->log("Exception while getting visit history {$ex->getMessage()}");
			return $this->set_json_response(array("history" => array(-1)), 500);	
		}
	}


	public function validateloginAction()
	{
		if ($this->request->isPost()) {
			try {
				$username = $this->request->getPost("username");
                $password = $this->request->getPost("password");
                $company = $this->request->getPost("idAccount");

                $user = User::findFirst(array(
                    "userName = ?0 AND idAccount = ?1",
                    "bind" => array(
                                0 => $username,
                                1 => $company
                            )
                ));

                $object = new stdClass();
                $status = 0;

                if ($user && $this->hash->checkHash($password, $user->password)) {
                	$object->idUser = $user->idUser;
                	$object->name = $user->name;	
                	$object->lastName = $user->lastName;
                	$status = 1;
                }

                return $this->set_json_response(array("status" => array($status), "user" => array($object)), 200);
			}
			catch(Exception $ex) {
				$this->logger->log("Exception while login {$ex->getMessage()}");
				return $this->set_json_response(array("status" => array(-1)), 500);	
			}
        }

        return $this->set_json_response(array("status" => array(0)), 200);
	}


	public function getclientsandvisittypesAction($idUser) 
	{

		try {
			$user = $this->validateUser($idUser);

			if (!$user) {
				return $this->set_json_response(array('No se ha encontrado el usuario'), 404);
			}

			$clients = Client::find(array(
				'conditions' => 'idAccount = ?0',
				'bind' => array($user->idAccount)
			));

			$c = array();

			if (count($clients) > 0) {
				foreach ($clients as $client) {
					$obj = new stdClass();
					$obj->idClient = $client->idClient;
					$obj->name = $client->name;

					$c[] = $obj;
				}
			}	

			$visittypes = Visittype::find(array(
				'conditions' => 'idAccount = ?0',
				'bind' => array($user->idAccount)
			));

			$v= array();

			if (count($visittypes) > 0) {
				foreach ($visittypes as $visittype) {
					$obj = new stdClass();
					$obj->idVisittype = $visittype->idVisittype;
					$obj->name = $visittype->name;

					$v[] = $obj;
				}
			}	

			return $this->set_json_response(array("clients" => $c, "visittypes" => $v), 200);

		}
		catch(Exception $ex) {
			$this->logger->log("Exception while getting clients and visittypes {$ex->getMessage()}");
			return $this->set_json_response(array("status" => array(-1)), 500);	
		}		
	}


	public function newvisitAction() 
	{
		if ($this->request->isPost()) {
			try {
				$idUser = $_POST['idUser'];
				$idVisittype = $_POST['idVisittype'];
				$idClient = $_POST['idClient'];
				$latitude = $_POST['latitude'];
				$longitude = $_POST['longitude'];
				$battery = $_POST['battery'];
				$location = $_POST['location'];

				$latitude = trim($latitude);
				$longitude = trim($longitude);
				$battery = trim($battery);
				$location = trim($location);

				$this->logger->log("idUser: {$idUser}");
				$this->logger->log("idVisittype: {$idVisittype}");
				$this->logger->log("idClient: {$idClient}");
				$this->logger->log("latitude: {$latitude}");
				$this->logger->log("longitude: {$longitude}");
				$this->logger->log("battery: {$battery}");
				$this->logger->log("location: {$location}");

				$this->validateVisit($idUser, $idVisittype, $idClient, $latitude, $longitude, $battery, $location);

				$visit = new Visit();
				$visit->idUser = $idUser;
				$visit->idVisittype = $idVisittype;
				$visit->idClient = $idClient;
				$visit->latitude = $latitude;
				$visit->longitude = $longitude;
				$visit->battery = $battery;
				$visit->location = $location;
				$visit->start = time();
				$visit->finished = 0;

				if (!$visit->save()) {
					$message = "";
					foreach ($visit->getMessages() as $msg) {
						$message .= ", {$msg}";
					}
					throw new Exception($message, 1);
				}

				return $this->set_json_response(array("status" => array(1)), 200);
			}
			catch (Exception $ex) {
				$this->logger->log("Exception while creating new visit: {$ex->getMessage()}");
				return $this->set_json_response(array("status" => array(-1)), 500);
			}
		}		

		return $this->set_json_response(array("status" => array(0)), 200);
	}


	public function closevisitAction() 
	{
		if ($this->request->isPost()) {
			try {
				$idVisit = $_POST['idVisit'];
				$idUser = $_POST['idUser'];
				$fLatitude = $_POST['fLatitude'];
				$fLongitude = $_POST['fLongitude'];
				$fLocation = $_POST['fLocation'];

				$idVisit = trim($idVisit);
				$idUser = trim($idUser);
				$fLatitude = trim($fLatitude);
				$fLongitude = trim($fLongitude);
				$fLocation = trim($fLocation);

				$this->logger->log("idVisit: {$idVisit}");
				$this->logger->log("idUser: {$idUser}");
				$this->logger->log("fLatitude: {$fLatitude}");
				$this->logger->log("fLongitude: {$fLongitude}");
				$this->logger->log("fLocation: {$fLocation}");

				$visit = $this->validateCloseVisit($idVisit, $idUser, $fLongitude, $fLatitude, $fLocation); 

				$visit->fLatitude = $fLatitude;
				$visit->fLongitude = $fLongitude;
				$visit->fLocation = $fLocation;
				$visit->end = time();
				$visit->finished = 1;

				$this->logger->log("SAVING");

				if (!$visit->save()) {
					$message = "";
					foreach ($visit->getMessages() as $msg) {
						$message .= ", {$msg}";
					}
					throw new Exception($message, 1);
				}

				return $this->set_json_response(array("status" => array(1)), 200);
                        }	
			catch(Exception $ex) {
				$this->logger->log("Exception while closing visit: {$ex->getMessage()}");
				return $this->set_json_response(array("status" => array(-1)), 500);
			}
		}		
	}


	private function validateCloseVisit($idVisit, $idUser, $fLongitude, $fLatitude, $fLocation) 
	{
		$visit = Visit::findFirst(array(
			'conditions' => 'idVisit = ?0',
			'bind' => array($idVisit)
		));

		if (!$visit) {
			throw new Exception("Visit do not exists", 1);
		}

		if ($visit->idUser != $idUser) {
			throw new Exception("Visit do not exists", 1);
		}

		$user = User::findFirst(array(
			'conditions' => 'idUser = ?0',
			'bind' => array($idUser)
		));

		if (!$user) {
			throw new Exception("User do not exists", 1);
		}

		if (empty($fLongitude)) {
			throw new Exception("flatitude is null", 1);
		}

		if (empty($fLatitude)) {
			throw new Exception("flongitude is null", 1);
		}

		if (empty($fLocation)) {
			throw new Exception("flocation is null", 1);
		}

		return $visit;
	}

	private function validateVisit($idUser, $idVisittype, $idClient, $latitude, $longitude, $battery, $location) 
	{
		$user = User::findFirst(array(
			'conditions' => 'idUser = ?0',
			'bind' => array($idUser)
		));

		$visitType = Visittype::findFirst(array(
			'conditions' => 'idVisittype = ?0',
			'bind' => array($idVisittype)
		));

		$client = Client::findFirst(array(
			'conditions' => 'idClient = ?0',
			'bind' => array($idClient)
		));

		if (!$user || !$visitType || !$client) {
			throw new Exception("User, Visittype or Client do not exists", 1);
		}

		if (empty($latitude)) {
			throw new Exception("latitude is null", 1);
		}

		if (empty($longitude)) {
			throw new Exception("longitude is null", 1);
		}

		if (empty($battery)) {
			throw new Exception("battery is null", 1);
		}

		if (empty($location)) {
			throw new Exception("location is null", 1);
		}

	}

	public function getclientsAction($idUser) 
	{
		$user = $this->validateUser($idUser);
		if (!$user) {
			return $this->set_json_response(array('No se ha encontrado el usuario'), 404);
		}

		$clients = Client::find(array(
			'conditions' => 'idAccount = ?0',
			'bind' => array($user->idAccount)
		));

		$data = array();

		if (count($clients) > 0) {
			foreach ($clients as $client) {
				$obj = new stdClass();
				$obj->idClient = $client->idClient;
				$obj->name = $client->name;

				$data[] = $obj;
			}
		}	

		return $this->set_json_response(array("clients" => $data), 200);	
	}

	public function getvisittypesAction($idUser) 
	{
		$user = $this->validateUser($idUser);
		if (!$user) {
			return $this->set_json_response(array('No se ha encontrado el usuario'), 404);
		}

		$visittypes = Visittype::find(array(
			'conditions' => 'idAccount = ?0',
			'bind' => array($user->idAccount)
		));

		$data = array();

		if (count($visittypes) > 0) {
			foreach ($visittypes as $visittype) {
				$obj = new stdClass();
				$obj->idVisittype = $visittype->idVisittype;
				$obj->name = $visittype->name;

				$data[] = $obj;
			}
		}	

		return $this->set_json_response(array("visittypes" => $data), 200);	
	}


	 /**
     * Llamar este metodo para enviar respuestas en modo JSON
     * @param string $content
     * @param int $status
     * @param string $message
     * @return \Phalcon\Http\ResponseInterface
     */
    public function set_json_response($content, $status = 200, $message = '') 
    {
        $this->view->disable();

        $this->_isJsonResponse = true;
        $this->response->setContentType('application/json', 'UTF-8');

        if ($status != 200) {
                $this->response->setStatusCode($status, $message);
        }
        if (is_array($content)) {
                $content = json_encode($content);
        }
        $this->response->setContent($content);
        return $this->response;
    }

    private function validateUser($idUser) {
    	$user = User::findFirst(array(
			'conditions' => 'idUser = ?0',
			'bind' => array($idUser)
		));

		return $user;
    }
}	