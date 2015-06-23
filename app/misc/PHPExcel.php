<?php
/**
 * Description of PaginationDecorator
 *
 * @author Will
 */
namespace Sigmamovil\Misc;

$path = \Phalcon\DI\FactoryDefault::getDefault()->get('path');
require_once "{$path->path}app/library/phpexcel/PHPExcel.php";

class PHPExcel
{
    private $logger;
    private $path;
    private $data;
    private $report;
    private $phpExcelObj;
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
        $this->path = \Phalcon\DI::getDefault()->get('path');
    }
    
    public function setAccount(\Account $account) 
    {
        $this->account = $account;
    }
    
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    
    public function create() {
        $this->createExcelObject();

        $header = array(
            array('key' => 'A3', 'name' => "FECHA"),
            array('key' => 'B3', 'name' => "NOMBRE DE USUARIO"),
            array('key' => 'C3', 'name' => "TIPO DE VISITA"),
            array('key' => 'D3', 'name' => "CLIENTE"),
            array('key' => 'E3', 'name' => "ESTADO DE BATERÍA"),
            array('key' => 'F3', 'name' => "UBICACIÓN")
        );

        $this->createExcelHeader($header);
	
        $row = 4;
        foreach ($this->data as $data) {
            $array = array(
                $data['date'],
                $data['name'],
                $data['visit'],
                $data['client'],
                $data['battery'],
                $data['location'],
            );

            $this->phpExcelObj->getActiveSheet()->fromArray($array, NULL, "A{$row}");
            unset($array);
            $row++;
        }

        $this->styleExcelHeader('A3:F3');

        $array = array(
            array('key' => 'A', 'size' => 15),
            array('key' => 'B', 'size' => 30),
            array('key' => 'C', 'size' => 40),
            array('key' => 'D', 'size' => 40),
            array('key' => 'E', 'size' => 20),
            array('key' => 'E', 'size' => 50),
        );

        $this->setColumnDimesion($array);
        $this->createExcelFilter("B3:B{$row}");
        $this->createExcelFilter("C3:C{$row}");
        $this->createExcelFilter("D3:D{$row}");

        $this->createExcelFile();
    }
    
    private function createExcelObject() {
        // Create new PHPExcel object
        $this->phpExcelObj = new \PHPExcel();
        // Set document properties
        $this->phpExcelObj->getProperties()->setCreator('Sigma Móvil Engine')
                ->setLastModifiedBy('Sigma Móvil Engine')
                ->setTitle("Reporte de visitas")
                ->setSubject('Reporte de visitas')
                ->setDescription("Reporte detallado de visitas registradas de usuarios")
                ->setKeywords('visits sales report excel')
                ->setCategory('Report');
    }

    private function createExcelHeader($array) {
        $this->phpExcelObj->setActiveSheetIndex(0);
        foreach ($array as $value) {
            $this->phpExcelObj->getActiveSheet()->setCellValue($value['key'], $value['name']);
        }
    }

    private function styleExcelHeader($fields) {
        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getFont()->setBold(true);
        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getAlignment()->setWrapText(TRUE);
        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getFill()
                ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
                ->getStartColor()->setARGB('AAAAAAAA');

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'style' => \PHPExcel_Style_Border::BORDER_MEDIUM,
                    'color' => array('argb' => '00000000'),
                ),
            ),
        );

        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->applyFromArray($styleArray);
        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getFont()->getColor()->setARGB('FFFFFFFF');
    }

    private function setColumnDimesion($array) {
        foreach ($array as $value) {
            $this->phpExcelObj->getActiveSheet()->getColumnDimension($value['key'])->setWidth($value['size']);
        }
    }

    private function formatUSDNumbers($fields) {
        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getNumberFormat()->setFormatCode(\PHPExcel_Style_NumberFormat::FORMAT_CURRENCY_USD_SIMPLE);
//        $this->phpExcelObj->getActiveSheet()->getStyle($fields)->getNumberFormat()->setFormatCode('@');
    }

    private function createExcelFilter($fields) {
        $this->phpExcelObj->getActiveSheet()->setAutoFilter($fields);
    }

    private function createExcelFile() {
        $this->phpExcelObj->setActiveSheetIndex(0);
        $objWriter = \PHPExcel_IOFactory::createWriter($this->phpExcelObj, 'Excel2007');
        $this->saveReport($objWriter);
    }

    private function saveReport($objWriter) {
        $folder = "{$this->path->path}{$this->path->tmpfolder}{$this->account->idAccount}/";

        if (!\file_exists($folder)) {
            \mkdir($folder, 0777, true);
        }

        $name = "{$this->account->idAccount}-" . date('d-M-Y-His', time()) . "-" . uniqid() . ".xlsx";
        $folder .= $name;
        $objWriter->save($folder);

        $this->report = new \Tmpreport();
        $this->report->idAccount = $this->account->idAccount;
        $this->report->idReport = $this->report->idReport;
        $this->report->name = $name;
        $this->report->created = time();

        if (!$this->report->save()) {
            foreach ($this->report->getMessages() as $message) {
                $this->logger->log("Error while saving tmpreport {$message->getMessage()}");
            }
            throw new \Exception("Error while saving tmpreport...");
        }
    }

    public function getReportData() {
        return $this->report;
    }
}