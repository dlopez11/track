<?php

class ApiController extends \Phalcon\Mvc\Controller
{    
	public function gethistoryAction($idUser)
	{
		$user = User::findFirst(array(
			'conditions' => 'idUser = ?0',
			'bind' => array($idUser)
		));

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
				$time1 = date_create(\date('Y-m-d H:i:s', $row['end']));
                $time2 = date_create(\date('Y-m-d H:i:s', $row['start']));
                $interval = date_diff($time1, $time2);
                $obj->elapsed = $interval->format("%a día(s) %H:%I:%S%");
				$obj->iLatitude = $value->visit->latitude;
				$obj->iLongitude = $value->visit->longitude;
				$obj->fLongitude = $value->visit->finalLongitude;
				$obj->fLatitude = $value->visit->finalLatitude;

				$data[] = $obj;
			}
		}

		return $this->set_json_response(array("history" => $data), 200);	
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
}	