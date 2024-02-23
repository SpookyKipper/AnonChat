
<!doctype html>

<?php

$conf_json = json_decode(file_get_contents("./config.json",1),1);

$iconlink = $conf_json["icon_link"];



$db_name = $conf_json["db_name"];

$db_user = $conf_json["db_user"];

$db_pass = $conf_json["db_pass"];

$db_host = $conf_json["db_host"];



?>
<!--

* Tabler - Premium and Open Source dashboard template with responsive and high quality UI.

* @version 1.0.0-beta19

* @link https://tabler.io

* Copyright 2018-2023 The Tabler Authors

* Copyright 2018-2023 codecalm.net Paweł Kuna

* Licensed under MIT (https://github.com/tabler/tabler/blob/master/LICENSE)

-->

<html lang="en">



<head>

  <meta charset="utf-8" />

  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

  <meta http-equiv="X-UA-Compatible" content="ie=edge" />

  <link rel="icon" href="<?=$iconlink?>" type="image/png">

  <title>Submitting Form</title>

  <!-- CSS files -->

  <link href="./dist/css/tabler.min.css?1684106062" rel="stylesheet" />

  <style>

    @import url('https://rsms.me/inter/inter.css');



    :root {

      --tblr-font-sans-serif: 'Inter Var', -apple-system, BlinkMacSystemFont, San Francisco, Segoe UI, Roboto, Helvetica Neue, sans-serif;

    }



    body {

      font-feature-settings: "cv03", "cv04", "cv11";

    }

  </style>

</head>


<body class=" d-flex flex-column">

  <div class="page page-center">

    <div class="container container-slim py-4">

      <div class="text-center">

        <div class="mb-3">

          <a href="." class="navbar-brand navbar-brand-autodark"><img src="<?=$iconlink?>"

              height="36" alt=""></a>

        </div>

        <div class="text-muted mb-3">Submitting Form</div>

        <div class="progress progress-sm">

          <div class="progress-bar progress-bar-indeterminate bg-indigo"></div>

        </div>

      </div>

    </div>

  </div>

  <!-- Libs JS -->

  <!-- Tabler Core -->

  <script src="./dist/js/tabler.min.js?1684106062" defer></script>







  <?php

  // String Generator \,\

  $permitted_chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

  function generate_string($input, $strength = 8)

  {

    $input_length = strlen($input);

    $random_string = '';

    for ($i = 0; $i < $strength; $i++) {

      $random_character = $input[mt_rand(0, $input_length - 1)];

      $random_string .= $random_character;

    }

    return $random_string;

  }





  // Actual Code \\

  $nickname = base64_encode(urlencode($_POST['nickname']));

  $message = base64_encode(urlencode($_POST['message']));

  $type = strtolower(urlencode($_POST['src'])); 

  if ($type != "instagram") {

      die("Unexpected Client Behaviour: Invalid Type");

  }

  $id = generate_string($permitted_chars, 8);

  $date = time(); // get current timestamp



  $conn = new mysqli($db_host, $db_user, $db_pass,  $db_name);



  // Check connection

  if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

  }

  $checkidsql = "SELECT * FROM conversations WHERE id = '$id'";

$result = $conn->query($checkidsql);



if($result->num_rows == 0){

  $sql = "INSERT INTO conversations (id, type, nickname, message, replies, date) VALUES ('$id', '$type', '$nickname', '$message','{}', '$date')";



  if ($conn->query($sql) === TRUE) {

    echo "<script>window.location.href='/view/?cid=$id'</script>";

  } else {

    echo "<script>window.alert('An error occurred, please try again later. (Error Code: SCS_SQL_1)');</script>";



  }



  $conn->close();





} else {

  echo "<script>window.alert('An error occurred, please try again. (Error Code: SCS_ID_DUP)');</script>";

}







  







  ?>



</body>



</html>