
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


<?php

$conf_json = json_decode(file_get_contents(".././config.json",1),1);

$iconlink = $conf_json["icon_link"];



$db_name = $conf_json["db_name"];
$db_user = $conf_json["db_user"];
$db_pass = $conf_json["db_pass"];
$db_host = $conf_json["db_host"];

$admin_cookie = $conf_json["admin_cookie"];
if ($_COOKIE['admin'] !== $admin_cookie) {
    // Send a 403 Forbidden header
    header('HTTP/1.1 403 Forbidden');
    // Display a custom message or error page
    echo 'Unauthorized';
    // Exit the script
    exit();
  }
  

$conn = new mysqli($db_host, $db_user, $db_pass,  $db_name);
$sql = "SELECT * FROM `conversations` ORDER BY `conversations`.`date` DESC";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $conversations = $result->fetch_all();
}
?>
<head>

    <meta charset="utf-8" />

    <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />

    <meta http-equiv="X-UA-Compatible" content="ie=edge" />
    <link rel="icon" href="<?=$iconlink?>" type="image/png">

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


    <div class="page">

        <!-- Navbar -->





        <div class="page-wrapper">



            <!-- Page body -->

            <div class="page-body">

                <div class="container-xl">

                    <div class="card mt-3">
                        <div class="card-header">
                            <h2 class="page-title" id="view_convo_txt">

                                Conversations



                            </h2>
                      
                        </div>

                        <div class="row g-0">

                        <div class="col-12 col-lg-5 col-xl-3 border-end">
                              <script>
                                function setActiveBox(active_e) {
                                    boxes = document.querySelectorAll("#tablist > *")
                                    boxes.forEach(element => {
                                        element.classList.remove("active");
                                    });
                                    active_e.classList.add("active");
                                }
                                function setFrameCid(cid) {
                                    document.getElementById('convo_frame').removeAttribute("srcdoc");
                                    document.getElementById('convo_frame').src='//' + window.location.host + '/viewframe/?compat=admin&cid=' + cid;
                                    document.getElementById('cid_input').value = cid;
                                    document.getElementById('reply_form_div').classList.remove("d-none");
                                }
                                
                                </script>
                                <div class="card-body p-0 scrollable" style="max-height: 35rem">
                                    <div class="nav flex-column nav-pills" role="tablist" id="tablist">


                                    <?php
                                    $template = '<a href="#" class="nav-link text-start mw-100 p-3" role="tab" aria-selected="true" onclick="setActiveBox(this);setFrameCid(\'[CID_PLACEHOLDER]\');">
                                    <div class="row align-items-center flex-fill">
                                   
                                        <div class="col text-body">
                                            <div>[NICKNAME_PLACEHOLDER] - [CID_PLACEHOLDER]</div>
                                            <div class="text-secondary text-truncate w-100">[MESSAGE_PLACEHOLDER]</div>
                                        </div>
                                    </div>
                                </a>
                                ';



                                foreach ($conversations as $conversation) {
                                    if ($conversation[4] == "{}") {
                                        $message = $conversation[3];
                                    } else {
                                        $message = json_decode($conversation[4],1)[0]["msg"];
                                    }
                                    $box = str_replace("[NICKNAME_PLACEHOLDER]",base64_decode($conversation[2]), $template);
                                    $box = str_replace("[CID_PLACEHOLDER]",$conversation[0], $box);
                                    $box = str_replace("[MESSAGE_PLACEHOLDER]",base64_decode($message), $box);
                                    echo $box;
                                }




                                    ?>
                                    
                               
                                        
                                        
                                        
                                        
                                        
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-lg-7 col-xl-9 d-flex flex-column">

                                <div class="card-body scrollable p-0" style="height: 35rem;overflow: hidden;">

                                    <iframe class="w-full h-full " id="convo_frame"
                                        style="background: url(\/loading.gif) no-repeat center;background-size: 50px;" srcdoc="<body style='background-color:white;'><center><h1 style='font-family: Calibri, Arial;'>Please select a conversation from the left</h1></center></body>"></iframe>









                                </div>
                                <script>
                                   //document.getElementById('convo_frame').src = "//" + window.location.host + "/viewframe/?cid=" + cid;
                                </script>

                            </div>

                        </div>

                        <form action="/admin/reply" method="post">



                            <div class="card-footer d-none" id="reply_form_div">

                                <div class="input-group input-group-flat">

                                    <input type="text" class="form-control" placeholder="Type message" name="message"
                                        required />

                                    <input type="hidden" class="form-control" autocomplete="off"
                                        placeholder="Type message" name="cid" value="" id="cid_input" required />

                                    <span class="input-group-text">

                                        <button type="submit" class="link-secondary ms-2 border-0 rounded"
                                            data-bs-toggle="tooltip" aria-label="Send Message" title="Send Message">

                                            <!-- Download SVG icon from http://tabler-icons.io/i/paperclip -->

                                            <svg xmlns="http://www.w3.org/2000/svg"
                                                class="icon icon-tabler icon-tabler-send" width="44" height="44"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none"
                                                stroke-linecap="round" stroke-linejoin="round">

                                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />

                                                <path d="M10 14l11 -11" />

                                                <path
                                                    d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5" />

                                            </svg>

                                            </a>

                                    </span>



                                </div>
                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>

    </div>



    </div>

    </div>

    <!-- Libs JS -->

    <!-- Tabler Core -->

    <script src="/dist/js/tabler.min.js?1692870487" defer></script>
<script>
    const params = new URLSearchParams(window.location.search)  
                                const cid = params.get("cid");
                                if (cid != null) {
                                    setFrameCid(cid);
                                }
                                </script>


    <!-- GT Translate Widget -->

    <div class="gtranslate_wrapper"></div>

    <script>window.gtranslateSettings = { "default_language": "en", "native_language_names": true, "wrapper_selector": ".gtranslate_wrapper" }</script>

    <script src="https://cdn.gtranslate.net/widgets/latest/float.js" defer></script>

    <!-- Copyright Notice, do not remove -->

    <div style="width: 100%;bottom:0;position:fixed;text-align:right;padding-right:1rem" class="text-muted mb-3">

        Powered by AnonConvo: <a href="https://github.com/SpookyKipper/AnonConvo" target="_blank">View Source on GitHub</a></div>

    <!-- End Copyright Notice -->

</body>



</html>