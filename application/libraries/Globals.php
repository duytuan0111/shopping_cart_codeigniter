<?php 
    /**
     * 
     */
    class Globals
    {

       public function get_dateformat($strDate, $format)
       {
        $date=date_create($strDate);
        return date_format($date, $format);
    }

    public function sendMail($to, $subject, $content)
    {
        require_once(APPPATH.'/libraries/phpmailer/class.phpmailer.php');
        require_once(APPPATH.'/libraries/phpmailer/class.smtp.php');
        $mail = new PHPMailer();

         $mail->IsSMTP(); // set mailer to use SMTP

        $mail->SMTPOptions = array(
            'ssl' => array(
                'verify_peer' => false,
                'verify_peer_name' => false,
                'allow_self_signed' => true
            )
        );
        $mail->Host='smtp.gmail.com';
        $mail->Port = '465'; // set the port to use
        $mail->SMTPAuth = true; // turn on SMTP authentication

        $mail->SMTPSecure='ssl';
        $mail->Username = 'pposcontact@gmail.com'; // your SMTP username or your gmail username
        $mail->Password = 'zjqogymqmihkmjrx'; // your SMTP password or your gmail password
        $mail->Timeout = 3600;

        $mail->From = 'pposcontact@gmail.com';
        $mail->FromName = 'Corona Crypto';
        // Name to indicate where the email came from when the recepient received
        $mail->AddAddress($to);
        $mail->CharSet = 'UTF-8';
        $mail->WordWrap = 50; // set word wrap
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = $subject;
        $mail->Body = $content; //HTML Body

        $mail->SMTPDebug = 0;
        if(!$mail->Send())
        {
            return false;
        }
        else
        {
            return true;
        }
    }
}

?>