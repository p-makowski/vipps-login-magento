<?php
/**
 * Copyright 2019 Vipps
 *
 *    Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 *    documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 *    the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software,
 *    and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 *    THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED
 *    TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NON INFRINGEMENT. IN NO EVENT SHALL
 *    THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF
 *    CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS
 *    IN THE SOFTWARE
 */

declare(strict_types=1);

namespace Vipps\Login\Model;

use Magento\Framework\Math\Random;
use Magento\Framework\Session\SessionManagerInterface;

/**
 * Class StateKey
 * @package Vipps\Login\Model
 */
class StateKey
{
    /**
     * @var string
     */
    const DATA_KEY_STATE = 'vipps_login_url_state';

    /**
     * @var SessionManagerInterface
     */
    private $sessionManager;

    /**
     * @var Random
     */
    private $mathRand;

    /**
     * StateKey constructor.
     *
     * @param SessionManagerInterface $sessionManager
     * @param Random $mathRand
     */
    public function __construct(
        SessionManagerInterface $sessionManager,
        Random $mathRand
    ) {
        $this->sessionManager = $sessionManager;
        $this->mathRand = $mathRand;
    }

    /**
     * Method to generate stateKey for vipps/login requests.
     * This stateKey uses for validation on redirect action from vipps/login.
     *
     * @return string
     */
    public function generate()
    {
        $state = $this->mathRand->getUniqueHash();
        $this->sessionManager->setData(self::DATA_KEY_STATE, $state);
        return $state;
    }

    /**
     * @param $state
     *
     * @return bool
     */
    public function isValid($state): bool
    {
        return $state == $this->sessionManager->getData(self::DATA_KEY_STATE);
    }
}
