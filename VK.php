<?php
namespace BW;

/**
 * The Vkontakte PHP SDK
 *
 * @author Bocharsky Victor, https://github.com/Vastly
 */
class Vkontakte
{
    const VERSION = '5.5';
    /**
     * The application ID
     * @var integer
     */
    private $appId;
    /**
     * The application secret code
     * @var string
     */
    private $secret;
    /**
     * The scope for login URL
     * @var array
     */
    private $scope = array();
    /**
     * The URL to which the user will be redirected
     * @var string
     */
    private $redirect_uri;
    /**
     * The response type of login URL
     * @var string
     */
    private $responceType = 'code';
    /**
     * The current access token
     * @var \StdClass
     */
    private $accessToken;
    /**
     * The Vkontakte instance constructor for quick configuration
     * @param array $config
     */
    public function __construct(array $config)
    {
        if (isset($config['access_token'])) {
            $this->setAccessToken(json_encode(array('access_token' => $config['access_token'])));
        }
        if (isset($config['app_id'])) {
            $this->setAppId($config['app_id']);
        }
        if (isset($config['secret'])) {
            $this->setSecret($config['secret']);
        }
        if (isset($config['scopes'])) {
            $this->setScope($config['scopes']);
        }
        if (isset($config['redirect_uri'])) {
            $this->setRedirectUri($config['redirect_uri']);
        }
        if (isset($config['response_type'])) {
            $this->setResponceType($config['response_type']);
        }
    }
    /**
     * Get the user id of current access token
     * @return integer
     */
    public function getUserId()
    {
        return $this->accessToken->user_id;
    }
    /**
     * Set the application id
     * @param integer $appId
     * @return \Vkontakte
     */
    public function setAppId($appId)
    {
        $this->appId = $appId;
        return $this;
    }
    /**
     * Get the application id
     * @return integer
     */
    public function getAppId()
    {
        return $this->appId;
    }
    /**
     * Set the application secret key
     * @param string $secret
     * @return \Vkontakte
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
        return $this;
    }
    /**
     * Get the application secret key
     * @return string
     */
    public function getSecret()
    {
        return $this->secret;
    }
    /**
     * Set the scope for login URL
     * @param array $scope
     * @return \Vkontakte
     */
    public function setScope(array $scope)
    {
        $this->scope = $scope;
        return $this;
    }
    /**
     * Get the scope for login URL
     * @return array
     */
    public function getScope()
    {
        return $this->scope;
    }
    /**
     * Set the URL to which the user will be redirected
     * @param string $redirect_uri
     * @return \Vkontakte
     */
    public function setRedirectUri($redirect_uri)
    {
        $this->redirect_uri = $redirect_uri;
        return $this;
    }
    /**
     * Get the URL to which the user will be redirected
     * @return string
     */
    public function getRedirectUri()
    {
        return $this->redirect_uri;
    }
    /**
     * Set the response type of login URL
     * @param string $responceType
     * @return \Vkontakte
     */
    public function setResponceType($responceType)
    {
        $this->responceType = $responceType;
        return $this;
    }
    /**
     * Get the response type of login URL
     * @return string
     */
    public function getResponceType()
    {
        return $this->responceType;
    }
    /**
     * Get the login URL via Vkontakte
     * @return string
     */
    public function getLoginUrl()
    {
        return 'https://oauth.vk.com/authorize'
        . '?client_id=' . urlencode($this->getAppId())
        . '&scope=' . urlencode(implode(',', $this->getScope()))
        . '&redirect_uri=' . urlencode($this->getRedirectUri())
        . '&response_type=' . urlencode($this->getResponceType())
        . '&v=' . urlencode(self::VERSION);
    }
    /**
     * Check is access token expired
     * @return boolean
     */
    public function isAccessTokenExpired()
    {
        return time() > $this->accessToken->created + $this->accessToken->expires_in;
    }
    /**
     * Authenticate user and get access token from server
     * @param string $code
     * @return \Vkontakte
     */
    public function authenticate($code = NULL)
    {
        $code = $code ? $code : $_GET['code'];
        $url = 'https://oauth.vk.com/access_token'
            . '?client_id=' . urlencode($this->getAppId())
            . '&client_secret=' . urlencode($this->getSecret())
            . '&code=' . urlencode($code)
            . '&redirect_uri=' . urlencode($this->getRedirectUri());
        $token = $this->curl($url);
        $data = json_decode($token);
        $data->created = time(); // add access token created unix timestamp
        $token = json_encode($data);
        $this->setAccessToken($token);
        return $this;
    }
    /**
     * Set the access token
     * @param string $token The access token in json format
     * @return \Vkontakte
     */
    public function setAccessToken($token)
    {
        $this->accessToken = json_decode($token);
        return $this;
    }
    /**
     * Get the access token
     * @param string $code
     * @return string The access token in json format
     */
    public function getAccessToken()
    {
        return json_encode($this->accessToken);
    }
    /**
     * Make an API call to https://api.vk.com/method/
     * @return string The response, decoded from json format
     */
    public function api($method, array $query = array())
    {
        /* Generate query string from array */
        $parameters = array();
        foreach ($query as $param => $value) {
            $q = $param . '=';
            if (is_array($value)) {
                $q .= urlencode(implode(',', $value));
            } else {
                $q .= urlencode($value);
            }
            $parameters[] = $q;
        }
        $q = implode('&', $parameters);
        if (count($query) > 0) {
            $q .= '&'; // Add "&" sign for access_token if query exists
        }
        $url = 'https://api.vk.com/method/' . $method . '?' . $q . 'access_token=' . $this->accessToken->access_token;
        $result = json_decode($this->curl($url));
        if (isset($result->response)) {
            return $result->response;
        }else{
        	return $result;
		}
    }
    /**
     * Make the curl request to specified url
     * @param string $url The url for curl() function
     * @return mixed The result of curl_exec() function
     * @throws \Exception
     */
    protected function curl($url)
    {
        // create curl resource
        $ch = curl_init();
        // set url
        curl_setopt($ch, CURLOPT_URL, $url);
        // return the transfer as a string
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        // disable SSL verifying
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        // $output contains the output string
        $result = curl_exec($ch);
        if (!$result) {
            $errno = curl_errno($ch);
            $error = curl_error($ch);
        }
        // close curl resource to free up system resources
        curl_close($ch);
        if (isset($errno) && isset($error)) {
            throw new \Exception($error, $errno);
        }
		echo $result;
        return $result;
    }
    /**
     * @param $publicID int vk group official identifier
     * @param $fullServerPathToImage string full path to the image file, ex. /var/www/site/img/pic.jpg
     * @param $text string message text
     * @param $tags array message tags
     * @return bool true if operation finished successfully and false otherwise
     */
    public function postToPublic($publicID, $text, $fullServerPathToImage, $tags = array())
    {
        $response = $this->api('photos.getWallUploadServer', array(
            'group_id' => $publicID,
        ));
        /*
         * public 'upload_url' => string 'http://cs618028.vk.com/upload.php?act=do_add&mid=76989657&aid=-14&gid=70941690&hash=0c9cdfa73779ea6c904c4b5326368700&rhash=ba9b60e61e258bf8fd61536e6683e3af&swfupload=1&api=1&wallphoto=1' (length=185)
              public 'aid' => int -14
              public 'mid' => int 76989657
         *
         *  */
        $uploadURL = $response->upload_url;
        $output = array();
        exec("curl -X POST -F 'photo=@$fullServerPathToImage' '$uploadURL'", $output);
        $response = json_decode($output[0]);
        /*
         *  public 'server' => int 618028
              public 'photo' => string '[{"photo":"96df595e0b:z","sizes":[["s","618028657","c5b1","RfjznPPyhxs",75,54],["m","618028657","c5b2","dQRTijvf4tE",130,93],["x","618028657","c5b3","-zUzUi-uOkU",604,432],["y","618028657","c5b4","FAAY0vnMSWc",807,577],["z","618028657","c5b5","OBZqwGjlO9s",900,644],["o","618028657","c5b6","Ku7Q6IqN5uc",130,93],["p","618028657","c5b7","0eFhSRrjxvU",200,143],["q","618028657","c5b8","F8E6QJg51o4",320,229],["r","618028657","c5b9","-a3oiI8SVOg",510,365]],"kid":"6bba9104fa05dd017597abce3ebeb215"}]' (length=496)
              public 'hash' => string 'd02d83e70eca1c0d756d1a5d51c2fbfb' (length=32)
         */
        $response = $this->api('photos.saveWallPhoto', array(
            'group_id' => $publicID,
            'photo' => $response->photo,
            'server' => $response->server,
            'hash' => $response->hash,
        ));
        if ($tags) {
            $text .= "\n\n";
        }
        foreach ($tags as $tag) {
            $text .= ' #' . str_replace(' ', '_', $tag);
        }
        $text = html_entity_decode($text);
        $response = $this->api('wall.post',
            array(
                'owner_id' => -$publicID,
                'from_group' => 1,
                'message' => "$text",
                'attachments' => "{$response[0]->id}", // uploaded image is passed as attachment
            ));
        return isset($response->post_id);
    }
}