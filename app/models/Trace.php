<?php

class Trace extends \Phalcon\Mvc\Model
{
    
    public function initialize()
    {
        
    }
    
    public function getSource()
    {
            return "trace";
    }	

    public static function createTrace($user, $status, $operation, $msg, $date, $ip)
    {
        try {
            $trace = new self();
            
            $trace->idUser = (!$user ? 0 : $user);
            $trace->result = $status;
            $trace->operation = $operation;
            $trace->description = $msg;
            $trace->location = 'Empty';
            $trace->date = $date;
            $trace->ip = ip2long($ip);

            if (!$trace->save()) {
                $message = 'Error while saving trace' . PHP_EOL;
                foreach ($trace->getMessages() as $msg) {
                        $message .= " - {$msg}" . PHP_EOL;
                }
                self::saveInLog($user, $status, $operation, $msg, $date, $ip, $msg);
            }
        }
        catch (Exception $e) {
            $msg = "Exception while saving trace" . PHP_EOL;
            $msg .= $e;
            self::saveInLog($user, $status, $operation, $msg, $date, $ip, $msg);
        }
    }
    
    protected static function saveInLog($user, $status, $operation, $msg, $date, $ip)
    {
        $logger = Phalcon\DI::getDefault()->get('logger');

        $logger->log("***************************************************************************************");
        $logger->log("***************************************************************************************");
        $logger->log("{$msg}");
        $logger->log("***************************************************************************************");
        $logger->log("***************************************************************************************");
        $logger->log("User: {$user->idUser}/{$user->username}");
        $logger->log("Result: {$status}");
        $logger->log("Operation: {$operation}");
        $logger->log("Desc: {$msg}");
        $logger->log("Date: " . date('d/m/Y H:i', $date));
        $logger->log("IP: {$ip}");
        $logger->log("***************************************************************************************");
        $logger->log("***************************************************************************************");
    }
}
