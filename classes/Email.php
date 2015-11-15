<?php

class Email
{
    static function sendMail($to, $to_name, $subject, $content_html, $content_plain = '')
    {
        $smtp_op = self::getMailOptions();
        $mail = new PHPMailer();

        $mail->IsSMTP();
        $mail->SMTPAuth = true;
        $mail->SMTPSecure = "tls";
        $mail->Host = $smtp_op['smtp_server'];
        $mail->Port = $smtp_op['smtp_port'];
        $mail->Username = $smtp_op['smtp_username'];
        $mail->Password = $smtp_op['smtp_password'];

        if($content_html != '')
        {
            $mail->isHTML(true);
        }

        $mail->setFrom($smtp_op['smtp_mail_from'], $smtp_op['smtp_mail_from_name']);
        $mail->addAddress($to, $to_name);

        $mail->Subject = $subject;
        $mail->Body    = $content_html;
        $mail->AltBody = $content_plain;

        if($mail->send())
        {
            return true;
        }
        else
        {
            return false; // use $mail->ErrorInfo; for errors
        }
    }

    // returns an associative array of smtp_* and value
    static function getMailOptions()
    {
        $conn = MySQL::open_conn();
        $query = "SELECT * FROM c_options WHERE option_name LIKE 'smtp%'";
        $res = $conn->query($query);
        dbQueryCheck($res, $conn);
        $smtp_op = [];
        while($row = $res->fetch_assoc())
        {
            $smtp_op[$row['option_name']] = $row['option_value'];
        }
        return $smtp_op;
    }
}