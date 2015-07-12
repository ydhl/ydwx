<?php
class YDHttp{

	/* Contains the last HTTP status code returned. */
	public $http_code;

	/* Contains the last API call. */
	public $url;

	/* Set timeout default. */
	public $timeout = 30;

	/* Set connect timeout. */
	public $connecttimeout = 30;

	/* Verify SSL Cert. */
	public $ssl_verifypeer = FALSE;


	/* Contains the last HTTP headers returned. */
	public $http_info;

	/* Set the useragnet. */
	public $useragent = 'jiaoliuping.com';

	static $boundary = "";


	public function get($url, $params = array())
	{
		if($params){
			$url .= "?".http_build_query($params);
		}
		return $this->http($url,'GET');
	}

	function post($url, $params = array(), $multi = false) {
		$query = "";
		if( is_array($params)){
			if($multi)
				$query = self::build_http_query_multi($params);
			else
				$query = http_build_query($params);
		}else{
			$query = $params;
		}

		return $this->http($url,'POST', $query, $multi);
	}

	public static function build_http_query_multi($params) {
		if (!$params) return '';

		// Urlencode both keys and values
		$keys = array_keys($params);
		$values = array_values($params);
		$params = array_combine($keys, $values);

		// Parameters are sorted by name, using lexicographical byte value ordering.
		// Ref: Spec: 9.1.1 (1)
		uksort($params, 'strcmp');
		$pairs = array();
		self::$boundary = $boundary = uniqid('------------------');
		$MPboundary = '--'.$boundary;
		$endMPboundary = $MPboundary. '--';
		$multipartbody = '';

		foreach ($params as $parameter => $value)
		{
			if( in_array($parameter,array("pic","image")) && $value{0} == '@' )
			{
				$url = ltrim( $value , '@' );
				$content = file_get_contents( $url );
				$filename = reset( explode( '?' , basename( $url ) ));
				$mime = self::get_image_mime($url);

				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'Content-Disposition: form-data; name="' . $parameter . '"; filename="' . $filename . '"'. "\r\n";
				$multipartbody .= 'Content-Type: '. $mime . "\r\n\r\n";
				$multipartbody .= $content. "\r\n";
			}
			else
			{
				$multipartbody .= $MPboundary . "\r\n";
				$multipartbody .= 'content-disposition: form-data; name="'.$parameter."\"\r\n\r\n";
				$multipartbody .= $value."\r\n";
			}
		}
		$multipartbody .=  $endMPboundary;
		return $multipartbody;

	}
	public static function get_image_mime( $file )
	{
		$ext = strtolower(pathinfo( $file , PATHINFO_EXTENSION ));
		switch( $ext )
		{
			case 'jpg':
			case 'jpeg':
				$mime = 'image/jpg';
				break;

			case 'png';
			$mime = 'image/png';
			break;

			case 'gif';
			default:
				$mime = 'image/gif';
				break;
		}
		return $mime;
	}
	/**
	 * Make an HTTP request
	 *
	 * @return API results
	 */
	function http($url, $method, $postfields = NULL, $multi = false) {
		$this->http_info = array();
		$ci = curl_init();
		/* Curl settings */
		curl_setopt($ci, CURLOPT_USERAGENT, $this->useragent);
		curl_setopt($ci, CURLOPT_CONNECTTIMEOUT, $this->connecttimeout);
		curl_setopt($ci, CURLOPT_TIMEOUT, $this->timeout);
		curl_setopt($ci, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ci, CURLOPT_HTTPHEADER, array('Expect:'));
		curl_setopt($ci, CURLOPT_SSL_VERIFYPEER, $this->ssl_verifypeer);
		curl_setopt($ci, CURLOPT_HEADER, FALSE);

		switch ($method) {
			case 'POST':
				curl_setopt($ci, CURLOPT_POST, TRUE);
				if (!empty($postfields)) {
					curl_setopt($ci, CURLOPT_POSTFIELDS, $postfields);
				}
				break;
			case 'DELETE':
				curl_setopt($ci, CURLOPT_CUSTOMREQUEST, 'DELETE');
				if (!empty($postfields)) {
					$url = "{$url}?{$postfields}";
				}
		}

		if( $multi )
		{
			$header_array = array("Content-Type: multipart/form-data; boundary=" . self::$boundary , "Expect: ");
			curl_setopt($ci, CURLOPT_HTTPHEADER, $header_array );
			curl_setopt($ci, CURLINFO_HEADER_OUT, TRUE );
		}
		//echo $url;
		curl_setopt($ci, CURLOPT_URL, $url);
		$response = curl_exec($ci);
		$this->http_code = curl_getinfo($ci, CURLINFO_HTTP_CODE);
		$this->http_info = array_merge($this->http_info, curl_getinfo($ci));
		$this->url = $url;
		curl_close ($ci);
		return trim($response);
	}

}