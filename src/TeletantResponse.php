<?php

namespace Askoldex\Teletant;


use Askoldex\Teletant\Exception\ResponseException;
use Askoldex\Teletant\Exception\TeletantException;
use GuzzleHttp\Promise\PromiseInterface;
use Psr\Http\Message\ResponseInterface;

class TeletantResponse
{
    /**
     * @var null|int The HTTP status code response from API.
     */
    protected $httpStatusCode;
    /**
     * @var array The headers returned from API request.
     */
    protected $headers;
    /**
     * @var string The raw body of the response from API request.
     */
    protected $body;

    /**
     * @var array The decoded body of the API response.
     */
    protected $decodedBody = [];
    private $thrownException;

    /**
     * Gets the relevant data from the Http client.
     *
     * @param ResponseInterface|PromiseInterface $response
     */
    public function __construct($response)
    {
        if ($response instanceof ResponseInterface) {
            $this->httpStatusCode = $response->getStatusCode();
            $this->body = $response->getBody();
            $this->headers = $response->getHeaders();
            $this->decodeBody();
        } elseif ($response instanceof PromiseInterface) {
            $this->httpStatusCode = null;
        } else {
            throw new \InvalidArgumentException(
                'Constructor argument must be instance of ResponseInterface or PromiseInterface'
            );
        }
    }

    /**
     * Gets the HTTP status code.
     * Returns NULL if the request was asynchronous since we are not waiting for the response.
     *
     * @return null|int
     */
    public function getHttpStatusCode()
    {
        return $this->httpStatusCode;
    }

    /**
     * Return the HTTP headers for this response.
     *
     * @return array
     */
    public function getHeaders()
    {
        return $this->headers;
    }
    /**
     * Return the raw body response.
     *
     * @return string
     */
    public function getBody()
    {
        return $this->body;
    }
    /**
     * Return the decoded body response.
     *
     * @return array
     */
    public function getDecodedBody()
    {
        return $this->decodedBody;
    }
    /**
     * Helper function to return the payload of a successful response.
     *
     * @return mixed
     */
    public function getResult()
    {
        return $this->decodedBody['result'];
    }
    /**
     * Checks if response is an error.
     *
     * @return bool
     */
    public function isError()
    {
        return isset($this->decodedBody['ok']) && ($this->decodedBody['ok'] === false);
    }
    /**
     * Throws the exception.
     *
     * @throws TeletantException
     */
    public function throwException()
    {
        throw $this->thrownException;
    }
    /**
     * Instantiates an exception to be thrown later.
     */
    public function makeException()
    {
        $this->thrownException = ResponseException::create($this);
    }
    /**
     * Returns the exception that was thrown for this request.
     *
     * @return TeletantException
     */
    public function getThrownException()
    {
        return $this->thrownException;
    }

    /**
     * Converts raw API response to proper decoded response.
     */
    public function decodeBody()
    {
        $this->decodedBody = json_decode($this->body, true);
        if ($this->decodedBody === null) {
            $this->decodedBody = [];
            parse_str($this->body, $this->decodedBody);
        }
        if (!is_array($this->decodedBody)) {
            $this->decodedBody = [];
        }
        if ($this->isError()) {
            $this->makeException();

        }
    }
}