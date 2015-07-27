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
    private $user = null;
    private $logo;
    
    public function __construct() 
    {
        $this->logger = \Phalcon\DI::getDefault()->get('logger');
        $this->path = \Phalcon\DI::getDefault()->get('path');
    }
    
    public function setUser(\User $user)
    {
        $this->user = $user;
    }
    
    public function setAccount(\Account $account) 
    {
        $this->account = $account;
    }
    
    
    public function setData($data)
    {
        $this->data = $data;
    }
    
    public function setLogoDir($logo)
    {
        $this->logo = $logo;
    }
    
    public function create() {
        $this->createExcelObject();

        $preheader = new \stdClass();
        $preheader->name = "Sigma Track";
        $preheader->desc = "Sigma Track Engine 2015";
        $preheader->logo = $this->logo;
        $preheader->x = 8;
        $preheader->y = 5;
        $preheader->coordinates = "A1";
        $preheader->height = 75;
        
        $this->addLogo($preheader);
        
        $firm = array(
            array('key' => 'B2', 'name' => "Fecha: " . date('d/M/Y H:s')),
            array('key' => 'B4', 'name' => "Cuenta: ". $this->account->name),
        );
        
        $this->createExcelHeader($firm);
        
        if ($this->user != null) {
            $firm = array(
                array('key' => 'B3', 'name' => "Elaborado por: {$this->user->name} {$this->user->lastName}"),
            );

            $this->createExcelHeader($firm);
        }
        
        $header = array(
            array('key' => 'A6', 'name' => "FECHA"),
            array('key' => 'B6', 'name' => "NOMBRE DE USUARIO"),
            array('key' => 'C6', 'name' => "TIPO DE VISITA"),
            array('key' => 'D6', 'name' => "CLIENTE"),
            array('key' => 'E6', 'name' => "ESTADO DE BATERÍA"),
            array('key' => 'F6', 'name' => "ÚLTIMA VISITA"),
            array('key' => 'G6', 'name' => "UBICACIÓN"),
            array('key' => 'H6', 'name' => "MAPA"),
            array('key' => 'I6', 'name' => "OBSERVACIONES")
        );

        $this->createExcelHeader($header);
	
        $row = 7;
        foreach ($this->data as $data) {
            $array = array(
                $data['start'] . " - " . $data['end'],
                $data['name'],
                $data['visit'],
                $data['client'],
                "{$data['battery']}%",
                $data['lastVisit'],
                $data['location'],
                "http://maps.google.com/maps?q={$data['latitude']},{$data['longitude']}&ll={$data['latitude']},-{$data['longitude']}&z=17",
                $data['observation'],
            );

            $this->phpExcelObj->getActiveSheet()->fromArray($array, NULL, "A{$row}");
            unset($array);
            $row++;
        }

        $this->styleExcelHeader('A6:I6');

        $array = array(
            array('key' => 'A', 'size' => 50),
            array('key' => 'B', 'size' => 30),
            array('key' => 'C', 'size' => 40),
            array('key' => 'D', 'size' => 40),
            array('key' => 'E', 'size' => 20),
            array('key' => 'F', 'size' => 30),
            array('key' => 'G', 'size' => 50),
            array('key' => 'H', 'size' => 90),
            array('key' => 'I', 'size' => 90),
        );

        $this->setColumnDimesion($array);
        $this->createExcelFilter("B7:B{$row}");
        $this->createExcelFilter("C7:C{$row}");
        $this->createExcelFilter("D6:D{$row}");

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
                ->getStartColor()->setARGB('474646');

        $styleArray = array(
            'borders' => array(
                'outline' => array(
                    'color' => array('argb' => '00bede'),
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

    private function addLogo($preheader)
    {
        $objDrawing = new \PHPExcel_Worksheet_Drawing();$objDrawing->setName($preheader->name);
        $objDrawing->setDescription($preheader->desc);
        $objDrawing->setPath($preheader->logo);
        $objDrawing->setOffsetX($preheader->x);    // setOffsetX works properly
        $objDrawing->setOffsetY($preheader->y);  //setOffsetY has no effect
        $objDrawing->setCoordinates($preheader->coordinates);
        $objDrawing->setHeight($preheader->height); // logo height
        $objDrawing->setWorksheet($this->phpExcelObj->getActiveSheet()); 
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