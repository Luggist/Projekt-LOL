<?php

class ExternAPI {
    private $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }


    public function call($url,  $method = 'GET',  $data = false)
    {
        $curl = curl_init();
        if(strpos($url, '?') !== false){
            $url .= '&api_key=' .  $this->apiKey;
        } else {
            $url .= '?api_key=' .  $this->apiKey;
        }
        switch ($method)
        {
            case "POST":
                curl_setopt($curl, CURLOPT_POST, 1);

                if ($data)
                    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
                break;
            case "PUT":
                curl_setopt($curl, CURLOPT_PUT, 1);
                break;
            default:
                if ($data)
                    $url = sprintf("%s?%s", $url, http_build_query($data));
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

        $result = curl_exec($curl);
        if(curl_errno($curl))
        {
            echo 'cURL-Fehler: ' . curl_error($curl);
        }
        curl_close($curl);

        return json_decode($result, true);
    }
}