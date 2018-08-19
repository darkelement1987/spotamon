#!/bin/bash

echo -e "\e[36m   _____             _ "
echo "  / ____|           | |"
echo " | (___  _ __   ___ | |_ __ _ _ __ ___   ___  _ __ "
echo "  \___ \| '_ \ / _ \| __/ _\` | '_ \` _ \ / _ \| '_ \ "
echo "  ____) | |_) | (_) | || (_| | | | | | | (_) | | | | "
echo " |_____/| .__/ \___/ \__\__,_|_| |_| |_|\___/|_| |_| "
echo -e "\e[95m  _____\e[36m | |\e[95m       _        _       _   _ "
echo -e " |_   _|\e[36m|_|\e[95m      | |      | |     | | (_) "
echo "   | |  _ __  ___| |_ __ _| | __ _| |_ _  ___  _ __  "
echo "   | | | '_ \/ __| __/ _\` | |/ _\` | __| |/ _ \| '_ \ "
echo "  _| |_| | | \__ \ || (_| | | (_| | |_| | (_) | | | | "
echo " |_____|_| |_|___/\__\__,_|_|\__,_|\__|_|\___/|_| |_| "
echo -e "\e[0m"


# MAIN
echo "Welcome to Spotamon installation"

if [ "$EUID" -ne 0 ]; then
    echo
    echo "Notice: Running script with other user than root might fail!"
    read -n1 -rsp $'Press any key to continue or Ctrl+C to exit...\r'
    echo -e "\e[2K"
fi
echo

# setting directory
DIR="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && pwd )"

echo "This script will install PHP's composer as well as help set"
echo "the required variables to run Spotamon on your system"
echo
read -n1 -rsp $'Press any key to continue or Ctrl+C to exit...\n'
clear

# This script will set all the config variables for you
echo "Setting up your Environment"
echo "Checking for PHP in your systems PATH"
if hash php 2>/dev/null; then
    echo "PHP is in PATH"
    echo "Checking for Composer"
    
    COMPOSER_CMD=$(command -v composer)
    if [[ "" == "$COMPOSER_CMD" ]]
    then
        echo "Installing Composer"
        curl -sS https://getcomposer.org/installer | php -- --install-dir=bin
        COMPOSER_CMD=$(command -v composer)
    else
        echo "Updating Composer"
        $COMPOSER_CMD selfupdate
    fi
    echo "Running Composer"
    cd "$DIR" || exit
    $COMPOSER_CMD update
    $COMPOSER_CMD s -o
    echo "Done.."
else
    echo
    echo "PHP was not found in your path and Composer"
    echo "has not been installed and updated please refer to documentation"
    echo "at http://php.net/manual/en/install.php on how to install and include"
    echo "in PATH on your system. After return to your Spotamon folder and run"
    echo "\"composer install\""
    echo
fi
read -n1 -rsp $'Press any key to continue or Ctrl+C to exit...\n'
clear
# go to script dir
BASEDIR=$(dirname "$0")
cd "$BASEDIR" || exit 1

# Set some defaults
# config.php
readonly CONFIG_PHP_EX="${DIR}""/config/config.php.example"
readonly CONFIG_PHP="${DIR}""/config/config.php"
SYS_MYSQL_PATH=""
SYS_DB_NAME=""
SYS_DB_USER=""
SYS_DB_USER_HOST="localhost"
SYS_DB_PSWD=""
SYS_DB_HOST="localhost"
# .htaccess

readonly PR_EXAMPLE="${DIR}""/core/functions/protected/htaccess.example"
readonly PR_ACCESS="${DIR}""/core/functions/protected/.htaccess"
HTPASSWORD=""
HTUSER=""
SYS_HTTPD_PATH=""
HTPATH=""

# map-settings
MAP_CENTER="36.852924,-75.977982"

# discord-aouth2 settings
CLIENT_ID=""
CLIENT_SECRET=""
BOT_TOKEN=""
SERVER_ID=""


readinput() {
    local prompt_text=$1
    local default_text=$2
    read -r -e -i "$default_text" -p "$prompt_text" input
    echo "$input"
}

# Loop until answer is yes
until [ "$answer" == 'y' ]; do
    
    echo "- PATH Settings -"
    echo "If on a Linux system just type \"Linux\", If Windows enter the"
    echo "path to your Apache \"bin\" folder containing the htpasswd executable."
    echo "(example: c:/wamp64/bin/apache/apache2.4.33/bin)"
    SYS_HTTPD_PATH=$(readinput "Apache bin folder: " "$SYS_HTTPD_PATH")
    if [ "$SYS_HTTPD_PATH" != "linux" ]; then
        until [ -s "${SYS_HTTPD_PATH}""/htpasswd.exe" ]; do
            echo -e "\e[31mYour Apache path seems to be incorrect, please enter again\e[0m"
            SYS_HTTPD_PATH=$(readinput "Apache bin folder: " "$SYS_HTTPD_PATH")
        done
        echo "Please enter your MySQL bin folder"
        echo "Note: If installed with Xampp/Wamp or similar software check the"
        echo "software\'s folder.  otherwish should be located in your \"Program Files\" folder"
        echo "(example: \"C:/Program Files/MARIADB 10.2/bin\")"
        SYS_MYSQL_PATH=$(readinput "MYSQL bin folder: " "$SYS_MYSQL_PATH")
        until [ -s "$SYS_MYSQL_PATH/mysql.exe" ]; do
            echo -e "\e[31mYour Mysql path seems to be incorrect, please enter again\e[0m"
            SYS_MYSQL_PATH=$(readinput "MYSQL bin folder: " "$SYS_MYSQL_PATH")
        done
    fi
    clear
    echo "--PATH Set--"
    echo
    echo
    
    # Get input
    echo "- MySQL Settings -"
    SYS_DB_NAME=$(readinput "MySQL Database Name: " "$SYS_DB_NAME")
    SYS_DB_USER=$(readinput "MySQL User: " "$SYS_DB_USER")
    echo "This is the portion following the @ for your user"
    echo "such as '${SYS_DB_USER}'@'localhost' or '${SYS_DB_USER}'@'%'"
    SYS_DB_USER_HOST=$(readinput "MYSQL User Host: " "$SYS_DB_USER_HOST")
    SYS_DB_PSWD=$(readinput "MySQL Password: " "$SYS_DB_PSWD")
    until [ $SYS_DB_PSWD != "" ]; do
        echo -e "\e[31mSpotamon requires a database user with a password\e[0m"
        SYS_DB_PSWD=$(readinput "MySQL Password: " "$SYS_DB_PSWD")
    done
    SYS_DB_HOST=$(readinput "MySQL Host: " "$SYS_DB_HOST")
    echo
    clear
    echo "-- PATH Set --"
    echo "-- MYSQL Set--"
    echo
    echo
    echo "- Map Settings -"
    MAP_CENTER=$(readinput "Map Center Latitude: " "$MAP_CENTER")
    echo
    clear
    echo "-- PATH Set --"
    echo "-- MYSQL Set--"
    echo "-- Map Set  --"
    echo
    echo
    echo "- Discord Settings -"
    CLIENT_ID=$(readinput "Discord Client Id: " "$CLIENT_ID")
    CLIENT_SECRET=$(readinput "Discord Client Secret: " "$CLIENT_SECRET")
    BOT_TOKEN=$(readinput "Discord Bot Token: " "$BOT_TOKEN")
    SERVER_ID=$(readinput "Discord Server ID: " "$SERVER_ID")
    echo
    clear
    echo "--  PATH  Set  --"
    echo "--  MYSQL Set  --"
    echo "--   Map Set   --"
    echo "-- Discord Set --"
    echo
    echo
    echo "- Protected File Settings -"
    echo "This is different than the username and password"
    echo "you will use to login to Spotamon"
    echo "for the save location, use a location outside of your Spotamon folder"
    HTUSER=$(readinput "Admin Username: " "$HTUSER")
    HTPASSWORD=$(readinput "Admin Password: " "$HTPASSWORD")
    HTPATH=$(readinput "Password save location: " "$HTPATH")
    echo
    HTPATH_FILE="${HTPATH}""/.htpasswd"
    
    clear
    # Show input for verification
    echo
    echo "You entered the following data:"
    echo
    echo -e "\e[1m\e[34m- PATH SETTINGS -\e[0m"
    if [ "$SYS_HTTPD_PATH" = "linux" ]; then
        echo "You are on a Linux system"
    else
        echo "Your Apache bin folder is: " "$SYS_HTTPD_PATH"
        echo "Your MySQL bin folder is: " "$SYS_MYSQL_PATH"
    fi
    echo
    echo -e "\e[1m\e[34m- MySQL Settings -\e[0m"
    echo "MySQL Database Name: $SYS_DB_NAME"
    echo "MySQL User: $SYS_DB_USER"
    echo "MySQL Password: $SYS_DB_PSWD"
    echo "MySQL Host: $SYS_DB_HOST"
    echo
    echo -e "\e[1m\e[34m- Map Settings -\e[0m"
    echo "Map Center: $MAP_CENTER"
    echo
    echo -e "\e[1m\e[34m- Discord Settings -\e[0m"
    echo "Client ID: $CLIENT_ID"
    echo "Client Secret: $CLIENT_SECRET"
    echo "Bot Token: $BOT_TOKEN"
    echo "Server ID: $SERVER_ID"
    echo
    echo -e "\e[1m\e[34m- HTACCESS Settings -\e[0m"
    echo "Admin Username: $HTUSER"
    echo "Admin Password: $HTPASSWORD"
    echo "Password File Path: $HTPATH_FILE"
    echo
    
    # yes or no
    read -r -n 1 -p "Is everything correct [y/n] " answer
    if [ "$answer" != 'y' ]; then
        echo
        echo "Do your edits:"
        echo
    fi
done
echo
echo
clear
# Replace default values with the ones set above
echo "Writing $CONFIG_PHP ..."
sed -e "s/#database#/$SYS_DB_NAME/" \
-e "s/#username#/$SYS_DB_USER/" \
-e "s/#password#/$SYS_DB_PSWD/" \
-e "s/#servername#/$SYS_DB_HOST/" \
-e "s/#lat,lon#/$MAP_CENTER/" \
-e "s/#clientid#/$CLIENT_ID/" \
-e "s/#clientsecret#/$CLIENT_SECRET/" \
-e "s/#bottoken#/$BOT_TOKEN/" \
-e "s/#serverid#/$SERVER_ID/" \
"$CONFIG_PHP_EX" > "$CONFIG_PHP"

echo "Writing $PR_ACCESS ..."
sed -e "s_#htpassword#_\"$HTPATH_FILE\"_" \
"$PR_EXAMPLE" > "$PR_ACCESS"

echo "Writing $HTPATH_FILE ..."
if [ "$SYS_HTTPD_PATH" = "linux" ]; then
    MYSQL_COMMAND="mysql"
    HTPASSWD_COMMAND="htpasswd"
else
    MYSQL_COMMAND="$\"${SYS_MYSQL_PATH}""/mysql.exe\""
    HTPASSWD_COMMAND="\"${SYS_HTTPD_PATH}""/htpasswd.exe\""
fi
HTPASSCREATE="$HTPASSWD_COMMAND -c -b \"$HTPATH_FILE\" $HTUSER $HTPASSWORD"
eval "$HTPASSCREATE"

echo "Creating MySQL database"
eval ${MYSQL_COMMAND} -u $SYS_DB_USER -p${SYS_DB_PSWD} <<MYSQL
CREATE DATABASE IF NOT EXISTS ${SYS_DB_NAME} /*\!40100 DEFAULT CHARACTER SET utf8 */;
GRANT ALL PRIVILEGES ON ${SYS_DB_NAME} . * TO '${SYS_DB_USER}'@'${SYS_DB_USER_HOST}';
FLUSH PRIVILEGES;
USE ${SYS_DB_NAME};
SOURCE core/functions/protected/spotamon.sql;
SOURCE core/functions/protected/update.sql;
SOURCE core/functions/protected/pokedex.sql;
MYSQL
# Rename htaccess
echo "creating .htaccess file"
cp "${DIR}/htaccess" "${DIR}/.htaccess"

echo
echo "For even more settings have a look at config/config.php"
echo
echo "Everything is set up. Catch 'Em All!"
echo

