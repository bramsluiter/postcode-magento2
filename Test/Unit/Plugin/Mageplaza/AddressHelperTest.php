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
namespace TIG\Postcode\Test\Unit\Plugin\Mageplaza;

use TIG\Postcode\Plugin\Mageplaza\AddressHelper;
use TIG\Postcode\Test\TestCase;
use TIG\Postcode\Config\Provider\ModuleConfiguration;

class AddressHelperTest extends TestCase
{
    /** @var AddressHelper\ */
    protected $instanceClass = AddressHelper::class;

    /**
     * @return array[]
     */
    public function pluginProvider()
    {
        return [
            'modus off is false' => [
                [], false, ['postcode-field-group' => [
                    'sortOrder' => 65,
                    'colspan'   => 12,
                    'isNewRow'  => true
                ]]
            ],
            'modus off is true' => [
                ['modus_off' => true], true, ['modus_off' => true]
            ]
        ];
    }

    /**
     * @param $fields
     * @param $moduleOff
     * @param $expected
     *
     * @dataProvider pluginProvider
     * @throws \Exception
     */
    public function testAfterGetAddressFieldPosition($fields, $moduleOff, $expected)
    {
        $moduleConfigMock = $this->getFakeMock(ModuleConfiguration::class)->getMock();
        $expectedConfig = $moduleConfigMock->expects($this->once());
        $expectedConfig->method('isModusOff');
        $expectedConfig->willReturn($moduleOff);

        $instance = $this->getInstance([
            'moduleConfiguration' => $moduleConfigMock
        ]);

        $result = $instance->afterGetAddressFieldPosition(null, $fields);
        $this->assertEquals($expected, $result);
    }
}
