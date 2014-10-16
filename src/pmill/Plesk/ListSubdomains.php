<?php
namespace pmill\Plesk;

class ListSubdomains extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.2">
    <subdomain>
        <get>
            <filter/>
        </get>
    </subdomain> 
</packet>
EOT;

    protected $default_params = array(
		'filter'=>'<filter/>',
	);

	public function __construct($config, $params=array())
	{
		if(isset($params['domain'])) {
			$params['filter'] = '<filter><site-name>'.$params['domain'].'</site-name></filter>';
		}
        
        if(isset($params['site_id'])) {
			$params['filter'] = '<filter><site-id>'.$params['site_id'].'</site-id></filter>';
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
        
        foreach ($xml->subdomain->get->result AS $node) {
			$result[] = array(
				'id'=>(int)$node->id,
				'status'=>(string)$node->status,
				'parent'=>(string)$node->data->parent,
				'name'=>(string)$node->data->name,
				'php'=>(string)$this->findProperty($node->data, 'php'),
				'php_handler_type'=>(string)$this->findProperty($node->data, 'php_handler_type'),
				'www_root'=>(string)$this->findProperty($node->data, 'www_root'),
			);
        }
        return $result;
    }
}
