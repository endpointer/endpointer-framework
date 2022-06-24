<?php

namespace EndpointerFramework\Endpoint;

use EndpointerFramework\Exception\Http\HttpHeaderNotFoundException;
use EndpointerFramework\Exception\Http\InvalidJsonException;

class Request
{

	private $httpStatus;
	private $headers;
	private $body;

	function getField(

		$fieldName

	) {

		$t = $this;

		return

			isset(

				$t->getBody()[$fieldName]

			)

			? $t->getBody()[$fieldName]

			: null;
	}

	function getHeaders()
	{

		return $this->headers;
	}

	function getBody()
	{

		return $this->body;
	}

	function hasHeader($headerName)
	{

		if (array_key_exists($headerName, $this->getHeaders())) {

			return true;
		}

		return false;
	}

	function getHeader($headerName)
	{

		$t = $this;

		if ($t->hasHeader($headerName)) {

			return $t->getHeaders()[$headerName];
		}

		throw new HttpHeaderNotFoundException();
	}

	function __construct()
	{

		$t = $this;

		$t->httpStatus	=	$_SERVER['REQUEST_METHOD'];
		$t->headers		= 	getallheaders();

		if ($t->httpStatus == 'POST') {

			$this->body	= json_decode(file_get_contents('php://input'), true);

			if (\json_last_error() != JSON_ERROR_NONE) {

				throw new InvalidJsonException(\json_last_error_msg());
			}

			return;
		}

		$this->body	= $_GET;
	}
}
