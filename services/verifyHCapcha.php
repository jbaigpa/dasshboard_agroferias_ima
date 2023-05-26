<?php

$data = array(
            'secret' => "0x7ff063F31FBF650f5778a132B70Eb10E04f79750",
            'response' => $_POST['hcaptcharesponse']
        );$verify = curl_init();
curl_setopt($verify, CURLOPT_URL, "https://hcaptcha.com/siteverify");
curl_setopt($verify, CURLOPT_POST, true);
curl_setopt($verify, CURLOPT_POSTFIELDS, http_build_query($data));
curl_setopt($verify, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($verify);// var_dump($response);
$responseData = json_decode($response);
if($responseData->success) {
    // your success code goes here
} 
else {
   exit("ERROR_CAPCHA");

}

?>