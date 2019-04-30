<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 30.04.2019
 * Time: 16:38
 */

class MatchesModel
{
    public static function createNewMatches($data) //updates summoner, if summoner doesn't exist create one
    {
        $db = new Database();
        $sql = "select * from Matches where gameId = '" . $db->escapeString($data['gameId']) . "'";
        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return true;
        }

        $sql = "insert into Matches values(
                  " . intval($data['seasonId']) . ",
                  " . intval($data['queueId']) . ",
                  " . $data['gameId'] . ",
                  '" . $db->escapeString(json_encode($data['participantIdentities'])) . "',
                  '" . $db->escapeString($data['gameVersion']) . "',
                  '" . $db->escapeString($data['platformId']) . "',
                  '" . $db->escapeString($data['gameMode']) . "',
                  " . intval($data['mapId']) . ",
                  '" . $db->escapeString($data['gameType']) . "',
                  '" . $db->escapeString(json_encode($data['teams'])) . "',
                  '" . $db->escapeString(json_encode($data['participants'])) . "',
                  " . $data['gameDuration'] . ",
                  " . $data['gameCreation'] . "
                  )";
        $db->query($sql);
    }

    public static function getMatchesByGameId($gameId)
    {
        $db = new Database();
        $sql = "SELECT * FROM Matches WHERE gameId=".intval($gameId);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchAssoc($result);
        }

        return null;
    }
}