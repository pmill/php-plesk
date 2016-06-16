<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\GenSetup;
use pmill\Plesk\Helper\Hosting;
use pmill\Plesk\Helper\HostingPermissions;
use pmill\Plesk\Helper\Limits;
use pmill\Plesk\Helper\PHPSettings;
use pmill\Plesk\Helper\SubscriptionPrefs;

class UpdateSubscription extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
<webspace>
	<set>
	    {FILTER}
        {VALUES}
	</set>
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
        'values' => null,
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

        if (isset($params['values']) && is_array($params['values'])) {
            foreach ($params['values'] as $key => $values) {
                switch ($key) {
                    case 'hosting':
                        $helper = new Hosting();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'gen_setup':
                        $helper = new GenSetup();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'limits':
                        $helper = new Limits();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'prefs':
                        $helper = new SubscriptionPrefs();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'permissions':
                        $helper = new HostingPermissions();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'php-settings':
                        $helper = new PHPSettings();
                        $params['values'][$key] = $helper->generate($values);
                        break;
                    case 'plan-id':
                    case 'plan-name':
                    case 'plan-guid':
                    case 'plan-external-id':
                        $params['values'][$key] = $values;
                        break;
                }
            }

            $params['values'] = new Node('values', NodeList::createFromKeyValueArray($params['values']));
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
        $result = $xml->webspace->set->result;

        if ($result->status == 'error') {
            throw new ApiRequestException($result);
        }

        return true;
    }
}
