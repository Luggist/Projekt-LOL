<?php

class MatchHistoryModel{

    public static function getMatchHistoryByAccountId($accountId)
    {
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE accountId='".$db->escapeString($accountId). "'";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchArray($result);
        }

        return null;
    }

//Aufruf in foreach schleife mit if(createNewMatchHistory($data, $accountId))break;)
    public static function createNewMatchHistory($data, $accountId)
    {
        $db = new Database();
        //check ob Zeile bereits existiert
        $sql = "SELECT * FROM MatchHistory WHERE accountId='".$db->escapeString($accountId). "'"." and gameid = '".$db->escapeString($data['gameId']) . "'";
        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return true;
        }

        $sql = "insert into MatchHistory values(
                '".$db->escapeString($data['lane'])."',
                '".$db->escapeString($data['gameId'])."',
                '".$db->escapeString($data['champion'])."',
                '".$db->escapeString($data['platformId'])."',
                '".$db->escapeString($data['season'])."',
                '".$db->escapeString($data['queue'])."',
                '".$db->escapeString($data['role'])."',
                '".$db->escapeString($data['timestamp'])."',
                '".$db->escapeString($accountId)."')";
        $db->query($sql);

    return false;
}

}