<?php
namespace pmill\Plesk;

class ListSubscriptions extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
<webspace>
    <get>
        {FILTER}
        <dataset>
			<hosting/>
		</dataset>
    </get>
</webspace>
</packet>
EOT;

	protected $default_params = array(
		'filter'=>'<filter/>',
	);

	public function __construct($config, $params=array())
	{
		if(isset($params['client_id'])) {
			$params['filter'] = '<filter><owner-id>'.$params['client_id'].'</owner-id></filter>';
		}

		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = array();

        for ($i=0 ;$i<count($xml->webspace->get->result); $i++) {
            $webspace = $xml->webspace->get->result[$i];

			$hosting = array();
			foreach ($webspace->data->hosting->children() AS $host) {
				$hosting[$host->getName()] = $this->getProperties($host);
			}

            $result[] = array(
                'id'=>(string)$webspace->id,
                'status'=>(string)$webspace->status,
                'created'=>(string)$webspace->data->gen_info->cr_date,
                'name'=>(string)$webspace->data->gen_info->name,
                'owner_id'=>(string)$webspace->data->gen_info->{"owner-id"},
                'hosting'=>$hosting,
            );
        }

        return $result;
    }
}
