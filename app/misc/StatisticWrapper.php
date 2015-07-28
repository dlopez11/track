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

        $first_day = strtotime("-29 days", time());
        $tomorrow = strtotime("Tomorrow");
//        $query = "SELECT Visit.*, User.name, User.lastName, Visittype.name FROM Visit JOIN Visittype JOIN User WHERE Visittype.idAccount = {$this->account->idAccount} AND Visit.start >= {$first_day} AND Visit.end < {$tomorrow} AND Visit.end <> 0 ORDER BY Visit.end ";
//        $this->logger->log($query);
//        $query_visits = \Phalcon\DI::getDefault()->get('modelsManager')->createQuery($query);
//        $this->visits = $query_visits->execute();
        $query = "SELECT v.idVisit, v.idVisittype, v.idUser, v.start, v.end, u.name, u.lastName, vt.name AS vname FROM visit AS v JOIN visittype AS vt ON vt.idVisittype = v.idVisittype JOIN user AS u ON u.idUser = v.idUser WHERE vt.idAccount = {$this->account->idAccount} AND v.start >= {$first_day} AND v.end < {$tomorrow} AND v.end <> 0 ORDER BY v.end ";
        $result = \Phalcon\DI::getDefault()->get('db')->query($query);
        $this->visits = $result->fetchAll();
    }

    private function modelDateTimes()
    {
        $time = array();
        
        $d = strtotime(date("Y-m-d"));
        
        $today = strtotime("+1 days", $d);
        $thirty_days_ago = strtotime("-29 days", $today);
        
        $obj = new \stdClass();
        $obj->date = $thirty_days_ago;
        $obj->visits = 0;
        $obj->times = array();
        
        $time[] = $obj;
        $j = 0;
        
        for ($i = 1; $i <= 29; $i++) {
            $obj = new \stdClass();
            $obj->date = strtotime("+1 days", $time[$j]->date);
            $obj->visits = 0;
            $obj->times = array();
            $time[] = $obj;
            $j++;
        }
        
        $obj = new \stdClass();
        $obj->date = $thirty_days_ago;
        $obj->visits = 0;
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
    
    private function modelUsers($visits)
    {
        $us = \User::findByIdAccount($this->account->idAccount);
        
        $users = array();
        
        foreach ($us as $user) {
//            $t = $times;
            $obj = new \stdClass();
            $obj->idUser = $user->idUser;
            $obj->name = "{$user->name} {$user->lastName}";
            $obj->data = $visits;
            $obj->times = $this->modelDateTimes();
//            $obj->times = &$t;
            $users[] = $obj;
//            unset($t);
        }
        
        return $users;
    }
    
    private function modelTotalVisits($visits, $time)
    {        
        $t = \User::findByIdAccount($this->account->idAccount);
        
        $totall = array();
        
        foreach ($t as $tt) {
            $totall = array();
            $obj = new \stdClass();
            $obj->idUser = $tt->idUser;
            $obj->name = "Promedio";
            $obj->data = $visits;
            $obj->times = $time;
            $totall[] = $obj;
        }
        
        return $totall;
    }
    
    private function modelPieData()
    {
        $data = array();
        $names = array();
        
        foreach ($this->visits as $v) {
            if (!isset($data[$v['idVisittype']])) {
                $data[$v['idVisittype']] = 1;
            }
            else {
                $data[$v['idVisittype']] += 1;
            }
            
            $names[$v['idVisittype']] = $v['name'];
        }
        
        foreach ($data as $key => $value) {
            $array = array($names[$key], $value);
            $this->modelData[] = $array;
        }
    }
    
    private function modelLineData()
    {
        $vist = \Visittype::findByIdAccount($this->account->idAccount);
        
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
                        if ($visit->start >= $v AND $visit->end < $time[$key+1]) {
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
                        if ($visit->end >= $v AND $visit->end < $time[$key+1]) {
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
        $times = $this->modelDateTimes();
        $visits = $this->modelVisits();
        $totall = $this->modelTotalVisits($visits, $times);
        
        foreach ($this->visits as $visit){
            foreach ($totall as $tt) {
                $total = count($tt->times);
                foreach ($tt->times as $key => $time) {
                    $next = ($key+1 > $total-1 ? strtotime("+1 day", $time->date) : $tt->times[$key+1]->date);
                    
                    if ($visit->start >= $time->date && $visit->end < $next) {
                            $time->visits += 1;
                            $time->times[] = $visit->start;
                            $time->times[] = $visit->end;
                    }
                }
                break;
            }
        }
        
        $this->logger->log("Array " . print_r($totall, true));
        
        foreach ($totall as $t) {
            foreach ($t->times as $key => $ts) {
                if ($ts->visits > 1) {
                    $first = array_shift($ts->times);
                    $last = array_pop($ts->times);
                    $pprom = ($last-$first);
                    $prom = round((($pprom/$ts->visits)/3600), 2);
                    
                    $t->data[$key] = $prom;
                }
            }
        }
        
        foreach ($totall as $t) {
            unset($t->times);
        }
        
        $tm = array();
        foreach ($times as $t) {
            $tm[] = date("d/M/Y", $t->date);
        }
        
        $this->modelData = array(
            'time' => $tm,
            'data' => $totall
        ); 
    }
    
    private function modelTimelineuserData()
    {
        $times = $this->modelDateTimes();
        $visits = $this->modelVisits();
        $users = $this->modelUsers($visits);
        
        foreach ($this->visits as $visit){
            foreach ($users as $key1 => $user) {
                if ($visit->idUser == $user->idUser) {
                    $total = count($user->times);
                    foreach ($user->times as $key => $time) {
                        $next = ($key+1 >= $total-1 ? strtotime("+1 day", $time->date) : $user->times[$key+1]->date);
                        if ($visit->start >= $time->date && $visit->end < $next) {
                            $time->visits += 1;
                            $users[$key1]->times[$key]->times[] = $visit->start;
                            $users[$key1]->times[$key]->times[] = $visit->end;
                        }
                    }                    
                    break;
                }                
            }
        }
        
        foreach ($users as $us) {
            foreach ($us->times as $key => $ts) {
                if ($visits > 1) {
                    $first = array_shift($ts->times);
                    $last = array_pop($ts->times);
                    $pprom = ($last-$first);
                    $prom = round((($pprom/$ts->visits)/3600), 2);
                    
                    $us->data[$key] = $prom;
                }
            }
        }
        
        foreach ($users as $us) {
            unset($us->times);
        }
        
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