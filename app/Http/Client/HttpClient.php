<?php

namespace App\Http\Client;

use App\Exception\ParserException;
use App\Interfaces\HttpClientInterface;

final class HttpClient implements HttpClientInterface
{
    /**
     * @var
     */
    private $url;

    /**
     * @var string
     */
    private $method = 'POST';

    /**
     * @var string
     */
    private $header;

    /**
     * @var string
     */
    private $content;

    /**
     * @param string $url
     * @return HttpClientInterface
     */
    public function setUrl(string $url): HttpClientInterface
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @param string $method
     * @return HttpClientInterface
     */
    public function setMethod(string $method): HttpClientInterface
    {
        $this->method = $method;

        return $this;
    }

    /**
     * @param string $header
     * @return HttpClientInterface
     */
    public function setHeader(string $header): HttpClientInterface
    {
        $this->header = $header;

        return $this;
    }

    /**
     * @param array $content
     * @return HttpClientInterface
     */
    public function setContent(array $content): HttpClientInterface
    {
        $this->content = http_build_query($content);

        return $this;
    }

    /**
     * @return null|string
     */
    public function get():? string
    {
        if ($this->validateGet()) {
            throw new \Exception('Your get request is not correct!');
        }

        $response = file_get_contents($this->url);

        if ($response === false) {
            $response = null;
        }

        return $response;
    }

    /**
     * @return bool
     */
    private function validateGet()
    {
        return empty($this->url);
    }

    /**
     * @return string
     * @throws ParserException
     */
    public function post(): string
    {
        if ($this->validatePost()) {
            throw new ParserException('Your post request is not correct!');
        }

        $options = $this->getPostOptions();

        $response = file_get_contents($this->url, false, stream_context_create($options));

        if ($response === false) {
            throw new ParserException('You have false response!');
        }

        return $response;
    }

    /**
     * @return array
     */
    private function getPostOptions(): array
    {
        return [
            'http' => [
                'method' => $this->method,
                'header' => $this->header,
                'content' => $this->content
            ]
        ];
    }

    /**
     * @return bool
     */
    private function validatePost()
    {
        return empty($this->url) || empty($this->header) || empty($this->content);
    }
}