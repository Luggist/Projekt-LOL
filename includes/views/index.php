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
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.css" rel="stylesheet" type="text/css"/>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.css" rel="stylesheet" type="text/css"/>
    <link href="scss/stylesheets/screen.css" media="screen, projection" rel="stylesheet" type="text/css" />
    <style>


    </style>
</head>
<body>

<main role="main" class="fluid-container">
    <div class="header-img">
        <div class="overlay">
            <input id="summonerInput" class="form-control rounded-0" placeholder="Summonername" autofocus>

            <!--<div class="hr-gold"><h3>OR</h3></div>-->
            <button id="searchBtn" class="c-btn btn-block rounded-0 pb-2" onclick="searchSummoner()">S E A R C H</button><br><br>
            <div class="hr-gold"><h3>OR</h3></div>
            <button id="loginBtn" class="c-btn btn-block rounded-0" onclick="goToLoginPage()">L O G I N - R E G I S T E R</button><br><br>
            <!--<div id="loginRegister">
                <input class="form-control" type="hidden">
                <input id="username" class="form-control d-none" type="text" placeholder="Benutzername">
                <input id="password" class="form-control d-none" type="password" placeholder="Passwort">
                <button id="loginBtn" class="c-btn btn-block rounded-0" onclick="login(this)">L O G I N</button><br><br>
                <input id="usernameR" class="form-control d-none" type="text" placeholder="Benutzername">
                <input id="passwordR" class="form-control d-none" type="password" placeholder="Passwort">
                <input id="passwordR2" class="form-control d-none" type="password" placeholder="Passwort wiederholen">
                <button id="registerBtn" class="c-btn btn-block rounded-0" onclick="register(this)">R E G I S T E R</button>
                <div id="response" class="error"></div>
            </div>-->
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.js"></script>
<script>
    $('#summonerInput').on('keyup', function (e) {
        if(e.keyCode == 13){
            searchSummoner();
        }
    });

    function goToLoginPage() {
        window.location = '/lolstats/login';
    }

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
                $('#searchBtn').prop('disabled', 'disabled');
                $('#summonerInput').val('Lädt...');
            },
            success: function(data){
                if(data.state != undefined && data.state == "error"){
                    new Noty({
                        text: data.output,
                        type: data.state,
                        theme: 'relax',
                        timeout: 2000,
                        animation: {
                            open: 'animated fadeInDown', // Animate.css class names
                            close: 'animated fadeOutUp' // Animate.css class names
                        }
                    }).show();
                    $('#summonerInput').prop('disabled', '');
                    $('#searchBtn').prop('disabled', '');
                    $('#summonerInput').val(summoner);
                } else {
                    // Setze Css Parameter, für dynamische Layoutänderungen
                    $('body').css('height', '1400px');
                    $('.header-img .overlay').css('left', '0');
                    $('.header-img .overlay').css('flex-direction', 'row');
                    $('.header-img .overlay').css('margin-left', '0');
                    $('.header-img .overlay').css('height', '80px');
                    $('.header-img .overlay').css('width', '100%');
                    $('.header-img .overlay').css('top', '302px');
                    $('.header-img .overlay').css('border', '0');
                    $('.header-img .overlay').css('padding', '5px');
                    $('.header-img .overlay').css('justify-content', 'space-evenly');
                    $('.header-img .overlay input').css('margin-right', '15px');
                    $('.header-img .overlay').css('border-bottom', '2px solid #c7b184');
                    $('.header-img .overlay').css('border-top', '2px solid #c7b184');
                    $('.header-img .overlay input').css('height', '50px');
                    $('.header-img .overlay input').css('margin-bottom', '0');
                    $('.header-img .overlay input').css('max-width', '50%');
                    $('.header-img .overlay input').css('float', 'left');
                    $('.header-img .overlay input').css('margin-left', '10px');
                    $('.header-img .overlay').addClass('btn-line-moblie');
                    $('#loginRegister').css('display', 'flex');
                    $('#loginRegister').css('flex-direction', 'row');
                    $('#loginBtn').css('margin-left', '15px');
                    $('.c-btn').css('width', '25%');
                    $('.c-btn').css('height', '50px');
                    $('#searchBtn').removeClass('d-none');
                    $('#searchBtn').css('font-size', '10pt');
                    $('#loginBtn').css('font-size', '10pt');
                    $('.hr-gold').addClass('d-none');
                    $('#stats').html('');
                    $('#stats').html(data);
                    $('#summonerInput').prop('disabled', '');
                    $('#summonerInput').val(summoner);
                    $('#searchBtn').prop('disabled', '');
                }
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
