<?php
/**
 * Description of PaginationDecorator
 *
 * @author Will
 */
namespace Sigmamovil\Misc;

class StatisticWrapper
{
    private $account;
    private $logger;
    private $visits = array();
    private $modelData = array();
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
    }
    
    public function setAccount(\Account $account)
    {
        $this->account = $account;
    }
    
    
    public function processData($type)
    {
        try{
            $this->findVisits();

            switch ($type) {
                case "pie":
                    $this->modelPieData();
                    break;

                case "line":
                    $this->modelLineData();
                    break;

                case "column":
                    $this->modelColumnData();
                    break;

                case "timeline":
                    $this->modelTimelineData();
                    break;
                
                case "timelineuser":
                    $this->modelTimelineuserData();
                    break;

                default:
                    break;
            }
        }
        catch (\Exception $e) {
            $this->logger->log($e->getMessage());
        }
    }
    
    private function findVisits()
    {
        $first_day = strtotime("-29 days");
        $tomorrow = strtotime("Tomorrow");
        $query = "SELECT v.idVisit, v.idVisittype, v.idUser, v.date, u.name, u.lastName, vt.name AS vname FROM Visit AS v JOIN Visittype AS vt ON vt.idVisittype = v.idVisittype JOIN User AS u ON u.idUser = v.idUser WHERE vt.idAccount = {$this->account->idAccount} AND v.date >= {$first_day} AND v.date < {$tomorrow} ORDER BY v.date ";
//        $this->logger->log($query);
        $query_visits = \Phalcon\DI::getDefault()->get('modelsManager')->createQuery($query);
        $this->visits = $query_visits->execute();
    }


    private function modelPieData()
    {
        $data = array();
        $names = array();
        
        foreach ($this->visits as $v) {
            if (!isset($data[$v->idVisittype])) {
                $data[$v->idVisittype] = 1;
            }
            else {
                $data[$v->idVisittype] += 1;
            }
            
            $names[$v->idVisittype] = $v->vname;
        }
        
        foreach ($data as $key => $value) {
            $array = array($names[$key], $value);
            $this->modelData[] = $array;
        }
    }
    
    private function modelLineData()
    {
        $vist = \Visittype::find();
        
        $time = array();
        $visits = array(0, 0);
        $date = strtotime(date("Y-m-d"), time());
        $today = strtotime("+1 days", $date);
        $first_day = strtotime("-29 days", $today);
        
        $time[] = $first_day;
        $j = 0;
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
            $time[] = strtotime("+1 days", $time[$j]);
            $j++;
        }
        
        $time[] = $today;
        
        $vists = array();
        foreach ($vist as $vt) {
            $obj = new \stdClass();
            $obj->idVisittype = $vt->idVisittype;
            $obj->name = $vt->name;
            $obj->data = $visits;
            $vists[] = $obj;
        }
        
        foreach ($this->visits as $visit){
            foreach ($vists as $vt) {
                if ($visit->idVisittype == $vt->idVisittype) {
                    foreach($time AS $key => $v) {
                        if ($visit->date >= $v AND $visit->date < $time[$key+1]) {
                            $vt->data[$key] += 1;
                        }
                    }
                }
            }
        }
        
        $tm = array();
        foreach ($time as $t) {
            $tm[] = date("d/M/Y", $t);
        }
        
        $this->modelData = array(
            'categories' => $tm,
            'data' => $vists
        );
    }
    
    private function modelColumnData()
    {
        $us = \User::findByIdAccount($this->account->idAccount);
        
        $time = array();
        $visits = array(0, 0);
        
        $date = strtotime(date("Y-m-d"), time());
        $today = strtotime("+1 days", $date);
        $first_day = strtotime("-29 days", $today);
        
        $time[] = $first_day;
        $j = 0;
        
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
            $time[] = strtotime("+1 days", $time[$j]);
            $j++;
        }
        
        $time[] = $today;
        
        $users = array();
        foreach ($us as $user) {
            $obj = new \stdClass();
            $obj->idUser = $user->idUser;
            $obj->name = "{$user->name} {$user->lastName}";
            $obj->data = $visits;
            $users[] = $obj;
        }
        
        foreach ($this->visits as $visit){
            foreach ($users as $user) {
                if ($visit->idUser == $user->idUser) {
                    foreach($time AS $key => $v) {
                        if ($visit->date >= $v AND $visit->date < $time[$key+1]) {
                            $user->data[$key] += 1;
                        }
                    }
                }
            }
        }
        
        $tm = array();
        foreach ($time as $t) {
            $tm[] = date("d/M/Y", $t);
        }
        
        $this->modelData = array(
            'time' => $tm,
            'data' => $users
        );
    }
    
    private function modelTimelineData()
    {        
        $time = array();
        $visits = array(0, 0);
        
        $d = strtotime(date("Y-m-d", time()));
        $today = strtotime("+1 days", $d);
        $first_day = strtotime("-29 days", $today);
        
        $time[] = $first_day;
        $j = 0;
        
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
            $time[] = strtotime("+1 days", $time[$j]);
            $j++;
        }
        
        $time[] = $today;
        $total = array();
        $vi = array();
        $obj = new \stdClass();
        $obj->name = "Promedio";
        $obj->data = $visits;
//        $obj->idUser = $visits->idUser;
        $total[] = $obj;
        
//        foreach ($this->visits as $visit){
//            $date = date("Y-m-d", $visit->date);
//            $date_initial = strtotime($date." 00:00");
//            $date_final = strtotime($date." 23:59");
//
//            $q1 = "SELECT v.date AS date 
//                   FROM visit AS v
//                       JOIN visittype AS vt ON (vt.idVisittype = v.idVisittype) 
//                   WHERE vt.idAccount = {$this->account->idAccount} 
//                       AND v.date >= {$date_initial} 
//                       AND v.date <= {$date_final} 
//                   ORDER BY v.date ASC LIMIT 1";
//
//            $q2 = "SELECT v.date AS date 
//                    FROM visit AS v
//                        JOIN visittype AS vt ON (vt.idVisittype = v.idVisittype) 
//                    WHERE vt.idAccount = {$this->account->idAccount} 
//                        AND v.date >= {$date_initial} 
//                        AND v.date <= {$date_final} 
//                    ORDER BY v.date DESC LIMIT 1";
//
//            $db1 = \Phalcon\DI::getDefault()->get('db');
//            $rq1 = $db1->query($q1);
//            $time_first = $rq1->fetchAll();
//
//            $db2 = \Phalcon\DI::getDefault()->get('db');
//            $rq2 = $db2->query($q2);
//            $time_final = $rq2->fetchAll();
//
//            foreach ($time_first as $ti){
//                $timei = $ti["date"];
//            }
//
//            foreach ($time_final as $tf){
//                $timef = $tf["date"];
//            }
//
//            $h = $timef - $timei;
//            $horas = $h / 3600;
//            
//            foreach($time AS $key => $v) {
//                if ($visit->date >= $v AND $visit->date < $time[$key+1]) {
//                    $vi[$key] += 1;
//                    $r = round($horas / $vi[$key], 2);
//                    $obj->data[$key] = $r;
//                }
//            }
//        }
        
        $tm = array();
        foreach ($time as $t) {
            $tm[] = date("d/M/Y", $t);
        }
        
        $this->modelData = array(
            'time' => $tm,
            'data' => $total
        );                
    }
    
    
    private function modelDateTimes()
    {
        $time = array();
        
        $d = strtotime(date("Y-m-d", time()));
        
        $today = strtotime("+1 days", $d);
        $thirty_days_ago = strtotime("-29 days", $today);
        
        $obj = new \stdClass();
        $obj->date = $thirty_days_ago;
        $obj->times = array();
        
        $time[] = $obj;
        $j = 0;
        
        for ($i = 1; $i < 29; $i++) {
            $obj = new \stdClass();
            $obj->date = strtotime("+1 days", $time[$j]->date);
            $obj->times = array();
            $time[] = $obj;
            $j++;
        }
        
        $obj = new \stdClass();
        $obj->date = $thirty_days_ago;
        $obj->times = array();
        
        $time[] = $obj;
        
        return $time;
    }
    
    private function modelVisits()
    {
        $visits = array(0, 0);
        for ($i = 1; $i < 29; $i++) {
            $visits[] = 0;
        }
        
        return $visits;
    }
    
    private function modelUsers($visits, $times)
    {
        $us = \User::findByIdAccount($this->account->idAccount);
        
        $users = array();
        
        foreach ($us as $user) {
            $obj = new \stdClass();
            $obj->idUser = $user->idUser;
            $obj->name = "{$user->name} {$user->lastName}";
            $obj->data = $visits;
            $obj->times = $times;
            $users[] = $obj;
        }
        
        return $users;
    } 
    
//    private function modelDates()
//    {
//        $d = strtotime(date("Y-m-d", time()));
//        $today = strtotime("+1 days", $d);
//        $first_day = strtotime("-29 days", $today);
//        
//        $time[] = $first_day;
//        $j = 0;
//        
//        for ($i = 1; $i < 29; $i++) {
//            $time[] = strtotime("+1 days", $time[$j]);
//            $j++;
//        }
//        
//        $time[] = $today;
//        
//        return $time;
//    }
    
    private function modelTimelineuserData()
    {
        $times = $this->modelDateTimes();
        $visits = $this->modelVisits();
        $users = $this->modelUsers($visits, $times);
        
        foreach ($this->visits as $visit){
            foreach ($users as $user) {
                if ($visit->idUser == $user->idUser) {
                    $total = count($user->times);
                    foreach ($user->times as $key => $time) {
                        $next = ($key+1 > $total-1 ? strtotime("+1 day", $time->date) : $user->times[$key+1]->date);
                        
                        if ($visit->date >= $time->date && $visit->date < $next) {
                            $time->times[] = $visit->date;
                        }
                    }
                    
                    break;
                }
            }
        }
        
//        $this->logger->log("Users: " . print_r($users, true));
        
        $tm = array();
        foreach ($times as $t) {
            $tm[] = date("d/M/Y", $t->date);
        }
        
        $this->modelData = array(
            'time' => $tm,
            'data' => $users
        ); 
    }

    public function getModelData()
    {
        return $this->modelData;
    }
}