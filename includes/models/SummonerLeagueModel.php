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
        $db = new Database();

        $sql = "update createSummonerLeague set
                summonerName = '" . $db->escapeString($data['summonerName']) . "',
                hotstreak = " . boolval($data['hotstreak']) . ",
                wins = " . intval($data['wins']) . ",
                veteran= " . boolval($data['veteran']) . ",
                losses = '" . intval($data['losses']) . "',
                rank = '" . $db->escapeString($data['rank']) . "',
                leagueId = '" . $db->escapeString($data['leagueId']) . "',
                inactive = " . boolval($data['inactive']) . ",
                freshBlood = " . boolval($data['freshBlood']) . ",
                tier = '" . $db->escapeString($data['tier']) . "',
                leaguePoints =" . intval($data['leaguePoints']) . "
                where queueType = '" . $db->escapeString($data['queueType']) . "' and summonerId = '" . $db->escapeString($data['summonerId']) . "'
                if @@ROWCOUNT =0 insert into Summoner values(
                '" . $db->escapeString($data['queueType']) . "',
                '" . $db->escapeString($data['summonerName']) . "',
                " . boolval($data['hotstreak']) . ",
                " . intval($data['wins']) . ",
                " . boolval($data['veteran']) . ",
                '" . intval($data['losses']) . "',
                '" . $db->escapeString($data['rank']) . "',
                '" . $db->escapeString($data['leagueId']) . "',
                " . boolval($data['inactive']) . ",
                " . boolval($data['freshBlood']) . ",
                '" . $db->escapeString($data['tier']) . "',
                '" . $db->escapeString($data['summonerId']) . "',
                " . intval($data['leaguePoints']) . "
                )"
        ;

        $db->query($sql);

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