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
            return $db->fetchObject($result);
        }

        return null;
    }

    public static function getSummonerByName($name)
    {
        $db = new Database();
        $sql = "SELECT * FROM Summoner WHERE name=".$db->escapeString($name);

        $result = $db->query($sql);

        if($db->numRows($result) > 0)
        {
            return $db->fetchObject($result);
        }

        return null;
    }


    public static function createNewSummoner($data) //updates summoner, if summoner doesn't exist create one
    {
        $db = new Database();

        $sql = "insert into Summoner values(
                  ".$db->escapeString($data['profileIconId']).",
                  '".$db->escapeString($data['name'])."',
                  '".$db->escapeString($data['puuid'])."',
                  ".$db->escapeString($data['summonerLevel']).",
                  ".$db->escapeString($data['revisionDate']).",
                  '".$db->escapeString($data['id'])."',
                  '".$db->escapeString($data['accountId'])."')
                  on duplicate key update Summoner set 
                  name='".$db->escapeString($data['name'])."',
                  proficeIconId=".$db->escapeString($data['profileIconId']).",
                  summonerLevel = ".$db->escapeString($data['summonerLevel']).",
                  revisionDate = ".$db->escapeString($data['revisionDate'])." 
                  where accountId = '".$db->escapeString($data['accountId'])."'";
        $db->query($sql);

        return (object) $data; //wüsste nicht für was man das return benötigen könnte
    }

}