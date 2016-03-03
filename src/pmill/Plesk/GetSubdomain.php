<?php
namespace pmill\Plesk;

class GetSubdomain extends BaseRequest
{
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.0.0">
<subdomain>
    <get>
        <filter>
            <name>{NAME}</name>
        </filter>
    </get>
</subdomain>
</packet>
EOT;

    protected $default_params = array(
        'name' => null,
    );

    /**
     * Process the response from Plesk
     * @return array
     */
    protected function processResponse($xml)
    {
        $subdomain = $xml->subdomain->get->result;

        if ((string)$subdomain->status == 'error') {
            throw new ApiRequestException((string)$subdomain->errtext, (int)$subdomain->errcode);
        }

        if ((string)$subdomain->result->status == 'error') {
            throw new ApiRequestException((string)$subdomain->result->errtext, (int)$subdomain->result->errcode);
        }

        return array(
            'id' => (int)$subdomain->id,
            'status' => (string)$subdomain->status,
            'parent' => (string)$subdomain->data->parent,
            'name' => (string)$subdomain->data->name,
            'php' => (string)$this->findHostingProperty($subdomain->data, 'php'),
            'php_handler_type' => (string)$this->findHostingProperty($subdomain->data, 'php_handler_type'),
            'www_root' => (string)$this->findHostingProperty($subdomain->data, 'www_root'),
        );
    }

    /*
     * Helper function to search an XML tree for a specific property
     * @return string
     */
    protected function findHostingProperty($node, $key)
    {
        foreach ($node->children() AS $property) {
            if ($property->name == $key) {
                return (string)$property->value;
            }
        }
        return null;
    }
}
