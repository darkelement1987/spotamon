### SPOTAMON - Crowdsourced Pokemon Go map.
Demo map: (spots only remain 15 seconds) <a href="https://www.spotamon.com/demo/">here</a>
This Fork is a work in progress, and still actively under development towards new deployment.
#### Requirements

- Hosting with SSL / HTTPS
- PHP 7.x or above
- MySQL database
- cURL PHP extension
- Header Apache extension
- Composer PHP Dependency Manager (Download and install [Here](https://getcomposer.org/download/))
- Discord Application ( Quick Guide [Here](https://github.com/SinisterRectus/Discordia/wiki/Setting-up-a-Discord-application))

#### Optional Requirements

- Mailserver

#### Discord

- Join us <a href="https://discordapp.com/invite/spU9p7v">here</a>
- <a href="https://github.com/darkelement1987/spotbot">Spotamon Discord Bot</a> 

#### Install
- use terminal or Git Bash to run `.install.sh`
- Set `/core/assets/userpics/` folder permissions to 777 / 757

#### Default Login
Username: admin<br>
Password: admin

#### Webhooks (DISCORD ONLY)
- Enable cURL in your webhosting backend / WAMP / etcetera to  use webhooks.
- Edit the following lines in your config:

```ini
//Webhook Setup
$webhook_url = "https://www.yourwebhookurlgoeshere"; // <-- Webhook URL of your Discord channel
$viewurl = "https://www.spotamon.com"; // <-- Enter your FULL Spotamon url here including http:// or https:// example: https://www.mysite.com < NO BACKSLASH AT THE END!
$viewtitle = "View on Spotamon"; // <-- Text showing for the MAP-link @ Discord
```

#### Cronjob Commands
Set your Cronjob to check each minute:

Pokemon spots: `cd *path to spotamon*/core/functions/frontend/ && php spotscron.php'`<br>
Raid spots: `cd *path to spotamon*/core/functions/frontend/ && php raidcron.php'`<br>
Egg spots: `cd *path to spotamon*/core/functions/frontend/ && php eggcron.php'` <br>

### Set this Cronjob to check every day at 00:01 (AM):
Quest spots: `cd *path to spotamon*/core/functions/frontend/ && php questcron.php'`<br>

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
