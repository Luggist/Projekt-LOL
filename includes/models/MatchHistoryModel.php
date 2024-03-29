<?php

class MatchHistoryModel{

    public static function getMatchHistoryByAccountId($accountId)
    {
        $db = new Database();
        $sql = "SELECT * FROM MatchHistory WHERE accountId='".$db->escapeString($accountId). "'";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {

            $resultArray = array();

            while($row = $db->fetchAssoc($result))
            {
                $resultArray[] = $row;
            }
            return $resultArray;
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
                '".intval($data['champion'])."',
                '".$db->escapeString($data['platformId'])."',
                '".intval($data['season'])."',
                '".intval($data['queue'])."',
                '".$db->escapeString($data['role'])."',
                '".$db->escapeString($data['timestamp'])."',
                '".$db->escapeString($accountId)."')";
        $db->query($sql);

    return false;
}

}