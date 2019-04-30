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
        $sql = "select count() from MatchHistory";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchArray($result);
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