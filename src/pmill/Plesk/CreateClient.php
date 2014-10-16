<?php
namespace pmill\Plesk;

class CreateClient extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<packet version="1.6.3.0">
<customer>
<add>
   <gen_info>
       {NODES}
   </gen_info>
</add>
</customer>
</packet>
EOT;

	public $id = NULL;

	protected $default_params = array(
		'company_name'=>'',
		'contact_name'=>NULL,
		'username'=>NULL,
		'password'=>NULL,
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
		'company_name'=>'cname',
		'contact_name'=>'pname',
		'username'=>'login',
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
    	$result = $xml->customer->add->result;

        if ($result->status == 'error')
            throw new ApiRequestException((string)$result->errtext);

        $this->id = (int)$result->id;
        return TRUE;
    }
}
