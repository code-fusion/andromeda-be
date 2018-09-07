<?php

require_once __DIR__ . "/CurlResponse.class.php";

class CurlRequest {

	//Basic options
	private $ch;
	private $url;

	//Response
	//private $responseBody;
	//private $responseHeader;
	//private $responseHeaderSize;
	private $response;

	//Request
	private $requestBody;
	private $requestHeader;
	private $postData;

	/**
	 * Constructor
	 *
	 * Initialize Curl and set CURLOPT_HEADER to true so the response header
	 *  is returned in execution
	 *
	 * @param string $url
	 * @return
	 * @author Maximiliano Atanasio
	 */
	public function __construct($url) {

		$this -> response = new CurlResponse();
		$this -> url = $url;
		$this -> ch = curl_init($url);
		curl_setopt($this -> ch, CURLOPT_HEADER, true);
		curl_setopt($this -> ch, CURLOPT_TIMEOUT, 8000);

	}

	/**
	 * getString
	 *
	 * Executes the Curl getting the response as a string. It sets the respon
	 * nse body, header and header size.
	 *
	 * @param
	 * @return string $responseBody
	 * @author Maximiliano Atanasio
	 */
	public function getString() {

		$this -> get();
		return $this -> response -> Body;

	}

	/**
	 * get
	 *
	 * Executes the Curl. It sets the response body, header and header size.
	 *
	 * @param  $
	 * @return
	 * @author Maximiliano Atanasio
	 */
	private function get() {
		curl_setopt($this -> ch, CURLOPT_RETURNTRANSFER, true);
		$response = curl_exec($this -> ch);
		$this -> response -> HeaderSize = curl_getinfo($this -> ch, CURLINFO_HEADER_SIZE);
		$this -> response -> Header = substr($response, 0, $this -> response -> HeaderSize);
		$this -> response -> Body = substr($response, $this -> response -> HeaderSize);
	}

	/**
	 * render
	 *
	 * Executes the Curl and render the result to the navigator
	 *
	 * @param  $
	 * @return
	 * @author Maximiliano Atanasio
	 */
	public function render() {

		curl_setopt($this -> ch, CURLOPT_HEADER, false);
		curl_exec($this -> ch);

	}

	/**
	 * getJson
	 *
	 * Executes the Curl and return the response body using json_decode. It s
	 * sets the response body, header and header size.
	 *
	 * @param
	 * @return Array $responseBody
	 * @author Maximiliano Atanasio
	 */
	public function getJson() {

		$this -> get();
		return json_decode($this -> response -> Body);

	}

	/**
	 * __destruct
	 *
	 * Close the Curl session
	 *
	 * @param  $
	 * @return
	 * @author Maximiliano Atanasio
	 */
	function __destruct() {

		curl_close($this -> ch);

	}

	public function addPostData($postdata) {

		$fields_string = "";

		//url-ify the data for the POST
		foreach ($postdata as $key => $value) {
			$fields_string .= $key . '=' . $value . '&';
		}
		rtrim($fields_string, '&');

		curl_setopt($this->ch, CURLOPT_POST, count($postdata));
		curl_setopt($this->ch, CURLOPT_POSTFIELDS, $fields_string);
		
	}

}
