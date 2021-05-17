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
namespace TIG\Postcode\Test\Unit\Config\Source;

use TIG\Postcode\Test\TestCase;
use TIG\Postcode\Config\Source\Checkouts;

class CheckoutsTest extends TestCase
{
    protected $instanceClass = Checkouts::class;

    private $compatibleCheckouts = [
        'default', 'blank', 'onestepcheckout', 'mageplaza', 'danslo', 'amasty'
    ];

    public function testToOptionsArray()
    {
        $instance = $this->getInstance();
        $result = $instance->toOptionArray();

        $this->assertCount(6, $result);

        foreach ($result as $checkout) {
            $this->assertArrayHasKey('label', $checkout);
            $this->assertArrayHasKey('value', $checkout);

            $inArray = in_array($checkout['value'], $this->compatibleCheckouts);
            $this->assertTrue($inArray);
        }
    }
}
