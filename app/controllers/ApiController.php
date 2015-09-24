<?php

class ApiController extends \Phalcon\Mvc\Controller
{    
	public function gethistoryAction($idUser)
	{
		$user = $this->validateUser($idUser);
		if (!$user) {
			return $this->set_json_response(array('No se ha encontrado el usuario'), 404);
		}

		$query = $this->modelsManager->createQuery("SELECT Visit.*, Visittype.*, Client.* FROM Visit JOIN Visittype JOIN Client WHERE Visit.idUser = :idUser: ORDER BY 'Visit.end' DESC LIMIT 20");
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
				$obj->end = date('d/M/Y H:s', $value->visit->end);
				
				$time1 = date_create(\date('Y-m-d H:i:s', $value->visit->start));
                $time2 = date_create(\date('Y-m-d H:i:s', $value->visit->end));
                $interval = date_diff($time1, $time2);
                $int = $interval->format("%a:%H:%I:%S");
                $el = explode(":", $int);


                $obj->elapsed = ($el[0] == "00" ? "" : $el[0] . "Día(s) ") . $el[1] . ":" . $el[2] . ":" . $el[3];

				$obj->iLatitude = $value->visit->latitude;
				$obj->iLongitude = $value->visit->longitude;
				$obj->fLongitude = $value->visit->finalLongitude;
				$obj->fLatitude = $value->visit->finalLatitude;

				$data[] = $obj;
			}
		}

		return $this->set_json_response(array("history" => $data), 200);	
	}


	public function validateloginAction()
	{
		if ($this->request->isPost()) {
                $username = $this->request->getPost("username");
                $password = $this->request->getPost("password");
                $company = $this->request->getPost("company");

                $this->logger->log("company: {$company}");
                $this->logger->log("username: {$username}");
                $this->logger->log("password: {$password}");


                $user = User::findFirst(array(
                    "userName = ?0 AND idAccount = ?1",
                    "bind" => array(
                                0 => $username,
                                1 => $company
                            )
                ));

                if ($user && $this->hash->checkHash($password, $user->password)) {
                	$this->logger->log("1");
                    $status = 1;
                }
                else {
                	$this->logger->log("-1");
                    $status = -1;
                }

                return $this->set_json_response(array("response" => array($status)), 200);
        }

        return $this->set_json_response(array("response" => array(0)), 200);
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