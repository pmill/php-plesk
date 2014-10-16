<?php
namespace pmill\Plesk;

class ListServicePlans extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<service-plan>
	<get>
		<filter/>
	</get>
</service-plan>
</packet>
EOT;

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        for ($i=0 ;$i<count($xml->{"service-plan"}->get->result); $i++) {
            $plan = $xml->{"service-plan"}->get->result[$i];

			$hosting = array();
			foreach ($plan->hosting AS $host) {
				$hosting[$host->getName()] = $this->getProperties($host);
			}

            $result[] = array(
                'id'=>(string)$plan->id,
                'status'=>(string)$plan->status,
                'name'=>(string)$plan->name,
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
        return $result;
    }

    /*
     * Helper function to search an XML tree for a specific property
     * @return string
     */
    protected function findProperty($node, $key, $node_name='property')
    {
    	foreach ($node->children() AS $property)
    	{
    		if ($property->getName() == $node_name && $property->name == $key)
    			return (string)$property->value;
    	}
    	return NULL;
    }

    /*
	 * Helper function to convert list of xml tags into an associative array
	 * @return array
     */
    protected function getProperties($node, $node_name='property')
    {
    	$result = array();

    	foreach ($node->children() AS $property)
    	{
    		if ($property->getName() == $node_name)
    			$result[(string)$property->name] = (string)$property->value;
    	}

    	return $result;
    }
}
