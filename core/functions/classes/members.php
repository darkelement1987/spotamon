<?php
class Members
{

    public $Members;

    public function __construct()
    {
        global $client;
        global $guild_id;
        $members = $client->guild->listGuildMembers(['guild.id' => $guild_id, 'limit' => 1000]);
        $members = $this->member($members);
        return $members;
    }

    protected function member($members)
    {
        $data = array();
        foreach ($members as $key => $value) {
            $key                        = $value;
            $id                         = $key->user->id;
            $username                   = $key->user->username . '#' . $key->user->discriminator;
            $data[$id]                  = array();
            $data[$id]['id']            = $key->user->id;
            $data[$id]['username']      = $username;
            $data[$id]['basename']      = $key->user->username;
            $data[$id]['descriminator'] = $key->user->discriminator;
            $data[$id]['isbot']         = $key->user->bot;
            $data[$id]['nickname']      = $key->nick;
            $data[$id]['roles']         = $this->roles($key->roles);
        }
        $this->Members = json_decode(json_encode($data));
        return $members;
    }
    protected function roles($data)
    {
        global $roles;
        $role = array();
        foreach ($data as $index => $role) {
            foreach ($roles as $key) {
                if ($role == $key->id) {
                    $data[$index] = $key;
                }
            }
        }
        return $data;
    }
}
