<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 29.04.2019
 * Time: 18:12
 */

class SummonerModel
{

    public static function getSummonerById($id)
    {
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE id=".$db->escapeString($id);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchArray($result);
        }

        return null;
    }

    public static function getSummonerByName($name)
    {
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE summonerName=".$db->escapeString($name);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchArray($result);
        }

        return null;
    }


    public static function createNewSummoner($data) //updates summoner, if summoner doesn't exist create one
    {
        $db = new Database();

        $sql = " insert into Summoner values(
                  ".intval($data['profileIconId']).",
                  '".$db->escapeString($data['name'])."',
                  '".$db->escapeString($data['puuid'])."',
                  ".$db->intval($data['summonerLevel']).",
                  ".$db->intval($data['revisionDate']).",
                  '".$db->escapeString($data['id'])."',
                  '".$db->escapeString($data['accountId'])."')
                  on duplicate key update Summoner
                  summonerName='".$db->escapeString($data['name'])."',
                  profileIconId=".intval($data['profileIconId']).",
                  summonerLevel = ".intval($data['summonerLevel']).",
                  revisionDate = ".intval($data['revisionDate']);

        $db->query($sql);

    }

}