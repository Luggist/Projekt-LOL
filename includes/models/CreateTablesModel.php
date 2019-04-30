<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 29.04.2019
 * Time: 20:47
 */

class CreateTablesModel
{
    public static function createTables(){
        $db = new Database();
        $sql = "create table Summoner(profileIconId int,name varchar(50) Primary Key,puuid varchar(78), summonerLevel bigint,revisionDate bigint, id varchar(63), accountId varchar(56))";
        $db->query($sql);
        $sql = "create table MatchHistory(lane varchar(20), gameId bigint Primary Key,champion int,platformId varchar(30), season int, queue int, role varchar(30), timestamp bigint, accountId varchar(56))";
        $db->query($sql);
        $sql = "create table SummonerLeague(queueType varchar(30), summonerName varchar(100) Primary Key, hotStreak boolean, wins int, veteran boolean, losses int, rank varchar(30), leagueId varchar(40), inactive boolean, freshBlood boolean, tier varchar(10), summonerId varchar(63),leaguePoints int)";
        $db->query($sql);
    }
}