<?php
class API_Summoner{


    private $profileIconId;
    private $name;
    private $puuid;
    private $summonerLevel;
    private $revisionDate;
    private $id;
    private $accountid;


    public function __construct($summoner_array){
        $this->profileIconId = $summoner_array['profileIconId'];
        $this->name = $summoner_array['name'];
        $this->puuid = $summoner_array['puuid'];
        $this->summonerLevel = $summoner_array['summonerLevel'];
        $this->revisionDate = $summoner_array['revisionDate'];
        $this->id = $summoner_array['id'];
        $this->accountid = $summoner_array['accountId'];
    }


    /**
     * @return mixed
     */
    public function getProfileIconId()
    {
        return $this->profileIconId;
    }

    /**
     * @param mixed $profileIoconId
     */
    public function setProfileIconId($profileIconId)
    {
        $this->profileIconId = $profileIconId;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getPuuid()
    {
        return $this->puuid;
    }

    /**
     * @param mixed $puuid
     */
    public function setPuuid($puuid)
    {
        $this->puuid = $puuid;
    }

    /**
     * @return mixed
     */
    public function getSummonerLevel()
    {
        return $this->summonerLevel;
    }

    /**
     * @param mixed $summonerLevel
     */
    public function setSummonerLevel($summonerLevel)
    {
        $this->summonerLevel = $summonerLevel;
    }

    /**
     * @return mixed
     */
    public function getRevisionDate()
    {
        return $this->revisionDate;
    }

    /**
     * @param mixed $revisionDate
     */
    public function setRevisionDate($revisionDate)
    {
        $this->revisionDate = $revisionDate;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getAccountid()
    {
        return $this->accountid;
    }

    /**
     * @param mixed $accountid
     */
    public function setAccountid($accountid)
    {
        $this->accountid = $accountid;
    }

}