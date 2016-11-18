<?php
namespace pmill\Plesk;

class GetUser extends BaseRequest
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
			<guid>{GUID}</guid>
		</filter>
		<dataset>
			<gen-info/>
			<roles/>
		</dataset>
	</get>
</customer>
</packet>
EOT;

    /**
     * @var array
     */
    protected $default_params = [
        'guid' => null,
    ];

    /**
     * GetClient constructor.
     * @param array $config
     * @param array $params
     */
    public function __construct(array $config, $params = [])
    {
        if (isset($params['username'])) {
            $request = new ListUsers($config);
            $users = $request->process();
            foreach($users as $user){
                if ($user['login'] == $params['username'])
                    $params['guid'] = $user['guid'];
            }
        }

        parent::__construct($config, $params);
    }

    /**
     * @param $xml
     * @return array
     */
    protected function processResponse($xml)
    {
        $user = $xml->user->get->result;

        if ((string)$user->status == 'error') {
            throw new ApiRequestException($user);
        }

        if ((string)$user->result->status == 'error') {
            throw new ApiRequestException($user->result);
        }
        return [
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
}
