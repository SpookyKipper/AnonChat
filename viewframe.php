<!doctype html>

<!--

  * Tabler - Premium and Open Source dashboard template with responsive and high quality UI.

  * @version 1.0.0-beta20

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

  <title>View Conversation -

    <?= $cid ?>

  </title>

  <!-- CSS files -->

  <link href="/dist/css/tabler.min.css?1692870487" rel="stylesheet" />



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



<body>
  <?php



$conf_json = json_decode(file_get_contents("./config.json", 1), 1);

$iconlink = $conf_json["icon_link"];

$usericonlink = $conf_json["user_icon_link"];

$staffname = $conf_json["nickname"];



$db_name = $conf_json["db_name"];

$db_user = $conf_json["db_user"];

$db_pass = $conf_json["db_pass"];

$db_host = $conf_json["db_host"];





function clean($string)
{

  $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.



  return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.

}

$cid = clean($_GET['cid']);

$sql = "SELECT * FROM `conversations` WHERE id = '$cid'";

$conn = new mysqli($db_host, $db_user, $db_pass, $db_name);

if ($conn->connect_error) {

  die("Connection failed: " . $conn->connect_error);

}

$result = $conn->query($sql);

if ($result->num_rows == 1) {

  $array = $result->fetch_array();

  $nickname = base64_decode($array["nickname"]);

  $message = base64_decode($array["message"]);

  $replies = json_decode($array["replies"], 1);

  $date = $array["date"];

} else {

  die("An error occurred. Error Code: SCS_VIEW_ERR");

}







?>

<div class="chat m-3 pt-3 pb-3 mt-0">

                    <div class="chat-bubbles">





                      <div class="chat-item">

                        <div class="row align-items-end justify-content-end">

                          <div class="col col-lg-6">

                            <div class="chat-bubble chat-bubble-me">

                              <div class="chat-bubble-title">

                                <div class="row">

                                  <div class="col chat-bubble-author ">You <span class="notranslate">(<?= $nickname ?>)

                                    </span>

                                  </div>

                                  <div class="col-auto chat-bubble-date">

                                    <?= gmdate("Y-m-d", $timestamp) ?>

                                  </div>

                                </div>

                              </div>

                              <div class="chat-bubble-body">

                                <p>

                                  <?= $message ?>

                                </p>

                              </div>

                            </div>

                          </div>

                          <div class="col-auto"><span class="avatar"

                              style="background-image: url(<?= $usericonlink ?>)"></span>

                          </div>

                        </div>

                      </div>





                      <?php


                      $usr_msgbox = file_get_contents("./templates/user_msgbox.html", 1);

                      $usr_msgbox = str_replace("[NICKNAME_PLACEHOLDER]", $nickname, $usr_msgbox);
                      $usr_msgbox = str_replace("[AVATAR_PLACEHOLDER]", $usericonlink, $usr_msgbox);


                      $staff_msgbox = file_get_contents("./templates/staff_msgbox.html", 1);
                      
                      $staff_msgbox = str_replace("[NICKNAME_PLACEHOLDER]", $staffname, $staff_msgbox);
                      $staff_msgbox = str_replace("[AVATAR_PLACEHOLDER]", $iconlink, $staff_msgbox);



                      foreach ($replies as $reply) {

                        if ($reply['usr'] == "staff") {

                          $msgbox = $staff_msgbox;

                        } else {

                          $msgbox = $usr_msgbox;

                        }

                        $msgbox = str_replace("[REPLY_PLACEHOLDER]", base64_decode($reply['msg']), $msgbox);

                        $msgbox = str_replace("[DATE_PLACEHOLDER]", gmdate("Y-m-d", $reply['date']), $msgbox);

                        echo $msgbox;



                      }

                      $conn->close();

                      ?>



















                    </div>

                  </div>
                    <!-- Libs JS -->

  <!-- Tabler Core -->

  <script src="/dist/js/tabler.min.js?1692870487" defer></script>

  <script async defer>
const params = new URLSearchParams(window.location.search)  
  const compat = params.get("compat");
    if(compat == "admin") {
      document.querySelectorAll("body > div.chat.m-3.pb-3 > div > div > div > div.col.col-lg-6 > div > div.chat-bubble-title > div > div.col.chat-bubble-author").forEach(element => {
        element.innerHTML = element.innerHTML.replace("You", "User");
      });
    }
  document.body.style.backgroundColor = "white";
  window.scrollTo(0, document.body.scrollHeight);
  // Refresh the page every 15 seconds
window.setTimeout(function() {
  // Reload the page from the server
  location.reload(true);
}, 60000); // 15000 milliseconds = 15 seconds

  </script>

<!-- GT Translate Widget -->

<div class="gtranslate_wrapper d-none"></div>

<script>window.gtranslateSettings = { "default_language": "en", "native_language_names": true, "wrapper_selector": ".gtranslate_wrapper" }</script>

<script src="https://cdn.gtranslate.net/widgets/latest/popup.js" defer></script>


</body>



</html>