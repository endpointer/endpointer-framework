<?php

namespace EndpointerFramework\Endpoint;

class Response
{
    private $httpStatus;

    private $headers    = [];
    private $body        = '';

    public function setHttpStatus($httpStatusCode)
    {
        $this->httpStatus = $httpStatusCode;
    }

    public function getHttpStatus()
    {
        return $this->httpStatus;
    }

    public function setBody($body)
    {

        $t = $this;

        $this->body = $body;

        return $t;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function addHeader($key, $value)
    {
        $this->headers[$key] = $value;
    }

    public function getHeaders()
    {
        return $this->headers;
    }

    public function echoHeaders()
    {
        \http_response_code($this->getHttpStatus());

        foreach ($this->getHeaders() as $key => $value) {
            header($key . ': ' . $value);
        }
    }

    public function echoBody()
    {
        echo \json_encode($this->getBody());
    }
}
