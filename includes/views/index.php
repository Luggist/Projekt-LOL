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
if(isset($_POST['request'])){
    $output = array(
        "state" => false,
        "output" => "Error: Get no keys"
    );
    $request = stripslashes($_POST['request']);
    if($request == 'register'){
        $username = stripslashes($_POST['username']);
        $password = stripslashes($_POST['password']);
        $password2 = stripslashes($_POST['password2']);
        if(strlen(trim($username)) == 0 || strlen(trim($password)) == 0 || strlen(trim($password2)) == 0){
            $output['output'] = 'Die Felder dürfen nicht leer sein!';
        } else {
            $db = new PDO('mysql:host=localhost;dbname=game', 'root');
            $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
            $stmt->execute(array($username));
            if($stmt->rowCount() > 0){
                $output['output'] = 'Dieser Benutzer existiert bereits!';
            } else {
                if($password != $password2){
                    $output['output'] = 'Die Passwörter sind nicht identisch!';
                } else {
                    $stmt = $db->prepare('INSERT INTO user (username, password) VALUES (?, ?)');
                    $stmt->execute(array($username, md5($password)));
                    $output['state'] = true;
                    $output['output'] = 'Erfolgreich registriert! Melde dich nun an!';
                }
            }
        }
    } else if($request == 'login'){
        $username = stripslashes($_POST['username']);
        $password = stripslashes($_POST['password']);
        if(strlen(trim($username)) == 0 || strlen(trim($password)) == 0){
            $output['output'] = 'Die Felder dürfen nicht leer sein!';
        } else {
            $db = new PDO('mysql:host=localhost;dbname=game', 'root');
            $stmt = $db->prepare('SELECT * FROM user WHERE username = ?');
            $stmt->execute(array($username));
            if($stmt->rowCount() < 0){
                $output['output'] = 'Dieser Benutzer existiert nicht!';
            } else {
                $row = $stmt->fetch(PDO::FETCH_ASSOC);
                if(md5($password) == $row['password']){
                    $output['state'] = true;
                } else {
                    $output['output'] = 'Benutzername oder Passwort ist falsch!';
                }
            }
        }
    }
    header('Content-type: application/json');
    echo json_encode($output);
    exit;
}
if(isset($_POST['apirequest'])) {
    $apiRequest = stripslashes($_POST['apirequest']);
    if ($apiRequest == 'summoner') {
        $apiKey = 'RGAPI-f4adc812-8386-487d-a0cd-d4df3e67b9b4';
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
    <link href="scss/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <style>


    </style>
</head>
<body>

<main role="main" class="fluid-container">
    <div class="header-img">
        <div class="overlay">
            <input id="summonerInput" class="form-control rounded-0" placeholder="Summonername" autofocus>

            <div class="hr-gold"><h3>OR</h3></div>
            <button id="searchBtn" class="c-btn btn-block rounded-0 d-none" onclick="searchSummoner()">S E A R C H</button>
            <div id="loginRegister">
                <input class="form-control" type="hidden">
                <input id="username" class="form-control d-none" type="text" placeholder="Benutzername">
                <input id="password" class="form-control d-none" type="password" placeholder="Passwort">
                <button id="loginBtn" class="c-btn btn-block rounded-0" onclick="login(this)">L O G I N</button><br><br>
                <input id="usernameR" class="form-control d-none" type="text" placeholder="Benutzername">
                <input id="passwordR" class="form-control d-none" type="password" placeholder="Passwort">
                <input id="passwordR2" class="form-control d-none" type="password" placeholder="Passwort wiederholen">
                <button id="registerBtn" class="c-btn btn-block rounded-0" onclick="register(this)">R E G I S T E R</button>
                <div id="response" class="error"></div>
            </div>
        </div>
    </div>
    <div id="stats">
    </div>
    <footer>
        WEB-Projekt<br> &copy; Julian Loferer | Matthias Oberleitner | Lukas Stuefer
    </footer>

</main><!-- /.container -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script>
    $('#summonerInput').on('keyup', function (e) {
        if(e.keyCode == 13){
            searchSummoner();
        }
    });

    function searchSummoner(){

        var summoner = $('#summonerInput').val().toLowerCase();
        $.ajax({
            url: "index",
            method: "POST",
            data: {
                apirequest: "summoner",
                summoner: summoner
            },
            beforeSend: function(){
                $('#summonerInput').prop('disabled', 'disabled');
                $('#summonerInput').val('Lädt...');
            },
            success: function(data){
                $('.header-img .overlay').css('left', '0');
                $('.header-img .overlay').css('flex-direction', 'row');
                $('.header-img .overlay').css('margin-left', '0');
                $('.header-img .overlay').css('height', '80px');
                $('.header-img .overlay').css('width', '100vw');
                $('.header-img .overlay').css('top', '302px');
                $('.header-img .overlay').css('border', '0');
                $('.header-img .overlay').css('justify-content', 'space-between');
                $('.header-img .overlay').css('border-bottom', '2px solid #c7b184');
                $('.header-img .overlay input').css('height', '50px');
                $('.header-img .overlay input').css('margin-bottom', '0');
                $('.header-img .overlay input').css('max-width', '25%');
                $('.header-img .overlay input').css('float', 'left');
                $('.header-img .overlay input').css('margin-left', '100px');
                $('#loginRegister').css('display', 'flex');
                $('#loginRegister').css('flex-direction', 'row');
                $('.c-btn').css('width', '25%');
                $('.c-btn').css('height', '50px');
                $('#searchBtn').removeClass('d-none');
                $('.hr-gold').addClass('d-none');
                $('#stats').html('');
                $('#stats').html(data);
                $('#summonerInput').prop('disabled', '');
                $('#summonerInput').val(summoner);
            }
        });
    }

    $('#password').on('keyup', function (event) {
        if(event.keyCode == 13){
            login('#loginBtn');
        }
    });
    $('#passwordR2').on('keyup', function (event) {
        if(event.keyCode == 13){
            register('#registerBtn');
        }
    });
    function login(ele){
        let userEle = $('#username');
        let pwEle = $('#password');
        let response = $('#response');
        let element = $(ele);
        let userEleR = $('#usernameR');
        let pwEleR = $('#passwordR');
        let pwEleR2 = $('#passwordR2');
        if(userEle.hasClass('d-none')){
            userEle.removeClass('d-none');
            pwEle.removeClass('d-none');
            if(!userEleR.hasClass('d-none')){
                userEleR.addClass('d-none');
                pwEleR.addClass('d-none');
                pwEleR2.addClass('d-none');
            }
        } else {
            let username = userEle.val();
            let password = pwEle.val();
            $.ajax({
                url: "index",
                method: "POST",
                data: {
                    request: "login",
                    username: username,
                    password: password
                },
                beforeSend: function () {
                    element.html('<i class="fa fa-spinner fa-spin"></i> Überprüfe...');
                    element.prop('disabled', 'disabled');
                },
                success: function (data) {
                    if (!data.state) {
                        response.removeClass('error');
                        response.removeClass('success');
                        response.addClass('error');
                        response.css('display', 'block');
                        response.html(data.output);
                        element.html('L O G I N');
                        element.prop('disabled', '');
                        setTimeout(function () {
                            response.css('display', 'none');
                        }, 2000);
                    } else {
                        window.location = '/lolstats/dashboard';
                    }
                }
            });
        }
    }
    function register(ele){
        let userEle = $('#usernameR');
        let pwEle = $('#passwordR');
        let pw2Ele = $('#passwordR2');
        let userEleL = $('#username');
        let pwEleL = $('#password');
        if(userEle.hasClass('d-none')){
            userEle.removeClass('d-none');
            pwEle.removeClass('d-none');
            pw2Ele.removeClass('d-none');
            if(!userEleL.hasClass('d-none')){
                userEleL.addClass('d-none');
                pwEleL.addClass('d-none');
            }
        } else {
            let username = userEle.val();
            let password = pwEle.val();
            let password2 = pw2Ele.val();
            let response = $('#response');
            let element = $(ele);
            $.ajax({
                url: "index",
                method: "POST",
                data: {
                    request: "register",
                    username: username,
                    password: password,
                    password2: password2
                },
                beforeSend: function(){
                    element.html('<i class="fa fa-spinner fa-spin"></i> Überprüfe...');
                    element.prop('disabled', 'disabled');
                },
                success: function (data) {
                    if(!data.state){
                        response.removeClass('error');
                        response.removeClass('success');
                        response.addClass('error');
                        response.html(data.output);
                        response.css('display', 'block');
                        element.html('REGISTER');
                        element.prop('disabled', '');
                        setTimeout(function () {
                            response.css('display', 'none');
                        }, 2000);

                    } else {
                        response.removeClass('error');
                        response.removeClass('success');
                        response.addClass('success');
                        response.html(data.output);
                        response.css('display', 'block');
                        element.html('REGISTER');
                        element.prop('disabled', '');
                        setTimeout(function () {
                            response.css('display', 'none');
                        }, 2000);
                    }
                }
            });
        }
    }
</script>
</html>
