<?php
namespace pmill\Plesk;

class UpdateClient extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.3.0">
    <customer>
        <set>
            <filter>
                <login>{USERNAME}</login>
            </filter>
            <values>
            	<gen_info>
				   {NODES}
   				</gen_info>
            </values>
        </set>
    </customer>
</packet>
EOT;

    protected $default_params = array(
        'username'=>'',
        'company_name'=>'',
		'contact_name'=>'',
		'username'=>'',
		'password'=>'',
		'status'=>0,
		'phone'=>'',
		'fax'=>'',
		'email'=>'',
		'address'=>'',
		'city'=>'',
		'state'=>'',
		'post_code'=>'',
		'country'=>'',
    );

    protected $node_mapping = array(
    	'password'=>'passwd',
    	'status'=>'status',
    	'phone'=>'phone',
    	'fax'=>'fax',
		'email'=>'email',
		'address'=>'address',
		'city'=>'city',
		'state'=>'state',
		'post_code'=>'pcode',
		'country'=>'country',
    );

	public function __construct($config, $params)
	{
		$params['nodes'] = $this->generateNodeList($params);
		parent::__construct($config, $params);
    }

    /**
     * Process the response from Plesk
     * @return bool
     */
    protected function processResponse($xml)
    {
    	$result = $xml->customer->set->result;

		if ($result->status == 'error') {
			throw new ApiRequestException((string)$result->errtext);
		}

        return TRUE;
    }
}
