<?php
/*
 * This file is part of the sym package.
 *
 * (c) toretto460 <toretto460@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Acme\SimplePurchaseProcessBundle\Tests\Mock;

use Guzzle\Http\Message\Response;

class MockedHttpClient
{
    static $queue;

    public function head($uri = null, $headers = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function get($uri = null, $headers = null, $options = array())
    {
        return $this->createFakeRequest();
    }

    public function delete($uri = null, $headers = null, $body = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function put($uri = null, $headers = null, $body = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function patch($uri = null, $headers = null, $body = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function post($uri = null, $headers = null, $postBody = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function options($uri = null, array $options = array())
    {
        return $this->createFakeRequest();
    }

    public function getMockedResponse()
    {
        return array_shift(self::$queue);
    }

    protected function createFakeRequest()
    {
        return new FakeRequest($this->getMockedResponse());
    }

    public static function addResponse(Response $response)
    {
        self::$queue[] = $response;
    }

    public static function resetResponses()
    {
        self::$queue = array();
    }

}
