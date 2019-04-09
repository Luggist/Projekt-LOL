<?php


class IndexController extends Controller
{
	protected $viewFileName = "index"; //this will be the View that gets the data...
	protected $loginRequired = false;


	public function run()
	{
		$this->view->title = 'LOL Stats';
		$this->view->api = new ExternAPI('RGAPI-6cb84988-ad78-4a15-9275-308870e4a81a');

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
                $summonerClean = $_POST['summoner'];
                $summoner = str_replace(' ', '%20', $summonerClean);
                $arr = $this->view->api->call('https://euw1.api.riotgames.com/lol/summoner/v4/summoners/by-name/' . $summoner);
                $champMastery = $this->view->api->call('https://euw1.api.riotgames.com/lol/champion-mastery/v4/champion-masteries/by-summoner/' . $arr["id"]);
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
                       <h5 class="pl-2 pt-2">Matchhistory:</h5>
                       <ul class="list-group list-group-flush bg-loldark">
                          <li class="list-group-item"><span class="text-warning">URF</span> <span class="text-muted">5 days ago</span> <span class="text-success">WIN</span> <span><img src="http://ddragon.leagueoflegends.com/cdn/6.24.1/img/champion/Ekko.png" width="16px" height="16px" alt="CHAMPION PLAYED IMAGE"/> ' . $arr["name"] . '</span> <span class="float-right"><img src="http://ddragon.leagueoflegends.com/cdn/6.24.1/img/champion/Ahri.png" width="16px" height="16px" alt="PARTICIPANT CHAMPION IMAGE"/> xZezzyx
                          <img src="http://ddragon.leagueoflegends.com/cdn/6.24.1/img/champion/Annie.png" width="16px" height="16px" alt="PARTICIPANT CHAMPION IMAGE"/> xOnionx
                          <img src="http://ddragon.leagueoflegends.com/cdn/6.24.1/img/champion/Braum.png" width="16px" height="16px" alt="PARTICIPANT CHAMPION IMAGE"/> MingaKoala
                          <img src="http://ddragon.leagueoflegends.com/cdn/6.24.1/img/champion/Olaf.png" width="16px" height="16px" alt="PARTICIPANT CHAMPION IMAGE"/> Lord of Muck</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                          <li class="list-group-item"><span class="text-warning">GameMode</span> <span class="text-muted">TIME</span> <span class="text-success">WIN</span>/<span class="text-danger">LOST</span> <span>CHAMPION PLAYED IMAGE</span> <span class="float-right">PARTICIPANTS</span></li>
                       </ul>
                    </div>
                </div>
            </div>';
                exit;
            }
        }
	}

}