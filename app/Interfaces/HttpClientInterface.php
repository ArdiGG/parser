<?php

namespace App\Interfaces;

interface HttpClientInterface
{
    /**
     * @param string $url
     * @return HttpClientInterface
     */
    public function setUrl(string $url): HttpClientInterface;

    /**
     * @param string $method
     * @return HttpClientInterface
     */
    public function setMethod(string $method): HttpClientInterface;

    /**
     * @param string $header
     * @return HttpClientInterface
     */
    public function setHeader(string $header): HttpClientInterface;

    /**
     * @param array $content
     * @return HttpClientInterface
     */
    public function setContent(array $content): HttpClientInterface;

    /**
     * @return string
     */
    public function get():? string;

    /**
     * @return string
     */
    public function post(): string;
}