<?php
/**
 * Description of PaginationDecorator
 *
 * @author Will
 */
namespace Sigmamovil\Misc;

class ReportCreator
{
    private $logger;
    private $account;
    private $report;
    private $rows = array();
    private $user = null;
    private $path;
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
        $this->path = \Phalcon\DI::getDefault()->get('path');
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setAccount(\Account $account)
    {
        $this->account = $account;
    }
    
    public function setUser(\User $user)
    {
        $this->user = $user;
    }
    
    public function process()
    {
        $limit = $this->data['limit'];
        $page = $this->data['page'];
        
        $filter = new \stdClass();
        $filter->user = $this->data['user'];
        $filter->visitType = $this->data['visit'];
        $filter->client = $this->data['client'];
        $filter->date = $this->data['date'];
        
        $pager = new \Sigmamovil\Misc\PaginationDecorator();
        
        $pager->setRowsPerPage($limit);
        $pager->setCurrentPage($page);
        
        $finder = new \Sigmamovil\Misc\VisitFinder();
        
        try {
            $finder->setPaginator($pager);
            $finder->setAccount($this->account);
            $finder->setFilter($filter);
            $finder->load();
            $rows = $finder->getRows();
            
            $PHPExcel = new \Sigmamovil\Misc\PHPExcel();
            $PHPExcel->setLogoDir("{$this->path->path}public/images/excel/logo.png");
            $PHPExcel->setAccount($this->account);
            if ($this->user != null) {
                $PHPExcel->setUser($this->user);
            }
            $PHPExcel->setData($rows['data']);
            $PHPExcel->create();
            $this->report = $PHPExcel->getReportData();
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
            return $this->set_json_response('ha ocurrido un error, por favor contacte al administrador', 500);
        }
    }
    
    public function processFull()
    {
        try {
            $sql_rows = "SELECT v.idVisit AS idVisit, u.idUser AS idUser, v.date AS date, u.name AS name, u.lastName AS lastname, vt.name AS visit, c.name AS client, v.battery AS battery, v.latitude AS latitude, v.longitude AS longitude, v.location AS location, v.lastVisit AS lastVisit "
                    . "FROM visit AS v "
                    . " JOIN user AS u ON (u.idUser = v.idUser) "
                    . " JOIN visittype AS vt ON (vt.idVisittype = v.idVisittype) "
                    . " JOIN client AS c ON (c.idClient = v.idClient) "
                    . " WHERE u.idAccount = {$this->account->idAccount} "
                    . " ORDER BY v.date DESC ";

            $db = \Phalcon\DI::getDefault()->get('db'); 
            $result  = $db->query($sql_rows);
            $this->modelRows($result->fetchAll());
            
            $PHPExcel = new \Sigmamovil\Misc\PHPExcel();
            $PHPExcel->setLogoDir("{$this->path->path}public/images/excel/logo.png");
            $PHPExcel->setAccount($this->account);
            $PHPExcel->setUser($this->user);
            $PHPExcel->setData($this->rows);
            $PHPExcel->create();
            $this->report = $PHPExcel->getReportData();
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
            return $this->set_json_response('ha ocurrido un error, por favor contacte al administrador', 500);
        }
    }
    
    private function modelRows($rows)
    {
        $crows = count($rows); 
        if ($crows > 0) {
            foreach ($rows as $row) {
                $array = array();
                $array['idVisit'] = $row['idVisit'];
                $array['idUser'] = $row['idUser'];
                $array['date'] = date('d/M/Y h:i:s A', $row['date']);
                $array['name'] = "{$row['name']} {$row['lastname']}";
                $array['visit'] = $row['visit'];
                $array['client'] = $row['client'];
                $array['battery'] = $row['battery'];
                $array['latitude'] = $row['latitude'];
                $array['longitude'] = $row['longitude'];
                $array['location'] = $row['location'];
                $array['lastVisit'] = $row['lastVisit'];
                
                $this->rows[] = $array;
            }
        }
    }
    
    public function getReport()
    {
        return $this->report;
    }
}