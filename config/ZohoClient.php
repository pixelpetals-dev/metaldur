<?php
class ZohoClient {
    private $config;
    private $tokenFile; 
    public function __construct($configArray) {
        $this->config = $configArray;
        $this->tokenFile = __DIR__ . '/token_store.json'; 
    }
    private function getAccessToken() {
        if (file_exists($this->tokenFile)) {
            $cached = json_decode(file_get_contents($this->tokenFile), true);
            if (isset($cached['access_token']) && $cached['expires_at'] > time() + 60) {
                return $cached['access_token'];
            }
        }
        return $this->refreshAccessToken();
    }
    private function refreshAccessToken() {
        $url = "https://accounts.zoho." . $this->config['dc_tld'] . "/oauth/v2/token";
        $postData = [
            'refresh_token' => $this->config['refresh_token'],
            'client_id'     => $this->config['client_id'],
            'client_secret' => $this->config['client_secret'],
            'grant_type'    => 'refresh_token'
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            throw new Exception('Zoho OAuth Error: ' . curl_error($ch));
        }
        curl_close($ch);
        $json = json_decode($response, true);
        if (isset($json['error'])) {
            throw new Exception('Zoho API Error (Token): ' . $json['error']);
        }
        $dataToSave = [
            'access_token' => $json['access_token'],
            'expires_at'   => time() + $json['expires_in'] 
        ];
        file_put_contents($this->tokenFile, json_encode($dataToSave));
        return $json['access_token'];
    }
    public function getItems($page = 1) {
        $endpoint = "https://www.zohoapis." . $this->config['dc_tld'] . "/inventory/v1/items";
        $params = [
            'organization_id' => $this->config['org_id'],
            'page'            => $page,
            'status'          => 'active',
        ];
        return $this->makeApiCall($endpoint, $params);
    }
    private function makeApiCall($url, $params = []) {
        $accessToken = $this->getAccessToken();
        $url .= '?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Zoho-oauthtoken ' . $accessToken,
            'Content-Type: application/json'
        ]);
        $response = curl_exec($ch);
        if(curl_errno($ch)){
            throw new Exception('Zoho Request Error: ' . curl_error($ch));
        }
        curl_close($ch);
        return json_decode($response, true);
    }
}
?>