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
namespace TIG\Postcode\Test\Unit\Block\Adminhtml\Config\Form\Field;

use TIG\Postcode\Test\TestCase;
use Magento\Backend\Block\Context;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

use \TIG\Postcode\Block\Adminhtml\Config\Form\Field\Fieldset;

class FieldsetTest extends TestCase
{
    /** @var Fieldset */
    protected $instanceClass = Fieldset::class;

    /**
     * @return array
     */
    public function fieldProvider()
    {
        return [
            'inactive' => [
                '0',
                'modus_off'
            ],
            'live modus' => [
                '1',
                'modus_live'
            ],
            'test modus' => [
                '2',
                'modus_test'
            ]
        ];
    }

    /**
     * @param $configValue
     * @param $expected
     * @dataProvider fieldProvider
     * @throws \Exception
     */
    public function testGetFrontendClass($configValue, $expected)
    {
        $elementMock = $this->getFakeMock(AbstractElement::class)->getMockForAbstractClass();
        $requestMock = $this->getFakeMock(RequestInterface::class)->getMockForAbstractClass();

        $scopeConfigMock = $this->getFakeMock(ScopeConfigInterface::class)->getMockForAbstractClass();
        $scopeConfigMock->expects($this->once())->method('getValue')->willReturn($configValue);

        $contextMock = $this->getFakeMock(Context::class)->setMethods(['getScopeConfig', 'getRequest'])->getMock();
        $contextMock->expects($this->once())->method('getScopeConfig')->willReturn($scopeConfigMock);
        $contextMock->expects($this->once())->method('getRequest')->willReturn($requestMock);

        $instance = $this->getInstance(['context' => $contextMock]);
        $result   = $this->invokeArgs('_getFrontendClass', [$elementMock], $instance);
        $this->assertContains($expected, $result);
    }
}
