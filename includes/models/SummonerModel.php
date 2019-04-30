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
        $sql = "SELECT * FROM Summoner WHERE id=".intval($id);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchAssoc($result);
        }

        return null;
    }

    public static function getSummonerByName($name)
    {
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE summonerName='".$db->escapeString($name) . "'";

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {

            return $db->fetchAssoc($result);
        }

        return null;
    }


    public static function createNewSummoner($data) //updates summoner, if summoner doesn't exist create one
    {
        $db = new Database();
        $sql = "select * from Summoner where accountId = '" . $db->escapeString($data['accountId'])."'";
        $sqlUpdate = "update Summoner set 
                  profileIconId=".intval($data['profileIconId']).",
                  summonerLevel = ".intval($data['summonerLevel']).",
                  revisionDate = ".intval($data['revisionDate'])." 
                  where accountId = '".$db->escapeString($data['accountId'])."'";
        $sqlInsert = "insert into Summoner values(
                  ".intval($data['profileIconId']).",
                  '".$db->escapeString($data['name'])."',
                  '".$db->escapeString($data['puuid'])."',
                  ".intval($data['summonerLevel']).",
                  ".intval($data['revisionDate']).",
                  '".$db->escapeString($data['id'])."',
                  '".$db->escapeString($data['accountId'])."')";
        $result = $db->query($sql);
        if($db->numRows($result) > 0){
            $db->query($sqlUpdate);
        } else {
            $db->query($sqlInsert);
        }
        /*
        $sql = "insert into Summoner values(
                  ".$db->escapeString($data['profileIconId']).",
                  '".$db->escapeString($data['name'])."',
                  '".$db->escapeString($data['puuid'])."',
                  ".$db->escapeString($data['summonerLevel']).",
                  ".$db->escapeString($data['revisionDate']).",
                  '".$db->escapeString($data['id'])."',
                  '".$db->escapeString($data['accountId'])."')
                  summonerName='".$db->escapeString($data['name'])."',
                  on duplicate key update profileIconId=".intval($data['profileIconId']).",
                  on duplicate key update summonerLevel = ".intval($data['summonerLevel']).",
                  on duplicate key update revisionDate = ".intval($data['revisionDate']);
        $db->query($sql);
        */
    }

}