### SPOTAMON - Crowdsourced Pokemon Go map.
Demo map: (spots only remain 15 seconds) <a href="https://www.spotamon.com/demo/">here</a>

#### Requirements

- Hosting with SSL / HTTPS
- PHP 5.x or above
- MySQL database
- cURL extension

#### Optional Requirements

- Mailserver

#### Discord

- Join us <a href="https://discordapp.com/invite/spU9p7v">here</a>
- <a href="https://github.com/darkelement1987/spotbot">Spotamon Discord Bot</a> 

#### Install
- Create Database,
- Connect to DB in config and load site, on first load it will auto create tables. 
- Set `/userpics/` folder permissions to 777 / 757

#### Default Login
Username: admin<br>
Password: admin

#### Webhooks (DISCORD ONLY)
- Enable cURL in your webhosting backend / WAMP / etcetera to  use webhooks.
- Edit the following lines in your config:

```ini
//Webhook Setup
$pokemon_webhook_url = ""; // <-- Webhook URL of your Pokemon Discord channel
$raid_webhook_url = ""; // <-- Webhook URL of your Raid Discord channel
$egg_webhook_url = ""; // <-- Webhook URL of your Egg Discord channel
$quest_webhook_url = ""; // <-- Webhook URL of your Quest Discord channel
$exraid_webhook_url = ""; // <-- Webhook URL of your Ex-Raid Discord channel
$gym_webhook_url = ""; // <-- Webhook URL of your Gym Discord channel
$viewurl = "https://www.spotamon.com"; // <-- Enter your FULL Spotamon url here including http:// or https:// example: https://www.mysite.com < NO BACKSLASH AT THE END!
$viewtitle = "View on Spotamon"; // <-- Text showing for the MAP-link @ Discord
```

#### Cronjob Commands
Set your Cronjob to check each minute:

Pokemon spots: `cd *path to spotamon*/frontend/ && php spotscron.php'`<br>
Raid spots: `cd *path to spotamon*/frontend/ && php raidcron.php'`<br>
Egg spots: `cd *path to spotamon*/frontend/ && php eggcron.php'` <br>

### Set this Cronjob to check every day at 00:01 (AM):
Quest spots: `cd *path to spotamon*/frontend/ && php questcron.php'`<br>

#### Importing Gym/Stop CSV's

1. First download the browser-extension '<a href="https://tampermonkey.net/">Tampermonkey</a>'.
2. In Tampermonkey go to the "Utilities"-tab and in the URL-field enter this url: `https://www.spotamon.com/gymscript.js` and click `Import -> Install`
3. Go to http://pokemongomap.info, search your location and from the top part of the  map click 'S2-Grid'. This wil open a popup-menu.
4. In this menu click 'Save stops as CSV' or 'Save gyms as CSV'.
5. Login as `admin` with password `admin` and upload the CSV's on your profile-page.

#### Cronjob Documentation

https://code.tutsplus.com/tutorials/scheduling-tasks-with-cron-jobs--net-8800<br>
https://docs.plesk.com/en-US/onyx/customer-guide/scheduling-tasks.65207/<br>
https://www.plothost.com/kb/working-cron-jobs-directadmin/<br>
https://documentation.cpanel.net/display/68Docs/Cron+Jobs

#### Make Cronjob online

https://www.setcronjob.com<br>
https://cron-job.org/en/

#### Notes
- Scan location is intended for mobile use. Location on PC might be wrong!

#### Todo
- Make all table content sortable on frontend
- Add locales for frontend
- Add quests
