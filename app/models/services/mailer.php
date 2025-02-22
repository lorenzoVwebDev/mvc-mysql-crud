<?php
require __DIR__.'//..//..//..//vendor//autoload.php';
require(__DIR__. '//..//..//..//vendor//autoload.php');


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer {
  private $name;
  private $surname;
  private $date;
  private $type;
  private $email;
  private $table;

 function __construct($mailArray) {
  $this->name = $mailArray[0];
  $this->surname = $mailArray[1];
  $this->date = $mailArray[2];
  $this->type = $mailArray[3];
  $this->email = $mailArray[4];
  $this->table = $mailArray[5];
  } 
//
  function sendTableMail() {

    try {
      $mail = new PHPMailer(true);
      if (file_exists(__DIR__."//..//..//..//logs//mailDebugOutput//mailDebugOutput". date('mdy').".log")) {
        try {
          $file = fopen( __DIR__."//..//..//..//logs//mailDebugOutput//mailDebugOutput". date('mdy').".log",'w');
          fclose($file);
          unset($file);
        } catch (Throwable $t) {
          throw new Exception($t->getMessage(), 500);
        }
      } 

      $mail->Debugoutput = function($str, $level) {
        try {
        error_log($str, 3, __DIR__."//..//..//..//logs//mailDebugOutput//mailDebugOutput". date('mdy').".log");
        } catch (Throwable $t) {
            throw new Exception($t->getMessage(), 500);
        }
      };

      $mail->Timeout = 30; 
      $mail->isSMTP();
      $mail->Host = 'smtp.zoho.eu';
      $mail->SMTPAuth = true;
      $mail->Username = EMAILUSERNAME;
      $mail->Password = EMAILPASSWORD;
      $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
      $mail->Port = 587;
      $mail->SMTPDebug = 1;
    
      $mail->setFrom('lorenzoviganego@lorenzo-viganego.com', 'LorenzoVwebdev');
      $users = array (
        array (
          $this->name . " " .$this->surname,
          $this->email
        )
        );
    
        foreach ($users as $user) {
          $mail->addAddress($user[1]);
    
          $mail->IsHTML(true);
          $mail->Subject = 'MVC mailer form portfolio project';
          $mail->Body = "admin ". $user[0]. "\n";
          $mail->Body .= $this->type." table ".$this->date;
          $mail->Body .= $this->table;
          if ($mail->send()) {
            return 'sent';
          } else {
            return 'not sent';
          }
    
          $mail->clearAddresses();
        }

     } catch (Exception $e) {
/*       print $e; */
     }
  }
}
