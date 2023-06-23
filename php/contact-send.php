<?php
if(!session_id()) {
	session_start();
}
error_reporting(0);

if (isset($_REQUEST['action'])) {
    if ($_REQUEST['action'] == "contact_form_request") {

        $ourMail = "medpraktic@gmail.com"; //Insert your email address here

        $required_fields = array("name", "phone","email", "date", "subject", "category", "message");
        $pre_messagebody_info = "";
        $subject = "";
        $errors = array();
        $data = array();
        parse_str($_REQUEST['values'], $data);
		
       
        $result = array(
            "is_errors" => 0,
            "info" => ""
        );
        if (!empty($errors)) {
            $result['is_errors'] = 1;
            $result['info'] = $errors;
            echo json_encode($result);
            exit;
        }
		
		$pre_messagebody_info.="<strong>Имя</strong>" . ": " . $data['name'] . "<br />";
        $pre_messagebody_info.="<strong>E-mail</strong>" . ": " . $data['email'] . "<br />";
		
		if (!empty($data["category"])){
			$pre_messagebody_info.="<strong>Category</strong>" . ": " . $data['category'] . "<br />";
		}
		if (!empty($data["phone"])){
			$pre_messagebody_info.="<strong>Телефон</strong>" . ": " . $data['phone'] . "<br />";
		}
		if (!empty($data["date"])){
			$pre_messagebody_info.="<strong>Желаемая дата</strong>" . ": " . $data['date'] . "<br />";
		}
		if (empty($data["subject"])){
			$subject.="Запись на приём";
		}else{
			$subject.= $data["subject"];
		}

        $headers = 'MIME-Version: 1.0' . "\r\n";
        $headers.= 'Content-type: text/html; charset=UTF-8' . "\r\n";
        $headers.= "From: ".$data['email']."\r\n";

        $after_message = "\r\n<br />--------------------------------------------------------------------------------------------------------\r\n<br> Это письмо было отправлено через контактную форму сайта";

        if (mail($ourMail, $subject, $pre_messagebody_info .= "<strong>Сообщение</strong>" . ": " . $data['message'] .$after_message, $headers)) {
            $result["info"] = "success";
        } else {
            $result["info"] = "server_fail";
        }

        echo json_encode($result);
        exit;
    }
}

function check_email_address($email) {
// First, we check that there's one @ symbol,
// and that the lengths are right.
    if (!ereg("^[^@]{1,64}@[^@]{1,255}$", $email)) {
// Email invalid because wrong number of characters
// in one section or wrong number of @ symbols.
        return false;
    }
// Split it into sections to make life easier
    $email_array = explode("@", $email);
    $local_array = explode(".", $email_array[0]);
    for ($i = 0; $i < sizeof($local_array); $i++) {
        if
        (!ereg("^(([A-Za-z0-9!#$%&'*+/=?^_`{|}~-][A-Za-z0-9!#$%&
в†Є'*+/=?^_`{|}~\.-]{0,63})|(\"[^(\\|\")]{0,62}\"))$", $local_array[$i])) {
            return false;
        }
    }
// Check if domain is IP. If not,
// it should be valid domain name
    if (!ereg("^\[?[0-9\.]+\]?$", $email_array[1])) {
        $domain_array = explode(".", $email_array[1]);
        if (sizeof($domain_array) < 2) {
            return false; // Not enough parts to domain
        }
        for ($i = 0; $i < sizeof($domain_array); $i++) {
            if
            (!ereg("^(([A-Za-z0-9][A-Za-z0-9-]{0,61}[A-Za-z0-9])|
в†Є([A-Za-z0-9]+))$", $domain_array[$i])) {
                return false;
            }
        }
    }
    return true;
}
?>

