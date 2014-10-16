<?php
namespace pmill\Plesk;

class GetServicePlan extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<service-plan>
	<get>
		<filter>
			<id>{ID}</id>
		</filter>
	</get>
</service-plan>
</packet>
EOT;

	protected $default_params = array(
		'id'=>NULL,
	);

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $plan = $xml->{"service-plan"}->get->result;

        if ((string)$plan->status == 'error') {
            throw new ApiRequestException((string)$plan->errtext);
        }

        if ((string)$plan->result->status == 'error') {
            throw new ApiRequestException((string)$plan->result->errtext);
        }

        $hosting = array();
		foreach ($plan->hosting AS $host) {
			$hosting[$host->getName()] = $this->getProperties($host);
		}

        return array(
            'id'=>(string)$plan->id,
			'status'=>(string)$plan->status,
			'limits'=>array(
				'overuse'=>(string)$plan->limits->overuse,
				'max_sites'=>$this->findProperty($plan->limits, 'max_site', 'limit'),
				'max_subdomains'=>$this->findProperty($plan->limits, 'max_subdom', 'limit'),
				'max_domain_aliases'=>$this->findProperty($plan->limits, 'max_dom_aliases', 'limit'),
				'disk_space'=>$this->findProperty($plan->limits, 'disk_space', 'limit'),
				'max_traffic'=>$this->findProperty($plan->limits, 'max_traffic', 'limit'),
				'max_web_users'=>$this->findProperty($plan->limits, 'max_wu', 'limit'),
				'max_subftp_users'=>$this->findProperty($plan->limits, 'max_subftp_users', 'limit'),
				'max_databases'=>$this->findProperty($plan->limits, 'max_db', 'limit'),
				'max_mailboxes'=>$this->findProperty($plan->limits, 'max_box', 'limit'),
				'mailbox_quota'=>$this->findProperty($plan->limits, 'mbox_quota', 'limit'),
				'max_maillists'=>$this->findProperty($plan->limits, 'max_maillists', 'limit'),
				'max_webapps'=>$this->findProperty($plan->limits, 'max_webapps', 'limit'),
				'max_site_builder'=>$this->findProperty($plan->limits, 'max_site_builder', 'limit'),
				'expiration'=>$this->findProperty($plan->limits, 'expiration', 'limit'),
			),
			'log_rotation'=>array(
				'on'=>(string)$plan->{"log-rotation"}->on->{"log-condition"}->{"log-bytime"},
				'max_num_files'=>(int)$plan->{"log-rotation"}->on->{"log-max-num-files"},
				'compressed'=>(string)$plan->{"log-rotation"}->on->{"log-compress"},
			),
			'preferences'=>array(
				'stat'=>(int)$plan->preferences->stat,
				'maillists'=>(string)$plan->preferences->maillists,
				'dns_zone_type'=>(string)$plan->preferences->dns_zone_type,
			),
			'hosting'=>$hosting,
			'performance'=>array(
				'bandwidth'=>(int)$plan->performance->bandwidth,
				'max_connections'=>(int)$plan->performance->max_connections,
			),
			'permissions'=>$this->getProperties($plan->permissions, 'permission'),
        );
    }
}
