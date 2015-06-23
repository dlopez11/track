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
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
    }
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setAccount(\Account $account)
    {
        $this->account = $account;
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
            $PHPExcel->setAccount($this->account);
            $PHPExcel->setData($rows['data']);
            $PHPExcel->create();
            $this->report = $PHPExcel->getReportData();
        } 
        catch (Exception $ex) {
            $this->logger->log($ex->getMessage());
            return $this->set_json_response('ha ocurrido un error, por favor contacte al administrador', 500);
        }
    }
    
    public function getReport()
    {
        return $this->report;
    }
}