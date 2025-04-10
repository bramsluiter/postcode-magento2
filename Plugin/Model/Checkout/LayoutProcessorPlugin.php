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
 * to servicedesk@totalinternetgroup.nl so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize this module for your
 * needs please contact servicedesk@tig.nl for more information.
 *
 * @copyright   Copyright (c) Total Internet Group B.V. https://tig.nl/copyright
 * @license     http://creativecommons.org/licenses/by-nc-nd/3.0/nl/deed.en_US
 */

namespace TIG\Postcode\Plugin\Model\Checkout;

use Magento\Checkout\Block\Checkout\LayoutProcessor;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Notification\NotifierInterface;
use Magento\Framework\Stdlib\ArrayManager;
use Magento\Store\Model\ScopeInterface;

class LayoutProcessorPlugin
{
    private const MAGENTO_POSTCODE_COMPONENT_JS   = "Magento_Ui/js/form/element/post-code";

    private const TIG_POSTCODE_COMPONENT_JS       = "TIG_Postcode/js/form/element/tig-postcode-field";

    private const TIG_POSTCODE_COMPONENT_TEMPLATE = 'TIG_Postcode/form/element/postcode-field';

    private const COUNTRY_CODE_PATH               = 'general/country/default';

    /**
     * @var ArrayManager
     */
    private $arrayManager;

    /**
     * @var NotifierInterface
     */
    private $notifier;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @param ArrayManager         $arrayManager
     * @param NotifierInterface    $notifier
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        ArrayManager $arrayManager,
        NotifierInterface $notifier,
        ScopeConfigInterface $scopeConfig
    ) {
        $this->arrayManager = $arrayManager;
        $this->notifier     = $notifier;
        $this->scopeConfig  = $scopeConfig;
    }

    /**
     * Adds tracked fields, so we can dynamically add classes or visibility
     *
     * @param string|int|array $jsLayout
     * @param string|int|array $fieldsetChildren
     *
     * @return array
     */
    public function addTrackedFields($jsLayout, $fieldsetChildren): array
    {
        foreach (
            [
                'street',
                'city',
                'postcode'
            ] as $key) {
            $jsLayout = $this->arrayManager->set(
                $fieldsetChildren . '/' . $key . '/tracks/additionalClasses',
                $jsLayout,
                true
            );
        }

        return $jsLayout;
    }

    /**
     * Create the base field configuration for the checkout provider
     *
     * @param string $dataScope
     * @param string $index
     * @param string $label
     * @param array  $options
     *
     * @return array
     */
    private function createBaseFieldConfig($dataScope, $index, $label, $options = [])
    {
        return array_merge_recursive([
            'component' => "Magento_Ui/js/form/element/abstract",
            'dataScope' => $dataScope . '.custom_attributes.' . $index,
            'config'    => [
                'customScope' => $dataScope . '.custom_attributes',
                'template'    => 'ui/form/field',
                'elementTmpl' => 'ui/form/element/input',
            ],
            'label'     => __($label),
            'provider'  => 'checkoutProvider',
            'visible'   => false,
            'tracks'    => [
                'additionalClasses' => true
            ]
        ], $options);
    }

    /**
     * Create Field definitions
     *
     * @param string $dataScope
     *
     * @return array[]
     */
    public function createHousenumberFieldsDefinition(string $dataScope): array
    {
        return [
            'tig_housenumber'          => $this->createBaseFieldConfig(
                $dataScope,
                'tig_housenumber',
                'Housenumber',
                [
                    "sortOrder"  => 51,
                    "validation" => ["required-entry" => true, "validate-number" => true]
                ]
            ),
            'tig_housenumber_addition' => $this->createBaseFieldConfig(
                $dataScope,
                'tig_housenumber_addition',
                'Housenumber addition',
                ["sortOrder" => 52]
            ),
            'tig_street'               => $this->createBaseFieldConfig(
                $dataScope,
                'tig_street',
                'Street Address',
                ["sortOrder" => 53]
            )
        ];
    }

    /**
     * Split string and remove last n element(s)
     *
     * @param array|string $path
     * @param string       $delimiter
     * @param int          $count
     *
     * @return string
     */
    private function getParentPath(array|string $path, string $delimiter = '/', int $count = 1): string
    {
        $splitPath = !empty($path) ? explode($delimiter, $path) : [];
        for ($i = 0; $i < $count; $i++) {
            array_pop($splitPath);
        }

        return implode($delimiter, $splitPath);
    }

    /**
     * Add message to admin panel
     *
     * Used when confronted with incompatible checkout
     *
     * @param mixed $message
     */
    public function addAdminErrorMessage($message): void
    {
        $this->notifier->addMajor(
            "TIG Postcode",
            "Postcodeservice detected one or more compatibility issues with your shop setup"
        );
    }

    /**
     * Modify checkout to add fields and change postcode field behaviour
     *
     * @param LayoutProcessor $subject
     * @param array           $jsLayout
     *
     * @return array
     *
     * @see LayoutProcessor::process()
     */
    public function afterProcess($subject, $jsLayout)
    {
        $postalCodePaths = $this->arrayManager->findPaths('postcode', $jsLayout);
        foreach ($postalCodePaths as $postalCodePath) {
            $fieldsetChildren = $this->getParentPath($postalCodePath, '/');

            if ($this->arrayManager->get(
                    $postalCodePath . '/component',
                    $jsLayout
                ) !== self::MAGENTO_POSTCODE_COMPONENT_JS
                xor $this->arrayManager->get(
                    $postalCodePath . '/component',
                    $jsLayout
                ) === self::TIG_POSTCODE_COMPONENT_JS) {
                $this->addAdminErrorMessage('Incompatible postcode field found @ ' . $postalCodePath . ': ' .
                                            $this->arrayManager->get($postalCodePath . '/component', $jsLayout));
                continue;
            }

            // Update PostcodeField
            $jsLayout = $this->arrayManager->set(
                $postalCodePath . '/component',
                $jsLayout,
                self::TIG_POSTCODE_COMPONENT_JS
            );
            $jsLayout = $this->arrayManager->set(
                $postalCodePath . '/config/elementTmpl',
                $jsLayout,
                self::TIG_POSTCODE_COMPONENT_TEMPLATE
            );

            // Order is set in CollectionPlugin.php
            $jsLayout = $this->arrayManager->set($postalCodePath . '/config/sortOrder', $jsLayout, 50);

            if ($this->arrayManager->get($postalCodePath . '/dataScope', $jsLayout) !== null) {
                // Add housenumber fields
                $postcodeParentDataScope = $this->getParentPath(
                    $this->arrayManager->get($postalCodePath . '/dataScope', $jsLayout),
                    "."
                );
                $jsLayout                = $this->arrayManager->merge(
                    $fieldsetChildren,
                    $jsLayout,
                    $this->createHousenumberFieldsDefinition($postcodeParentDataScope)
                );
            }

            // Modify fields
            $jsLayout = $this->addTrackedFields($jsLayout, $fieldsetChildren);
        }

        return $jsLayout;
    }
}
