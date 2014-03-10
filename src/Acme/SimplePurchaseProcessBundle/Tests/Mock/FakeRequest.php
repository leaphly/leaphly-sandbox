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

use Guzzle\Http\Message\Request;

class FakeRequest extends Request
{
    protected $fakeResponse;

    public function __construct($response)
    {
        $this->fakeResponse = $response;
    }

    public function send()
    {
        return $this->fakeResponse;
    }

    public function setAuth($user, $password = '', $scheme = 'Basic')
    {
        return $this;
    }

    public function setPort($port)
    {
        return $this;
    }
}
