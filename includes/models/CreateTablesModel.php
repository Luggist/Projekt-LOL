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
        $sql = "create table Summoner(profileIconId int,name varchar(50) Primary Key,puuid varchar(78), summonerLevel bigint,revisionDate bigint, id varchar(63), accountId varchar(56)),
                create table MatchHistory(lane varchar(20), gameId bigint,champion int,platformId varchar, season int, queue int, role varchar, timestamp bigint, accountId varchar(56)),
                create table SummonerLeague(queueType varchar(), summonerName, varchar, hotStreak varchar, wins int, veteran boolean, losses int, rank varchar(), leagueId varchar(), inactive boolean, freshBlood boolean, tier varchar(), summonerId varchar(),leaguePoints int),
                create table MiniSeries(progress varchar(),losses int, target int, wins int)
        ";
        $db->query($sql);
    }
}