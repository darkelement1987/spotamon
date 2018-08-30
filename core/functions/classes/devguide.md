![Powered By The Flash](https://img.shields.io/badge/Powered%20By-Flash-red.svg?style=for-the-badge)

# Spotamon Dev Quick Reference

This is a quick reference for the Spotamon Security API, and PHP Bootstrapping and Function

## Composer

The Spotamon project is Composer dependent.  Composer is a PHP dependency and package manager.  It can be installed from [Here](https://getcomposer.org/download/).  Packages for composer can be found at [The Packagist](https://packagist.org/).

## Classes
Included Classes in the Project (not including dependencies:
| Class          | Namespace                             | Source      | Use                                                                 |
| -------------- | ------------------------------------- | ----------- | ------------------------------------------------------------------- |
| AntiCSRF       | \ParagonIE\AntiCSRF                   | Composer    | Form Anti-CSRF protection                                           |
| Reusable       | \ParagonIE\AntiCSRF                   | Composer    | Ajax Anti-CSRF protection                                           |
| Discord        | \ Wohali \ OAuth2 \ Client \ Provider | Composer    | Oauth2 Library                                                      |
| DiscordClient  | \Restcord                             | Composer    | Discord interaction Library(future use)                             |
| Anti-XSS       | \Voku                                 | Composer    | Anti-XSS Client                                                     |
| Authentication | \Spotamon                             | FallenFlash | Login, Registration, Logout                                         |
| Discord        | \Spotamon                             | FallenFlash | Pulling Discord Info to Web Frontend for Restcord  (future use)     |
| Members        | \Spotamon                             | FallenFlash | Pulling specific member info from Discord (future use)              |
| Oauth2         | \Spotamon                             | FallenFlash | Used for oauth2 authentication through Discord Front end for Waholi |
| Session        | \Spotamon                             | FallenFlash | Session Management and Security                                     |
| Silph          | \Spotamon                             | FallenFlash | Pulling of Trainer Card info from The Silph Road (future use)       |
| Validate       | \Spotamon                             | FallenFlash | For form Validation with XSS Protection                             |

All Classes are processed through the Composer Auto-loader so `Use` syntax is not required, though usable.  But Classes can be initiated by 
`$Class = New \Namespace\Class`

## Bootsrapped Classes and Variables

Every Directory has a file named "initiate.php"  This should be the first file included into your php files.  This will give you access to some project specific constants and variables, as well as auto intiate the csrf-protection classes, and the xss/validation classes.  The Following are included
### Files
 - Config.php
 - Composer Auto loader
 - Database Version File
### Project Constants

Back-end Constants, These contain the absolute path to the project folders for use to back-end processes, such as file inclusion.
  - S_ROOT 
  - S_CONFIG
  - S_PAGES
  - S_CLASSES
 
 Front-end Constants, these contain the paths with domain included for use in the project front end, such as images or css files
   - W_ASSETS
   - W_PAGES
   - W_CSS
   - W_JS
   - W_FUNCTIONS

Variables
  - $wroot  = containts the filepath not including domain.   `/` or if there is a subdirectory `/subdirectory/`
  - $viewurl = works much the same as wroot, but includes domain and request type
 
 ##### Some Classes have been auto intitiated per convenience
   - $csrf
	   - This is for Ajax Form CSRF protection, not quite as secure as the standard csrf library, but the standard is not compatible with multiple submits or with page refreshes.
   - $csrf2
	   - The standard library
   - $Validate
	   - The XSS/Form Validation Library

**-Note-**
The bootstrap file does initiate Session_Start() if it has not already been started, and does so through a custom class.  But this class is just static functions and is not initiated. but used as `Spotamon\Session::sessionStart()`

# Class Guide

This will include reference to the currently used Spotamon Classes.  The classes for oauth2 and authorization as well as quick references for composer classes will be included later

## CSRF

the use case for both $csrf and $csrf2 are identical, but $csrf2 cannot be used for AJAX submitted forms.

to include csrf protection into a form use the format
`$csrf->insertToken($lockto, $echo)`

where (string) $lockto is the address of where the form is being sent
and (bool) $echo is if the output should be echo'd or stored to the variable.

this creates a hidden input containing the information needed to validate the form.  for instance
```php
$path = W_ROOT . 'index.php;
$token = $csrf->insertToken($path, false);
 ```
 this would save the input to the `$token` variable for a form being sent to 
 `https://spotamon.com/index.php`
 you would then include 
 `<?=$token?>`
 at the end, but before the close, of your form to be submitted.
 $lockto is optional if your form could be set to multiple locations, and echo is default true. simplest case of using this would be 
```html
<form id="form" method="post" action="#">
	<input type="text">
	<?=$csrf->insertToken()?>
	<input type="submit"
</form>
```
To Validate this all you need to do is on whichever file is processing the form use
`$csrf->validateRequest;`


### Validate

By far the largest and most complicated of the classes I've created for Spotamon.  This is used for grabbing global variables , securing the server from xss attacks that might be used through them, and validating the data for use in the website.
Additionally it can be used to set or unset global variables.

**-Return-**
The get functions check if the $key exists, as well as if the $key is empty.
On passing validation check the asked for data will be returned.  If data fails to validate the functions of this class will return `null`

**-Variables-**
 - $Validate->aXss 
	 - this give direct access to the voku\antiXSS class
 - $Validate->data
	 - On improper validation this will contain the reasoning behind the invalidation.  otherwise it will return empty.

**-Function List-**
  - clean()
  - getPost()
  - getGet()
  - getSession()
  - setGet()
  - setPost()
  - setSession()
  - validate()

The set and get functions work the same for each with the exception of getSession() which does not include validation.

#### getPost/Get($key, $filter = null, $sanitized = true, $options = null, $default = null)
  - $key = `$_POST/GET[$key]`
  - $filter(optional) = the filter by which you wish to validate the data by, options include;
	  - username
	  - email
	  - url
	  - bool
	  - int
	  - float
	  - password
  - $sanitized(default = true) = if the data should be first run through xss sanitation before being returned for use
  - $options(optional) = what options to use for validation if you chose a $filter.
  - $default (optional) = you can set a custom return value other than null for if validation fails

#### getSession($key, $sanitize = null, $default = null)
		set up the same as the other two, but without options for validation.

#### setGet/Post/Session($key, $value = null)

if only key is provided then then this function unsets the variable
if key and value are provided, $key = $value;
both $key and $value accept arrays;
if $key is array but $value is singular, sets all items in the array with the same value
if both $key and $value are arrays, this function combines arrays and sets each 
$key with its corresponding $value. 
*note* if using both options as arrays, both arrays much have the same number of items, and corresponding $keys are set with thier $values.
ie: $key[0] = $value[0], $key[1] = $value[1], and so forth.

#### validate($data, $filter, $options)
these values are carried over from their corresponding variables in the get functions.
	- $data = data to be validated
	- $filter = filter to compare against
	- $options = options for different filters

I'm going to go over the each filters and there available options, and then i will give some examples.

  - username
	  - compares the given data against the regular expression `"/^[a-zA-Z0-9-_#]{8,20}$|(?i)admin(?-i)/"`.  this allows letters, numbers, hyphen, underscore, and the pound symbol, and must be between 8 and 20 characters.  it also allows for `admin` to allow the default user and password.
	  - no options available
  - email
	  - checks that the given data is a valid email against php's FILTER_VALIDATE_EMAIL function
	  - no options available
  - url
	  - checks that the given data is valid against php's FILTER_VALIDATE_URL function.
	  - no options available (in future will include options for validating that url belongs to a specific domain.
  - bool
	  - checks that the given data is valid against php's FILTER_VALIDATE_BOOLEAN function.
	  - no options available
  - int
	  - checks that the given data is valid against php's FILTER_VALIDATE_INT function but also allows for 0 and negative numbers.
	  - $options = array(low, high), this not only checks that the given data is an interger but also between the low and high values.   options are optional
  - float
	  - checks that the given data is valid against php's FILTER_VALIDATE_FLOAT function
	  - options are the same as int
  - password
	  - checks the given data against the regular expression `^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)[A-Za-z\d!$%@#£€*?&]{8,20}$|^admin$'` allowing for a password to be between 8 and 20 characters, containing at least 1 capital, 1 lowercase, and one number, and optional symbols.   or admin (for the default admin password)
	  - $options
		  - $options = (string), compares that two strings are identical.  for comparing a password, and confirm password to be identical
		  - $options = array('hash', 'password_hash'), compares the given data with a php PASSWORD_DEFAULT hash from the database (or other sorts)


##### Examples
------------
**get functions**
$Validate->getPost('uname', 'username')
	will return $_POST['uname'] that has been sanitized and validated
$Validate->getGet('code', null, false)
	will return $_GET['code'] that has not been filtered or validated (for use in the case where santizing the info might damage its integrity
$Validate->getPost('upass', 'password', true, ['hash', $dbpassword])
	would return $_POST['upass'] that has been compared against a hashed password from the db.   if true, will return the password, if false will return null, and the reason will be contained in $Validate->data
**set functions**
$Validate->setSession('uname', $uname)
	will set $_SESSION['uname'] = $uname
$Validate->setPost('upass')
	this would unset $_POST['upass']
	
$key = [ 'a', 'b', 'c']
$value = 1
$Validate->setSession( $key, $value)
	sets $_SESSION['a'], $_SESSION['b'], $_SESSION['c'] all with the value 1
	
$key = ['uname', 'loggedin', 'time']
$value = ['FallenFlash', true, time()]
$Validate->setSession( $key, $value)
	sets $_SESSION['uname'] = 'FallenFlash' , $_SESSION['loggedin'] = true , $_SESSION['time'] = currenttime


if you have any questions contact FallenFlash#6963 on discord
