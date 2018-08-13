<?php
namespace Spotamon;

class Oauth2
{

    public $provider;
    public $token;
    public $user;
    private $sesh;

    public function __construct()
    {
        global $Validate;

        $checktoken = $Validate->getSession('token');
        $checkstate  = $Validate->getGet('state');

        $this->provider = $this->provider();
        if ($checktoken === null && $checkstate === null) {
            $this->oauthToken();
        } else if ($checkstate !== null && $checktoken === null) {
            $this->token = $this->token();
            $Validate->setSession('token', $this->token);
            unset($_GET['state']);
        }
        $seshtoken = $Validate->getSession('token');
        if ($seshtoken || isset($this->token)){
            $this->token = $seshtoken;
            $this->user = $this->discordUser($this->token);
        }
        return $this;
    }

    private function provider()
    {
        global $clientId;
        global $clientSecret;
        global $domain;

        $provider = new \Wohali\OAuth2\Client\Provider\Discord([
            'clientId'     => $clientId,
            'clientSecret' => $clientSecret,
            'redirectUri'  => $domain . "/core/functions/auth.php",
        ]);
        return $provider;
    }

    private function oauthToken()
    {
        global $Validate;

        $options = [
            'state' => $this->sesh,
            'scope' => [
                'connections',
                'email',
                'identify',
                'guilds',
                'guilds.join',
            ], // array or string
        ];
        $authUrl = $this->provider->getAuthorizationUrl($options);

        header('Location: ' . $authUrl);
    }
    public function token()
    {
        global $Validate;
        global $conn;

        $code  = $Validate->getGet('code', null, false);
        $token = $this->provider->getAccessToken('authorization_code', [
            'code' => $code,
        ]);

        return $token;
    }

    protected function discordUser($token)
    {
        $user        = $this->provider->getResourceOwner($token);
        $discorduser = $this->constructUser($user);
        return $discorduser;
    }
    public function constructUser($user)
    {
        $result           = $user;
        $result->id       = $user->getId();
        $result->basename = $user->getUsername();
        $result->discrim  = $user->getDiscriminator();
        $result->username = $result->basename . "#" . $result->discrim;
        $result->avatar   = 'https://cdn.discordapp.com/avatars/' . $user->getId() . '/' . $user->getAvatarHash() . '.png';
        $result->email    = $user->getEmail();
        $result->guilds   = $this->getGuilds($_SESSION['token']);
        return $result;
    }
    private function getGuilds($token)
    {
        $guilds = $this->getRawGuilds($token);
        $guilds = $this->processedGuilds($guilds);
        return $guilds;
    }

    private function getRawGuilds($token)
    {
        $url  = 'https://discordapp.com/api/users/@me/guilds';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "GET",
            CURLOPT_HTTPHEADER     => array(
                "Authorization: Bearer " . $token,
                "Cache-Control: no-cache",
            ),
        ));

        $response = curl_exec($curl);
        $err      = curl_error($curl);

        curl_close($curl);

        if ($err) {
            return "cURL Error #:" . $err;
        }
        return json_decode($response);
    }

    private function processedGuilds($guild)
    {
        $data = array();
        foreach ($guild as $key => $value) {
            $perm                              = $value->permissions;
            $txt                               = $this->guildPermissions($perm);
            $data[$value->name]['permissions'] = $txt;
            if ($value->icon != null) {
                $data[$value->name]['icon'] = 'https://cdn.discordapp.com/' . $value->id . '/' . $value->icon . '.png';
            }
            $data[$value->name]['owner'] = $value->owner;

        }
        $data = json_decode(json_encode($data));
        return $data;
    }

    public function guildPermissions($perm)
    {
        $data        = array();
        $permissions = array(
            // General
            'generalCreateInstantInvite' => 0x1,
            'generalKickMembers'         => 0x2,
            'generalBanMembers'          => 0x4,
            'generalAdministrator'       => 0x8,
            'generalManageChannels'      => 0x10,
            'generalManageServer'        => 0x20,
            'generalChangeNickname'      => 0x4000000,
            'generalManageNicknames'     => 0x8000000,
            'generalManageRoles'         => 0x10000000,
            'generalManageWebhooks'      => 0x20000000,
            'generalManageEmojis'        => 0x40000000,
            'generalViewAuditLog'        => 0x80,
            // Text
            'textAddReactions'           => 0x40,
            'textReadMessages'           => 0x400,
            'textSendMessages'           => 0x800,
            'textSendTTSMessages'        => 0x1000,
            'textManageMessages'         => 0x2000,
            'textEmbedLinks'             => 0x4000,
            'textAttachFiles'            => 0x8000,
            'textReadMessageHistory'     => 0x10000,
            'textMentionEveryone'        => 0x20000,
            'textUseExternalEmojis'      => 0x40000,
            // Voice
            'voiceViewChannel'           => 0x400,
            'voiceConnect'               => 0x100000,
            'voiceSpeak'                 => 0x200000,
            'voiceMuteMembers'           => 0x400000,
            'voiceDeafenMembers'         => 0x800000,
            'voiceMoveMembers'           => 0x1000000,
            'voiceUseVAD'                => 0x2000000,
        );

        foreach ($permissions as $key => $value) {
            if (($perm & $value) != 0) {
                $result = $key;
                array_push($data, $result);
            }
        }
        return $data;
    }
}
