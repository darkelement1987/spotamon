### SPOTAMON - Crowdsourced Pokemon Go map.

#### Demo
Demo map <a href="http://www.spotamon.com">here</a>

#### Install
- Create Database,
- Connect to DB in config and load site, on first load it will auto create tables. 
- Add 15 min interval cronjob for /frontend/spotscron.php to automatically remove spots from db.

#### Importing Gym/Stop CSV's

1. First download the browser-extension '<a href="https://tampermonkey.net/">Tampermonkey</a>'.
2. In Tampermonkey go to the "Utilities"-tab and in the URL-field enter this url: `https://www.spotamon.com/gymscript.js` and click install.
3. Go to gymhuntr.com and from the menu on the left side choose the last option, this will open a popup.
4. In this menu click 'Save stops as CSV' or 'Save gyms as CSV'.
5. Run yoururl.com/config/gymcsv.php / yoururl.com/config/stopcsv.php and import the files.
6. Delete the .php files to prevent people from uploading fake stuff.

#### Cronjob Documentation

https://code.tutsplus.com/tutorials/scheduling-tasks-with-cron-jobs--net-8800<br>
https://docs.plesk.com/en-US/onyx/customer-guide/scheduling-tasks.65207/<br>
https://www.plothost.com/kb/working-cron-jobs-directadmin/<br>
https://documentation.cpanel.net/display/68Docs/Cron+Jobs

#### Make Cronjob online

https://www.setcronjob.com<br>
https://cron-job.org/en/

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
- Make all table content sortable on frontend
- Add feedback/score system to spots. (2+ thumbsdown = remove from table/map)
- Add locales for frontend
- Add quests
- Integrate webhooks
