<?php

namespace Bram\Postcode2\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Zend\Http\Client as HttpClient;

class ApiClient extends AbstractHelper
{
    const PDOK_API_URL = 'https://api.pdok.nl/bzk/locatieserver/search/v3_1/free';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function getAddressData($query)
    {
        $client = new HttpClient();
        $client->setUri(self::PDOK_API_URL);
        $client->setParameterGet(['q' => $query]);
        $response = $client->send();
        
        if ($response->isSuccess()) {
            return json_decode($response->getBody(), true);
        }

        return null;
    }
}
