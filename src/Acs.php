<?php namespace H3r2on\Acs;

class Acs {

	protected $apiUrl;
  protected $appKey;
	protected $email;
	protected $password;
	protected $_cookie = '/tmp/appcookie';
	protected $_errors;


  public function delete($url, $data = null, $secure = TRUE)
  {
    return $this->send('delete', $url, $data, $secure);
  }

  public function get($url, $data = null, $secure = TRUE)
  {
    
    return $this->send('get', $url, $data, $secure);
  }

  public function post($url, $data = null, $secure = TRUE)
  {
    return $this->send('post', $url, $data, $secure);
  }

  public function put($url, $data = null, $secure = TRUE)
  {
    return $this->send('put', $url, $data, $secure);
  }

  public function __construct()
  {
    $this->apiUrl = \Config::get("acs::api.apiurl");
    $this->appKey = \Config::get("acs::api.appkey");
  }

  protected function send($verb, $url, $data, $secure) {
    $baseUri = $this->buildUrl($url, $secure);

    if(!empty($data) && $verb == 'get') {
    	$uri =  $baseUri . '&'. http_build_query($data);
    } else {
    	$uri =  $baseUri; 
    }
    

    $ch = curl_init($uri);

		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->_cookie);
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->_cookie);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		switch ($verb)
		{
			case 'get':
				curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
				break;

			case 'post':
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;

			case 'put':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;

			case 'delete':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
		}

		$output = curl_exec($ch);

		if ($output == FALSE)
		{
			return curl_error($ch);
		}

		return json_decode($output, true);
  }

  protected function buildUrl($url,$secure=TRUE)
  {
    $finalUrl = ($secure === TRUE) ? 'https://' : 'http://';
    $finalUrl .= $this->apiUrl . $url . '?key=' . $this->appKey;

    return $finalUrl;
  }
}
