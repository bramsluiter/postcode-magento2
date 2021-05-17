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
namespace TIG\Postcode\Plugin\Mageplaza;

use TIG\Postcode\Config\Provider\ModuleConfiguration;

class AddressHelper
{
    private $moduleConfig;

    public function __construct(
        ModuleConfiguration $moduleConfiguration
    ) {
        $this->moduleConfig = $moduleConfiguration;
    }

    /**
     * Subject => \Mageplaza\Osc\Helper\Address
     * Compatible plugin, Mageplaza skippes grouped fields, so we need to add this manualy.
     *
     * @param         $subject
     * @param         $fieldPosition
     *
     * @return mixed
     */
    // @codingStandardsIgnoreLine
    public function afterGetAddressFieldPosition($subject, $fieldPosition)
    {
        if ($this->moduleConfig->isModusOff()) {
            return $fieldPosition;
        }

        return $this->addPostcodeFieldGroup($fieldPosition);
    }

    /**
     * @param $fieldPosition
     *
     * @return mixed
     */
    private function addPostcodeFieldGroup($fieldPosition)
    {
        $fieldPosition = $this->orderFields($fieldPosition);

        $fieldPosition['postcode-field-group'] = [
            'sortOrder' => 65,
            'colspan'   => 12,
            'isNewRow'  => true
        ];

        return $fieldPosition;
    }

    /**
     * @see \Mageplaza\Osc\Block\Checkout\LayoutProcessor::addAddressOption
     * Because the postcode-field-group is not an Mageplaza OSC field it will be ignored.
     * Affects since OSC version 2.5.0
     *
     * @param $fieldPosition
     * @return mixed
     */
    private function orderFields(&$fieldPosition)
    {
        $sortOrder = 40;
        foreach ($fieldPosition as $key => &$field) {
            $field['sortOrder'] = $sortOrder += 10;
        }

        return $fieldPosition;
    }
}
