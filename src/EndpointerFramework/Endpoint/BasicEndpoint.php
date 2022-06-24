<?php

namespace EndpointerFramework\Endpoint;

use EndpointerFramework\Constants;
use EndpointerFramework\Endpoint\Errors;

use EndpointerFramework\Exception\InternalServerErrorException;
use EndpointerFramework\Exception\Db\TransactionException;
use EndpointerFramework\Exception\Http\InvalidInputException;
use EndpointerFramework\Exception\Http\InvalidJsonException;

abstract class BasicEndpoint
{
    private $request;
    private $response;
    private $dbService;

    private $errors;

    public function processRequest()
    {
        $t = $this;

        $res = $t->getResponse();

        try {
            $t->request    = new Request();

            $t->validateInput();

            $t->run();
        } catch (InvalidJsonException $ex) {
            $res->setHttpStatus(Constants::HTTP_BADDATA);

            $res->setBody(
                [

                    'code'        =>    Constants::ERROR_INVALIDJSON,
                    'messages'    =>    [$ex->getMessage()]

                ]
            );

            $res->echoHeaders();

            $res->echoBody();

            return;
        } catch (InvalidInputException $ex) {
            $res->setHttpStatus(Constants::HTTP_BADDATA);

            $res->setBody(
                [

                    'code'        =>    Constants::ERROR_INVALIDINPUT,
                    'messages'    =>    $t

                        ->getErrors()
                        ->getErrorList()

                ]
            );

            $res->echoHeaders();

            $res->echoBody();

            return;
        } catch (TransactionException $ex) {

            $res->setHttpStatus(Constants::HTTP_BADDATA);

            $res->setBody(
                [

                    'code'        =>    Constants::ERROR_INVALIDDATA,
                    'messages'    =>    $t

                        ->getErrors()
                        ->getErrorList()

                ]
            );

            $res->echoHeaders();

            $res->echoBody();

            return;
        } catch (InternalServerErrorException $ex) {

            $res->setHttpStatus(

                Constants::HTTP_INTERNALERROR

            );

            $res->setBody(

                $ex->getMessage()

            );

            $res->echoHeaders();

            $res->echoBody();

            return;
        }

        $res->echoHeaders();

        $res->echoBody();
    }

    public function validateInput()
    {
    }

    abstract public function run();

    public function getErrors()
    {
        return $this->errors;
    }

    public function setTransaction($transaction)
    {
        $this->transaction = $transaction;
    }

    public function getTransaction()
    {
        return $this->transaction;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getResponse()
    {
        return $this->response;
    }

    function setDbService($dbService)
    {
        $this->dbService = $dbService;

        return $this;
    }

    function getDbService()
    {
        return $this->dbService;
    }

    function setEmailService($emailService)
    {
        $this->emailService = $emailService;

        return $this;
    }

    function throwTransactionException($ex = null)
    {

        throw new TransactionException($ex);
    }

    function throwInternalServerErrorException($ex)
    {

        throw new InternalServerErrorException($ex);
    }

    function throwInvalidInputException()
    {

        throw new InvalidInputException();
    }

    public function __construct()
    {
        $t = $this;

        $t->response    = new Response();

        $t->errors      = new Errors();
    }
}
