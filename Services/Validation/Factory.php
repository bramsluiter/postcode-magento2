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

use TIG\Postcode\Exception as PostcodeException;

class Factory
{
    /**
     * @var array|ValidationInterface[]
     */
    private array $validators;

    /**
     * Factory constructor.
     *
     * @param ValidationInterface[] $validators
     */
    public function __construct(array $validators = [])
    {
        $this->validators = $validators;
    }

    /**
     * Validate
     *
     * @param mixed $type
     * @param mixed $data
     *
     * @return bool|mixed
     * @throws PostcodeException
     */
    public function validate(mixed $type, mixed $data): mixed
    {
        foreach ($this->validators as $validator) {
            $this->checkImplementation($validator);
        }

        return $this->validator($type, $data);
    }

    /**
     * Validator
     *
     * @param mixed $type
     * @param mixed $data
     *
     * @return bool|mixed
     * @throws PostcodeException
     */
    private function validator(mixed $type, mixed $data): mixed
    {
        if (!isset($this->validators[$type])) {
            // @codingStandardsIgnoreLine
            throw new PostcodeException(__('Could not find type %1 as validator', $type));
        }

        return $this->validators[$type]->validateResponseData($data);
    }

    /**
     * Check implementations
     *
     * @param mixed $validator
     *
     * @throws PostcodeException
     */
    private function checkImplementation(mixed $validator): void
    {
        if (!array_key_exists(ValidationInterface::class, class_implements($validator))) {
            // @codingStandardsIgnoreLine
            throw new PostcodeException(__('Class is not an implementation of %1', ValidationInterface::class));
        }
    }
}
