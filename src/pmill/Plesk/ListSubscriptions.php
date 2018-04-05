<?php
namespace pmill\Plesk;

use pmill\Plesk\Helper\Xml;

class ListSubscriptions extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<webspace>
    <get>
        <filter/>
        <dataset>
			<hosting/>
			<subscriptions/>
		</dataset>
    </get>
</webspace>
</packet>
EOT;

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = [];

        for ($i = 0; $i < count($xml->webspace->get->result); $i++) {
            $webspace = $xml->webspace->get->result[$i];
            if (isset($webspace->id)){

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
        }

        return $result;
    }
}
