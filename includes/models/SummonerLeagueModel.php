<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 30.04.2019
 * Time: 09:23
 */

class SummonerLeagueModel
{
    public static function createSummonerLeague($data)
    {
        if($data == null) return; // checks if summoner is not ranked
        $hotStreak = ($data["hotStreak"] ? 1 : 0);
        $veteran = ($data["veteran"] ? 1 : 0);
        $inactive = ($data["inactive"] ? 1 : 0);
        $freshBlood = ($data["freshBlood"] ? 1 : 0);
        $db = new Database();
        $sql = "select * from SummonerLeague where queueType = '" . $db->escapeString($data['queueType']) . "' and summonerId = '" . $db->escapeString($data['summonerId']) . "'";
        $sqlUpdate = "update SummonerLeague set
                summonerName = '" . $db->escapeString($data['summonerName']) . "',
                hotStreak = " . $hotStreak . ",
                wins = " . intval($data['wins']) . ",
                veteran= " . $veteran . ",
                losses = " . intval($data['losses']) . ",
                rank = '" . $db->escapeString($data['rank']) . "',
                leagueId = '" . $db->escapeString($data['leagueId']) . "',
                inactive = " . $inactive . ",
                freshBlood = " . $freshBlood . ",
                tier = '" . $db->escapeString($data['tier']) . "',
                leaguePoints =" . intval($data['leaguePoints']) . "
                where queueType = '" . $db->escapeString($data['queueType']) . "' and summonerId = '" . $db->escapeString($data['summonerId']) . "'";
        $sqlInsert = "insert into SummonerLeague values(
                '" . $db->escapeString($data['queueType']) . "',
                '" . $db->escapeString($data['summonerName']) . "',
                " . $hotStreak . ",
                " . intval($data['wins']) . ",
                " . $veteran . ",
                " . intval($data['losses']) . ",
                '" . $db->escapeString($data['rank']) . "',
                '" . $db->escapeString($data['leagueId']) . "',
                " . $inactive . ",
                " . $freshBlood . ",
                '" . $db->escapeString($data['tier']) . "',
                '" . $db->escapeString($data['summonerId']) . "',
                " . intval($data['leaguePoints']) . "
                )"
        ;

        $result = $db->query($sql);
        if($db->numRows($result) > 0){
            $db->query($sqlUpdate);
        } else {
            $db->query($sqlInsert);
        }
    }

    public static function getSummonerLeagueByName($summonerName)
    {
        $db = new Database();
        $sql = "SELECT * FROM SummonerLeague WHERE summonerName='" . $db->escapeString($summonerName) ."'";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchAssoc($result);
        }

        return null;
    }

    public static function getSummonerLeagueByNameAndQueueType($summonerName,$queueType)
    {
        $db = new Database();
        $sql = "SELECT * FROM SummonerLeague WHERE summonerName='" . $db->escapeString($summonerName) ."' and queueType ='" . $db->escapeString($queueType) . "'";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchAssoc($result);
        }

        return null;
    }

    //Miniseries fehlt noch wird aber nicht benötigt
}