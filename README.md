### SPOTAMON - Crowdsourced Pokemon Go map.

#### Demo
Demo map <a href="http://www.spotamon.com">here</a>

#### Install
- Create Database,
- Connect to DB in config and load site, on first load it will auto create tables. 

#### Configuration

`config.php`
```php
// Connect to Database
$servername = "";
$username = "";
$password = "";
$database = "";

// Set maps default location example: 
// Example:
// $mapcenter = "51.9720526, 6.7202572";

$mapcenter = "";

//24HR-Clock (default = false = 12HR) 
$clock = "";

//Google Maps key
$gmaps= "";
```

#### Notes
- Scan location is intended for mobile use. Location on PC might be wrong!

#### Todo
- Add login/registration
- Store address to db instead of table using cache
- Make all table content sortable on frontend
- Add feedback/score system to spots. (2+ thumbsdown = remove from table/map)
- Add locales for frontend
- Add gyms
- Add quests
- Auto remove spots from db after 15 mins
- Add better GUI / responsive (mobile) design
- Add pokemon icons to <a href="http://makitweb.com/insert-image-in-dropdown-lists-with-jquery/">select2 dropdown</a>
