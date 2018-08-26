<?php
namespace Spotamon;

class Discord
{
    public $Guild;
    public $Emoji;
    public $Roles;
    public $Channels;
    public $Guild_id;

    public function __construct($g_id)
    {
        global $client;
        $this->Guild_id = $g_id;
        $guild          = $client->guild->getGuild(['guild.id' => $this->Guild_id]);
        $guild          = $this->emoji($guild);
        $guild          = $this->roles($guild);
        $guild          = $this->guild($guild);
        $this->Channels = $this->channels();
        return $this;
    }

    protected function emoji($guild)
    {
        $emojis = $guild->emojis;
        unset($guild->emojis);
        $emoji = array();
        foreach ($emojis as $key => $value) {
            $emoji[$value->name]          = array();
            $emoji[$value->name]['id']    = $value->id;
            $emoji[$value->name]['isgif'] = $value->animated;
            $emoji[$value->name]['pic']   = "https://cdn.discordapp.com/emojis/" . $emoji[$value->name]['id'] . ".png";
            if ($emoji[$value->name]['isgif'] == true) {
                $emoji[$value->name]['pic'] = "https://cdn.discordapp.com/emojis/" . $emoji[$value->name]['id'] . ".gif";
            }
        }
        $this->Emoji = json_decode(json_encode($emoji));
        return $guild;
    }

    protected function channels()
    {
        global $client;
        $channels = $client->guild->getGuildChannels(['guild.id' => $this->Guild_id]);
        return $channels;
    }

    protected function roles($guild)
    {
        $role = $guild->roles;
        unset($guild->roles);
        $roles = array();
        foreach ($role as $key => $value) {
            $key                        = $value;
            $roles[$key->name]          = array();
            $roles[$key->name]['id']    = $key->id;
            $roles[$key->name]['rank']  = $key->position;
            $roles[$key->name]['color'] = "#" . substr("000000" . dechex($key->color), -6);
            $roles[$key->name]['name']  = $key->name;
        }
        $this->Roles = json_decode(json_encode($roles));
        return $guild;
    }

    protected function guild($guild)
    {
        $data         = new stdclass;
        $data->iconid = $guild->icon;
        $data->icon   = "https://cdn.discordapp.com/icons/" . $data->iconid . ".png";
        $data->name   = $guild->name;
        $data->owner  = $guild->owner_id;
        $data->id     = $this->Guild_id;
        $data->icon   = "https://cdn.discordapp.com/icons/" . $data->id . "/" . $data->iconid . ".png";
        $this->Guild  = $data;
        unset($guild);
    }
}
