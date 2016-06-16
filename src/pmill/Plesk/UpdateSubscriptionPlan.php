<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\GenSetup;
use pmill\Plesk\Helper\Hosting;
use pmill\Plesk\Helper\HostingPermissions;
use pmill\Plesk\Helper\Limits;
use pmill\Plesk\Helper\PHPSettings;
use pmill\Plesk\Helper\SubscriptionPrefs;

class UpdateSubscriptionPlan extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
<webspace>
	<switch-subscription>
	    {FILTER}
        {VALUES}
	</switch-subscription>
</webspace>
</packet>
EOT;

    /**
     * @var int
     */
    public $id;

    /**
     * @var array
     */
    protected $default_params = [
        'filter' => null,
    ];

    /**
     * @param array $config
     * @param array $params
     * @throws ApiRequestException
     */
    public function __construct(array $config, array $params)
    {
        if (isset($params['filter']) && is_array($params['filter'])) {
            $params['filter'] = new Node('filter', NodeList::createFromKeyValueArray($params['filter']));
        }

        foreach ($params as $key => $value) {
            switch ($key) {
                case 'plan-guid':
                case 'plan-external-id':
                    $params['values'] = new Node($key, $value);
                    break;
                case 'no-plan':
                    $params['values'] = new Node($key);
                    break;
                default:
                    break;
            }
        }

        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return bool
     * @throws ApiRequestException
     */
    protected function processResponse($xml)
    {
        $result = $xml->webspace->{'switch-subscription'}->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        return true;
    }
}
