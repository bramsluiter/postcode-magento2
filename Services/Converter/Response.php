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
namespace TIG\Postcode\Services\Converter;

use TIG\Postcode\Services\Validation\Response as ValidationResponse;

class Response implements ConverterInterface
{
    /**
     * @var ValidationResponse
     */
    private ValidationResponse $validation;

    /**
     * Request constructor.
     *
     * @param ValidationResponse $validation
     */
    public function __construct(
        ValidationResponse $validation
    ) {
        $this->validation = $validation;
    }

    /**
     * @inheritdoc
     */
    public function setValidationKeys($keys): void
    {
        $this->validation->setKeyFields($keys);
    }

    /**
     * @inheritDoc
     */
    public function convert($data)
    {
        if (is_string($data)) {
            $data = json_decode($data, true);
        }

        if (!$this->validation->validateResponseData($data)) {
            return false;
        }

        return $data;
    }
}
