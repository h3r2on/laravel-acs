<?php namespace H3r2on\Acs;

class Acs {

	protected $apiUrl;
  protected $appKey;
	protected $email;
	protected $password;
	protected $cookiePath;
	protected $_errors;


  public function __construct()
  {
    $this->apiUrl = \Config::get("acs::api.apiurl");
    $this->appKey = \Config::get("acs::api.appkey");
		$this->cookiePath = app_path() . '/tmp/cookies/';
  }

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

  public function attempt($email, $password)
  {
    $userInfo = $this->authenticate($email, $password);

    if($userInfo) {
      return $userInfo;

    } else {

      return false;
    }

  }

  protected function authenticate($email, $password)
  {
    $login = array(
			'login'    => $email,
			'password' => $password
		);

		$sessionId = \Session::getId();

		$ch = curl_init($this->buildUrl('users/login.json'));

		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath.'jar_'.$sessionId.'.data');
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath.'jar_'.$sessionId.'.data');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $login);

		$login = curl_exec($ch);
		if($login == FALSE) {
			print_r(curl_error($ch));
		}

		$response = json_decode($login, true);

		$user = $response['response']['users'][0];

		return array(
			'id'    => $user['id'],
			'cn'    => $user['first_name'] . ' ' . $user['last_name'],
			'role'	=> $user['role'],
			'email'	=> $user['email']
		);
  }

  protected function send($verb, $url, $data, $secure)
  {
    $baseUri = $this->buildUrl($url, $secure);

    if(!empty($data) && $verb == 'get') {
    	$uri =  $baseUri . '&'. http_build_query($data);
    } else {
    	$uri =  $baseUri;
    }

		$sessionId = \Session::getId();

		$ch = curl_init($uri);

		curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookiePath.'jar_'.$sessionId.'.data');
		curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookiePath.'jar_'.$sessionId.'.data');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

		switch ($verb)
		{
			case 'GET':
				curl_setopt($ch, CURLOPT_HTTPGET, TRUE);
				break;
			case 'POST':
				curl_setopt($ch, CURLOPT_POST, TRUE);
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case 'PUT':
				curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'PUT');
				curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
				break;
			case 'DELETE':
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
