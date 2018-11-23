<?php

Namespace Spotamon;

class Meta {

    public $site;
    public $title;
    public $description;
    public $images;
    public $name;
    public $locale;

    public function __construct($site, $sitename = 'Spotamon') {

        $this->name = $sitename;
        $this->site = $site;
        $this->title = $this->title();
        $this->locale = $this->locale();
        $this->description = $this->description();
        $this->images = $this->images();
    }

    public static function createWebmanifest($site) {
    $data = array(
        "name" => "Spotamon",
        "short_name" => "Spotamon",
        "description" => "Spot a Pok&eacute;mon, Raid, Quest or more, Welcome to Spotamon! Now with trading!!!!",
        "icons" => array(
            array(
                "src" => "/core/assets/meta/android-chrome-192x192.png",
                "sizes" => "192x192",
                "type" => "image/png",
            ),
            array(
                "src" => "/core/assets/meta/android-chrome-256x256.png",
                "sizes" => "256x256",
                "type" => "image/png",
            ),
        ),
        "theme_color" => "#1386c7",
        "background_color" => "#1386c7",
        "start_url" => $site,
        "display" => "fullscreen",
    );
    $newJsonString = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    $manifest = S_ROOT . 'core/assets/meta/site.webmanifest';
    file_put_contents($manifest, $newJsonString);
}



    public function title($title = null) {

        if ($title === null ) {
        $name = $this->name;

        $ending = array();
        $ending[] = "spotting made easy";
        $ending[] = "insert cheesy slogan here";
        $ending[] = "we want YOU to spot";
        $ending[] = "easy spotting for YOU";
        $ending[] = "spotting is for all of us";
        $ending[] = "sometimes all we need is Pokémon Go";
        $ending[] = "help us become even better!";
        $ending[] = "with love and care";
        $ending[] = "sometimes we just need a life too";
        $ending[] = "for all your Pokémon pleasure";
        $ending[] = "will need to think about this one";
        $ending[] = "thank you for visiting us";
        $ending[] = "now for your local community!";
        $ending[] = "does not cost money!";
        $ending[] = "feedback is always appreciated";
        $ending[] = "DarkElement1987 approved";
        $ending[] = "<3";
        $ending[] = "PokemonGO enthausiast";
        $ending[] = "we love you too <3";
        srand((float) microtime() * 10000000); // Seed the random number generator

        // Pick a random item from the array and output it
        $ending = $ending[array_rand($ending)];
        $title = $name . ', ' . $ending;
        }
        return $title;
    }
    public function locale() {
        $data = \Locale::getDefault();
        return $data;
    }

    public function description($description = null) {
        if ($description === null) {
        $description = "Spot a Pokémon, Raid, Quest or more, Welcome to Spotamon!!! Now with trading!!!!";
        }
        return $description;

    }

    public function images() {

        $data = new \stdclass();
        $data->androidSm = "/core/assets/meta/android-chrome-192x192.png";
        $data->androidLg = "/core/assets/meta/android-chrome-256x256.png";
        $data->apple = "/core/assets/meta/apple-touch-icon.png";
        $data->faviconSm = "/core/assets/meta/favicon-16x16.png";
        $data->faviconLg = "/core/assets/meta/favicon-32x32.png";
        $data->faviconIco = "/core/assets/meta/favicon.ico";
        $data->iconPng = "/core/assets/meta/icon.png";
        $data->msTile = "/core/assets/meta/mstile-150x150.png";
        $data->ogImage = "/core/assets/meta/facebook_large.jpg";
        $data->safari = "/core/assets/meta/safari-pinned-tab.svg";
        $data->facebookLg = "/core/assets/meta/facebook_large.jpg";

        return $data;
    }


}
// todo--  create page specific includes function
?>
