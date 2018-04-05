<?php
namespace pmill\Plesk;

class ListUsers extends BaseRequest
{
    /**
     * @var string
     */
    public $xml_packet = <<<EOT
<?xml version="1.0"?>
<packet version="1.6.7.0">
<user>
    <get>
        <filter>
            <all/>
        </filter>
        <dataset>
            <gen-info/>
            <roles/>
        </dataset>
    </get>
</user>
</packet>
EOT;

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $result = [];

        for ($i = 0; $i < count($xml->user->get->result); $i++) {
            $user = $xml->user->get->result[$i];

            $result[] = [
                'id' => (int)$user->id,
                'filter-id' => (int)$user->{'filter-id'},
                'status' => (string)$user->status,
                'login' => (string)$user->data->{'gen-info'}->login,
                'name' => (string)$user->data->{'gen-info'}->name,
                'owner-guid' => (string)$user->data->{'gen-info'}->{'owner-guid'},
                'status' => (string)$user->data->{'gen-info'}->status,
                'guid' => (string)$user->data->{'gen-info'}->guid,
                'is-built-in' => (int)$user->data->{'gen-info'}->{'is-built-in'},
                'subcription-domain-id' => (int)$user->data->{'gen-info'}->{'subcription-domain-id'},
                'email' => (string)$user->data->{'gen-info'}->email,
                'contact-info' => [
                    'company' => (string)$user->data->{'gen-info'}->{'contact-info'}->company,
                    'phone' => (string)$user->data->{'gen-info'}->{'contact-info'}->phone,
                    'fax' => (string)$user->data->{'gen-info'}->{'contact-info'}->fax,
                    'address' => (string)$user->data->{'gen-info'}->{'contact-info'}->address,
                    'city' => (string)$user->data->{'gen-info'}->{'contact-info'}->city,
                    'state' => (string)$user->data->{'gen-info'}->{'contact-info'}->state,
                    'zip' => (string)$user->data->{'gen-info'}->{'contact-info'}->zip,
                    'country' => (string)$user->data->{'gen-info'}->{'contact-info'}->country,
                    'im' => (string)$user->data->{'gen-info'}->{'contact-info'}->im,
                    'imtype' => (string)$user->data->{'gen-info'}->{'contact-info'}->imtype,
                    'comment' => (string)$user->data->{'gen-info'}->{'contact-info'}->comment,
                    'locale' => (string)$user->data->{'gen-info'}->{'contact-info'}->locale,
                ],
                'role' => (string)$user->data->roles->name,
            ];
        }

        return $result;
    }
}
