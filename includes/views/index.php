<?php

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    if(curl_errno($curl))
    {
        echo 'cURL-Fehler: ' . curl_error($curl);
    }
    curl_close($curl);

    return $result;
}
if(isset($_POST['apirequest'])) {
    $apiRequest = stripslashes($_POST['apirequest']);
    if ($apiRequest == 'summoner') {
        $apiKey = 'RGAPI-d6eb0f20-247c-463c-80b1-7da93fd9c8d3';
        $summonerClean = $_POST['summoner'];
        $summoner = str_replace(' ', '%20', $summonerClean);
        /*$api = new classes\ExternAPI();
        $arr = $api->call('summoner/v4/summoners/by-name/' . $summoner);*/
        $url = 'https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summoner . '?api_key=' . $apiKey;
        $resp = CallAPI('GET', $url);
        if(strlen($resp) == 0){
            $resp = file_get_contents($url);
        }
        $arr = json_decode($resp, true);
        /*
        $champMastery = $api->call('champion-mastery/v4/champion-masteries/by-summoner/' . $arr["id"]);*/

        $resp = CallAPI('GET', 'https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/' . $arr["id"] . '?api_key=' . $apiKey);
        $champMastery = json_decode($resp, true);
        $champData = json_decode(file_get_contents('http://ddragon.leagueoflegends.com/cdn/6.24.1/data/de_DE/champion.json'), true);
        $champMasteryName = '';
        foreach ($champData["data"] as $champ) {
            if ($champ["key"] == $champMastery[0]["championId"]) {
                $champMasteryName = $champ["id"];
                break;
            }
        }

        $levelPic = 'img/';
        if($arr["summonerLevel"] < 30){
            $levelPic .= 'level29.png';
        } else if($arr["summonerLevel"] < 50){
            $levelPic .= 'level49.png';
        } else if($arr["summonerLevel"] < 75){
            $levelPic .= 'level74.png';
        } else if($arr["summonerLevel"] < 100){
            $levelPic .= 'level99.png';
        } else if($arr["summonerLevel"] < 125){
            $levelPic .= 'level124.png';
        } else if($arr["summonerLevel"] < 150){
            $levelPic .= 'level149.png';
        } else if($arr["summonerLevel"] < 175){
            $levelPic .= 'level174.png';
        } else {
            $levelPic .= 'level175.png';
        }
        //header('Content-type: application/json');
        header("Access-Control-Allow-Origin: *");
        echo '<div class="row profile">
                <div class="col-md-3" style="background-image: url(http://ddragon.leagueoflegends.com/cdn/img/champion/loading/' . $champMasteryName . '_0.jpg); background-repeat: no-repeat; background-size: cover; height:459px; width:100%;">
                    <div class="dark-overlay"></div>
                    <div class="profile-sidebar">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img class="level-border" src="' . $levelPic . '"/>
                            <img class="pic" src="http://avatar.leagueoflegends.com/euw/' . $summoner . '.png" class="img-responsive" alt="">
                        </div>
                        <!-- END SIDEBAR USERPIC -->
                        <!-- SIDEBAR USER TITLE -->
                        <div class="profile-usertitle">
                            <div class="profile-usertitle-name">
                                ' . $arr["name"] . '
                            </div>
                            <div class="profile-usertitle-level">
                                ' . $arr["summonerLevel"] . '
                            </div>
                        </div>
                        <!-- END SIDEBAR USER TITLE -->
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="profile-content">
                       Some user related content goes here...
                    </div>
                </div>
            </div>';
        exit;
    }
}
?>



<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="Julian Loferer, Matthias Oberleitner, Lukas Stuefer">
    <title><?php echo $this->title; ?></title>

    <!-- Bootstrap core CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://fonts.googleapis.com/css?family=Lato" rel="stylesheet">

    <style>
        html {
            font-family: 'Lato', sans-serif;
        }
        .header-img {
            max-width: 100%;
            width: 100%;
            margin-left: auto;
            margin-right: auto;
            padding-left: 10px;
            padding-right: 10px;
            padding-top: 10px;
            height: 300px;
            background-image: url("img/lolheader.jpg");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
        }

        .header-img .overlay {
            position: absolute;
            top: 240px;
            left: 50%;
            width: 700px;
            margin-left: -350px;
            background-color: #1e2328;
            padding: 40px 60px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            z-index: 700;
            transition: top .5s ease-in-out;
        }

        .overlay {
            border: 2px;
            color: #72542a;
            background-clip:padding-box;
            border: solid;
            border-radius: initial;

        :before {
            content: '';
            position: absolute;
            top: 0; right: 0; bottom: 0; left: 0;
            z-index: -1;
            margin: -$border; /* !importantÃ© */
            border-radius: inherit; /* !importantÃ© */
            background: linear-gradient(to right, red, orange);
        }
        }

        .header-img .overlay input {
            margin-bottom: 20px;
            text-align: center;
            height: 70px;
            font-size: 35pt;
            outline: 0 !important;
            border: 3px solid rgba(192,252,253,.7);
            border-image: linear-gradient(to bottom,#08abac 0,#01698b 100%);
            color: #f0e6d2;
            background-color: #1e2328;
            border-image-slice: 1;
            transition: all 0.5s ease;
        }

        .col-md-3 {
            width:450px;
            border: 2px solid #72542a;
            position: relative;
            padding-left: 0;
        }

        .header-img .overlay input:hover {
            box-shadow:0 0 10px 4px rgba(192,252,253,.4),inset 0 0 5px 2px rgba(192,252,253,.3);
            border:3px solid rgba(192,252,253,.7);
        }

        .hr-gold {
            height: 2px;
            width: 100%;
            z-index: 700;
            padding-bottom: 50px;
            overflow: hidden;
            text-align: center;
        }
        .hr-gold h3 {
            display: inline-block;
            position: relative;
            color: #72542a;
        }

        h3::before,
        h3::after {
            content: "";
            position: absolute;
            border-top: 2px solid #c7b184;
            top: 50%;
            width: 2000px;
        }
        h3::before {
            margin-right: 15px;
            right: 100%;
        }
        h3::after {
            margin-left: 15px;
            left: 100%;
        }

        .c-btn {
            display: -ms-inline-flexbox;
            display: inline-flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            vertical-align: middle;
            cursor: pointer;
            height: 3.00em;
            font-size: 15pt;
            max-width: 100%;
            border: 0;
            color: #c7b184;
            fill: currentColor;
            -webkit-box-shadow: 0 0 28px #000;
            box-shadow: 0 0 28px #000;
            line-height: 1;
            font-weight: 500;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
            position: relative;
            z-index: 1;
            -webkit-transition: .15s;
            transition: .15s
        }

        .c-btn:active,
        .c-btn:focus,
        .c-btn:hover {
            color: #fff;
            text-decoration: none;
            -webkit-box-shadow: 0 0 28px #000, 0 0 28px rgba(0, 0, 0, .6);
            box-shadow: 0 0 28px #000, 0 0 28px rgba(0, 0, 0, .6)
        }

        .c-btn:active::after,
        .c-btn:focus::after,
        .c-btn:hover::after {
            background: #1a1d21
        }

        .c-btn::after,
        .c-btn::before {
            content: '';
            display: block;
            position: absolute;
            top: 0;
            right: 0;
            bottom: 0;
            left: 0;
            z-index: -1
        }

        .c-btn::before {
            background: -webkit-gradient(linear, left bottom, left top, from(#72542a), to(#bd9e5e));
            background: linear-gradient(0deg, #72542a 0, #bd9e5e 100%)
        }

        .c-btn::after {
            margin: 1px;
            background: #16181d;
            -webkit-transition: .15s;
            transition: .15s
        }

        /* Profile container */
        .profile {
            position: fixed;
            min-width: 100%;
            z-index: 10;
            top: 300px;
            bottom: 100px;
            border-top: 2px solid #c7b184;
            background-color: #303840;
        }

        /* Profile sidebar */
        .profile-sidebar {
            padding: 20px 0 10px 0;
            z-index: 15;
            position: absolute;
            width: 100%;
            margin: 0 auto;
            max-height: 455px;
        }

        .profile-userpic img.pic {
            position: relative;
            width: 40%;
            left: 50%;
            margin-left: -20%;
            z-index: 102;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
            border: 4px solid #c7b184;
            object-fit: contain;
        }

        .profile-userpic img.level-border {
            position: absolute;
            z-index: 101;
            width: 55%;
            left: 50%;
            margin-left: -27%;
            margin-top: -8%;
        }



        .profile-usertitle {
            text-align: center;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            margin-top: 20px;
        }

        .profile-usertitle-name {
            color: #c7b184;
            font-size: 40px;
            font-weight: 600;
            margin-bottom: 7px;
        }

        .profile-usertitle-level {
            text-transform: uppercase;
            color: #c7b184;
            font-size: 40px;
            font-weight: 600;
            padding-top: 15px;
            border: 4px solid #c7b184;
            background-color: #303840;
            width: 100px;
            height: 100px;
            -webkit-border-radius: 50% !important;
            -moz-border-radius: 50% !important;
            border-radius: 50% !important;
        }

        /* Profile Content */
        .profile-content {
            padding: 20px;
            background: #1e2328;
            min-height: 460px;;
            color: #f0e6d2;
            border: 3px solid rgba(192,252,253,.7);
            border-image: linear-gradient(to bottom,#08abac 0,#01698b 100%);
            border-image-slice: 1;
        }


        .dark-overlay {
            background-color: black;
            opacity: .7;
            width: 100%;
            height: 100%;
            max-height: 459px;
            z-index: 10;
            position: absolute;
        }

        #stats {
            position: absolute;
            color: #f0e6d2;
            border-top: 2px solid #c7b184;
            background-color: #303840;
            width: 100%;
            top: 300px;
            bottom: 100px;
            padding-bottom:100px;
        }

        #stats .row {
            padding-top: 90px;
            padding-left: 50px;
            padding-right: 50px;
        }

        #stats .info {
            padding-top: 250px;
        }

        footer {
            position: absolute;
            bottom: 0;
            height: 100px;
            width: 100%;
            background: #1e2328;
            text-align: center;
            color: #f0e6d2;
            display: flex;
            justify-content: center;
            align-items: center;
            border-top: 2px solid #c7b184;
        }

        .col {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .col-md-3 {
            padding-left: 0;
        }

        .col i {
            font-size: 50pt;
            font-weight: bold;
        }

        .row {
            margin-right: 0 !important;
        }

    </style>
</head>
<body>

<main role="main" class="fluid-container">
    <div class="header-img">
        <div class="overlay">
            <input id="summonerInput" class="form-control rounded-0" placeholder="Summonername" autofocus>
            <div class="hr-gold"><h3>OR</h3></div>
            <button class="c-btn btn-block rounded-0">L O G I N</button><br>
            <button class="c-btn btn-block rounded-0">R E G I S T E R</button>
        </div>
    </div>
    <div id="stats">
    </div>
    <footer>
        WEB-Projekt<br> Â© Julian Loferer | Matthias Oberleitner | Lukas Stuefer
    </footer>

</main><!-- /.container -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $('#summonerInput').on('keyup', function (e) {
        if(e.keyCode == 13){
            var summoner = $('#summonerInput').val();
            $.ajax({
                url: "index",
                method: "POST",
                data: {
                    apirequest: "summoner",
                    summoner: summoner
                },
                beforeSend: function(){
                    $('#summonerInput').prop('disabled', 'disabled');
                    $('#summonerInput').val('LÃ¤dt...');
                },
                success: function(data){
                    $('.header-img .overlay').css('top', '100px');
                    $('#stats').html('');
                    $('#stats').html(data);
                    $('#summonerInput').prop('disabled', '');
                    $('#summonerInput').val(summoner);
                }
            });
        }
    })
</script>
</html>
