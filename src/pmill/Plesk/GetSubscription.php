<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\Xml;

class GetSubscription extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<webspace>
    <get>
        {FILTER}
        <dataset>
			<hosting/>
			<subscriptions/>
		</dataset>
    </get>
</webspace>
</packet>
EOT;

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
    public function __construct($config, $params = [])
    {
        $this->default_params['filter'] = new Node('filter');

        if (isset($params['client_id'])) {
            $ownerIdNode = new Node('owner-id', $params['client_id']);
            $params['filter'] = new Node('filter', $ownerIdNode);
        }
        if (isset($params['username'])) {
            $ownerLoginNode = new Node('owner-login', $params['username']);
            $params['filter'] = new Node('filter', $ownerLoginNode);
        }
        if (isset($params['name'])) {
            $nameNode = new Node('name', $params['name']);
            $params['filter'] = new Node('filter', $nameNode);
        }
        if (isset($params['subscription_id'])) {
            $idNode = new Node('id', $params['subscription_id']);
            $params['filter'] = new Node('filter', $idNode);
        }
        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = [];

        for ($i = 0; $i < count($xml->webspace->get->result); $i++) {
            $webspace = $xml->webspace->get->result[$i];

            if ($webspace->status == 'error') {
                throw new ApiRequestException($webspace);
            }

            $hosting = [];
            foreach ($webspace->data->hosting->children() as $host) {
                $hosting[$host->getName()] = Xml::getProperties($host);
            }


            $subscriptions = [];
            foreach ($webspace->data->subscriptions->children() as $subscription) {
                $subscriptions[] = [
                    'locked' => (bool)$subscription->locked,
                    'synchronized' => (bool)$subscription->synchronized,
                    'plan-guid' => (string)$subscription->plan->{"plan-guid"},
                ];
            }

            $result[] = [
                'id' => (string)$webspace->id,
                'status' => (string)$webspace->status,
                'subscription_status' => (int)$webspace->data->gen_info->status,
                'created' => (string)$webspace->data->gen_info->cr_date,
                'name' => (string)$webspace->data->gen_info->name,
                'owner_id' => (string)$webspace->data->gen_info->{"owner-id"},
                'hosting' => $hosting,
                'real_size' => (int)$webspace->data->gen_info->real_size,
                'dns_ip_address' => (string)$webspace->data->gen_info->dns_ip_address,
                'htype' => (string)$webspace->data->gen_info->htype,
                'subscriptions' => $subscriptions,
            ];
        }

        return $result;
    }
}
