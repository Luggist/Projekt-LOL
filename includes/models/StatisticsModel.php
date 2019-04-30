<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 30.04.2019
 * Time: 13:20
 */

class StatisticsModel
{
    public static function GetSumChampPlayedBySummonerName($summonerName){
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE id=".intval($summonerName);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchObject($result);
        }

        return null;
    }
    public static function GetSumRolePlayedBySummonerName(){

    }
    public static function GetChampionWinRateBySummoner(){

    }
    public static function GetChampionWinRateBySummonerAndChampion(){

    }

}