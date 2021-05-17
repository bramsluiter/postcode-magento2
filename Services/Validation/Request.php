<?php
/**
 *
 *          ..::..
 *     ..::::::::::::..
 *   ::'''''':''::'''''::
 *   ::..  ..:  :  ....::
 *   ::::  :::  :  :   ::
 *   ::::  :::  :  ''' ::
 *   ::::..:::..::.....::
 *     ''::::::::::::''
 *          ''::''
 *
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Creative Commons License.
 * It is available through the world-wide-web at this URL:
 * http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 * If you are unable to obtain it through the world-wide-web, please send an email
 * to support@postcodeservice.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact support@postcodeservice.com for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */
namespace TIG\Postcode\Services\Validation;

class Request implements ValidationInterface
{
    private $keysToContain = ['postcode', 'huisnummer'];

    /**
     * {@inheritDoc}
     */
    public function validate($data)
    {
        if (!is_array($data)) {
            return false;
        }

        if (!$this->checkKeys($data)) {
            return false;
        }

        return true;
    }

    /**
     * @param $keys
     */
    public function setKeys($keys)
    {
        $this->keysToContain = $keys;
    }

    public function getKeys()
    {
        return $this->keysToContain;
    }

    /**
     * @param $data
     *
     * @return bool
     */
    private function checkKeys($data)
    {
        $check = 0;
        foreach ($this->keysToContain as $key) {
            array_key_exists($key, $data)?: $check++;
        }

        return $check == 0;
    }
}
