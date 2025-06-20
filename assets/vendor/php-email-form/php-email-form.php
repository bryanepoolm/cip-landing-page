<?php
/**
 * PHP Email Form
 * Version: 1.0
 * Author: BootstrapMade.com
 */

class PHP_Email_Form {

  public $to = '';
  public $from_name = '';
  public $from_email = '';
  public $subject = '';

  public $smtp = array();

  public $messages = array();

  public $ajax = false;

  public function add_message($content, $label = '', $length = 0) {
    if (!empty($length) && strlen($content) < (int)$length) {
      return;
    }

    $message = '';
    if (!empty($label)) {
      $message = "<strong>$label:</strong> ";
    }

    $message .= htmlspecialchars($content) . "<br>";

    $this->messages[] = $message;
  }

  public function send() {
    $message_content = implode('', $this->messages);

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=utf-8' . "\r\n";

    $headers .= 'From: '. $this->from_name .' <'. $this->from_email .'>' . "\r\n";
    $headers .= 'Reply-To: '. $this->from_email . "\r\n";

    $subject = $this->subject;

    if (!empty($this->smtp)) {
      return $this->smtp_send($subject, $message_content, $headers);
    } else {
      if (mail($this->to, $subject, $message_content, $headers)) {
        return 'OK';
      } else {
        return 'No se pudo enviar el correo.'.var_dump($this->to, $subject, $message_content, $headers);
      }
    }
  }

  private function smtp_send($subject, $message_content, $headers) {
    require_once 'PHPMailer/PHPMailer.php';
    require_once 'PHPMailer/SMTP.php';
    require_once 'PHPMailer/Exception.php';

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
      $mail->isSMTP();
      $mail->Host = $this->smtp['host'];
      $mail->SMTPAuth = true;
      $mail->Username = $this->smtp['username'];
      $mail->Password = $this->smtp['password'];
      $mail->SMTPSecure = 'tls';
      $mail->Port = $this->smtp['port'];

      $mail->setFrom($this->from_email, $this->from_name);
      $mail->addAddress($this->to);
      $mail->addReplyTo($this->from_email, $this->from_name);

      $mail->isHTML(true);
      $mail->Subject = $subject;
      $mail->Body = $message_content;

      $mail->send();
      return 'OK';
    } catch (Exception $e) {
      return 'Mailer Error: ' . $mail->ErrorInfo;
    }
  }
}