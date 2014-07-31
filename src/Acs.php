<?php namespace H3r2on\Acs;

class Acs {

  protected $apiUrl;
	protected $appKey;
	protected $email;
	protected $password;
	protected $_cookie = '/tmp/appcookie';
	protected $_errors;


  public static function delete($url, $data = null, $secure = TRUE)
  {
    return static::send('delete', $url, $data, $secure);
  }

  public static function get($url, $data = null, $secure = TRUE)
  {
    return static::send('get', $url, $data, $secure);
  }

  public static function post($url, $data = null, $secure = TRUE)
  {
    return static::send('post', $url, $data, $secure);
  }

  public static function put($url, $data = null, $secure = TRUE)
  {
    return static::send('put', $url, $data, $secure);
  }

  public function __construct()
  {
    $this->apiUrl = Config::get('acs::apiUrl');
    $this->appKey = Config::get('acs::appKey');
  }

  protected static function send($verb, $url, $data, $secure) {
    $uri = self::buildUrl($url, $secure) . ($verb == 'get' ? '&'. http_build_query($data) : '');

    $ch = curl_init($url);

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

  protected static function buildUrl($url,$secure=TRUE)
  {
    $finalUrl = ($secure === TRUE) ? 'https://' : 'http://';
    $finalUrl .= this->$apiUrl . $url . '?key=' . $this->appKey;

    return $finalUrl;
  }
}
