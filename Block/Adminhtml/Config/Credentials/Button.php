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
namespace TIG\Postcode\Block\Adminhtml\Config\Credentials;

use Magento\Framework\View\Element\Template;
use Magento\Framework\Data\Form\Element\Renderer\RendererInterface;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Button extends Template implements RendererInterface
{
    public const MODULE_NAME = 'TIG_Postcode';

    public const CREDENTIALS_URL = 'https://postcodeservice.com/#compare-packages';

    // @codingStandardsIgnoreLine
    protected $_template = 'TIG_Postcode::config/credentials/button.phtml';

    // @codeCoverageIgnoreStart
    /**
     * @inheritdoc
     */
    public function render(AbstractElement $element)
    {
        /** @noinspection PhpUndefinedMethodInspection */
        $this->setElement($element);

        return $this->toHtml();
    }
    // @codeCoverageIgnoreEnd

    /**
     * Get Label
     *
     * @return \Magento\Framework\Phrase
     */
    public function getSignUpLabel()
    {
        return __('No credentials yet? Sign up for a plan');
    }

    /**
     * Get credentials Url
     *
     * @return string
     */
    public function getCredentialsUrl()
    {
        return static::CREDENTIALS_URL;
    }
}
