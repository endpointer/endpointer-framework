<?php

namespace EndpointerFramework\Endpoint;

use EndpointerFramework\Exception\Http\InvalidInputException;

class Errors
{

	private $errorList	= [];

	function addError(

		$errorMessage

	) {

		$t = $this;

		if (

			in_array(

				$errorMessage,

				$t->getErrorList()

			)

		) {

			return;
		}

		$this->errorList[]	= $errorMessage;

		return $t;
	}

	function setErrorList(

		$errorList

	) {

		$t = $this;

		$t->errorList = $errorList;

		return $t;
	}

	function getErrorList()
	{

		$t = $this;

		return $t->errorList;
	}

	function throwInvalidInputException()
	{

		$t = $this;

		if (

			count($t->errorList) > 0

		) {

			throw new InvalidInputException();
		}
	}
}
