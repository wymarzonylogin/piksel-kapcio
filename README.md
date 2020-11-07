# Piksel Kapcio
This is a **PHP** captcha-like library.

![piksel-kapcio-title](https://wymarzonylog.in/img/github/piksel-kapcio/piksel-kapcio-title.png)

## Advertisment
*Are you tired of users complaining that this obscure captcha you use on your website is sooo annonying 
and hard to solve? Are you bored with all the bots being defeated by the captcha you use? 
Well, we have good news for you! **Piksel Kapcio** is now available! It's a captcha library,
but not really! It's really easy to read, both for humans and bots! *
## What is it?
It's bascially a simple captcha package to use within your PHP projects.
## Disclaimer
Use at your own risk!
This was never meant to be a hard to solve captcha. I never tried it against bots, so 
I can only assume it's not really good. This was created more as an easy to configure,
easy to use, easy to solve thing, with specific esthetics applied, that would throttle human users a bit.
This works great in my use case, but it doesn't have to for you! Always pick right tool for the job.
## Installation
Easy peasy, just use composer:
```bash
composer require wymarzonylogin/piksel-kapcio
```
## Basic usage
Typical flow for captchas is as follows:
- In a captcha-protected form presented to user, include text field for his captcha answer (usually labelled as "Type in code from picture" or something like that). In our examples we will give this field name "captcha_code".
- Show captcha image somwhere in or near to the form mentioned above
- When the user submits the form, there need's to be a check, if code he provided is the one which is solution for image presented to him. If yes, process the form as valid, otherwise, reject submission and show user an error message like "Invalid code".

In other words, you need to take 3 steps to use it:
1. Create endpoint serving images to user (and also storing generated code text in session)
2. Modify your form to show image (call endpoint from step 1) and add field for user's solution for captcha (also show there error, if solution is wrong).
3. Verify submitted user's captcha solution.

### 1. Endpoint serving images
Here is the example of basic endpoint:
```php
<?php

declare(strict_types=1);

namespace YourApp\Controller;

use WymarzonyLogin\PikselKapcio\CodeManager;

class ExampleController
{
    public function serveImage()
    {
        $codeManager = new CodeManager();
        $code = $codeManager->generateCode();
        
        header('Content-Type: image/png');
        imagepng($code->getImageData());
    }
}
```
Let's assume this endpoint is called for `/captcha-image` relative URL in your app.
### 2. Your form
```html
<form>
	// Here: all your current fields
	
	<img src="/captcha-image" />
	
	// Here: display error if form was submitted and captcha solution is wrong (how to do it - depends on your templating engine)

	<label>Type in code from image above:</label>
	<input type="text" name="captcha_solution" />

	// Here: your submit button
</form>
```

If you notice that image becomes cached (after first try, same image is served to given user all the time) try appending query string containing some unique parameter to called image serving endopoint's URL.

With default configuration, served image would look something like this:

![serve-image-default](https://wymarzonylog.in/img/github/piksel-kapcio/serve-image-default.png)

### Verify submitted data
Below there's an example code for handling form submission. Do not modify code in this package's `ExampleController` - copy the code to your namespace instead and then work on it.

```php
<?php

declare(strict_types=1);

namespace YourApp\Controller;

use WymarzonyLogin\PikselKapcio\CodeManager;

class ExampleController
{    
    public function validateSubmittedForm()
    {
        $userSolution = $_POST['captcha_solution'];
        $codeManager = new CodeManager();
        
        if ($codeManager->validateCode($userSolution)) {
            //all good, process the form
        } else {
            //invalid captcha solution, show error in form etc.
        }
    }
}
```

Luckily you dont need to configure anything. You can see basic usage in [\WymarzonyLogin\PikselKapcio\Controller\ExampleController](https://github.com/wymarzonylogin/piksel-kapcio/blob/master/src/Controller/ExampleController.php) class. You can actually use this controller's `serveImage` method  for serving images, if you are fine with default configuration.

## Configuration
There are 2 things that can be configured separately: code generation and image generation.
### Code generation
You can configure text generation of the code and set your custom session key for captcha code. You can do that by providing valid `CodeManagerConfig` object as a first parameter to `CodeManager` constructor:

```php
$codeConfig = new CodeManagerConfig();
$codeManager = new CodeManager($codeConfig);
```
This parameter can be also `null` and you don't need to pass it at all.

#### Custom session keys
There is a small chance that the default session key used by the package ( `_wl_kapcio`) will collide with session keys used by other packages. In that case you will want to use a custom session key for piksel-kapcio. Other, more probable case, is that you have more than one form that you want to protect with captcha. In the latter case, each form should receive a code generated with separate session key, and should be validated with this session key used. Session key is set via constructor of `CodeManagerConfig` object:

```php
$codeConfig = new CodeManagerConfig('your_custom_session_key');
$codeManager = new CodeManager($codeConfig);
```
Please keep in mind that instance of `CodeManager` used both for generation and validation of corresponding code needs to be instantiated with exactly same session key set in passed `CodeManagerConfig` object.

#### Text generation mode
There are two modes for generating text:
- random text
- word from defined words list

By default, random text mode is used. You can set the mode like that:
```php
// random text
$codeConfig->setTextGenerationMode(CodeManagerConfig::TEXT_GENERATION_RANDOM);

// text from defined words list
$codeConfig->setTextGenerationMode(CodeManagerConfig::TEXT_GENERATION_FROM_LIST);
```
#### Random text length
If random text mode is used, the length of the generated code can be defined:

```php
$codeConfig->setRandomTextLength(10);
```
Default value is 4. Custom value needs to be in range [1,36]
#### Custom words list
There is default list of words already provided, but I'm pretty sure you will want to overwrite it. Simply pass array of words like that:
```php
$codeConfig->setWordsList(['berlin', 'test', 'your', 'words']);
```
#### Respected characters
For custom words list, for each word please do not use characters other than:
- letters from latin alphabet only (a-z)
- digits 0-9
- plus, minus, asterisk, slash (+, -, *, /)
- equal sign, question mark (=, ?)
- round brackets ()
- space

Other characters would be rendered as space (empty block)
#### Case sensitivity
Piksel Kapcio is case insensitive. Your words can contain uppercase and/or lowercase latin letters, users can provide their captcha solutions in any case as well. Code will be conisdered valid, if letter match, whatever the case (e.g. if you provide custom word 'AmsterDam', and user responds with 'amsterDAM', solution will be considered as valid).
### Image generation
#### Scale
#### Color pairs
#### Color pairs rotation


