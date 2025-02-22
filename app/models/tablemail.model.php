<?php

class Table_mail {
  private $name;
  private $surname;
  private $birthdate;
  private $type;
  private $email;
  private $table;

  function __construct($mailInfo = []) {
    $this->name = $mailInfo[0];
    $this->surname = $mailInfo[1];
    $this->birthdate = $mailInfo[2];
    $this->type = $mailInfo[3];
    $this->email = $mailInfo[4];
    $mailInfo[5] ? $this->table = $mailInfo[5] : 'none';
  }

  function sendTableMail() {
    if (file_exists(__DIR__."//services//mailer.php")) {
      require_once(__DIR__."//services//mailer.php");
      $mailer = new Mailer([$this->name, $this->surname, $this->birthdate, $this->type, $this->email, $this->table]);
      $debug_message = $mailer->sendTableMail();

      if ($debug_message) {
        return 'sent';
      } else {
        return 'error';
      }
    }
  }
}