# Piksel Kapcio
This is a **PHP** captcha-like library.

![piksel-kapcio-title](https://wymarzonylog.in/img/github/piksel-kapcio/piksel-kapcio-title.png)

## Advertisment
Are you tired of users complaining that this obscure captcha you use on your website is sooo annonying 
and hard to solve? Are you bored with all the bots being defeated by the captcha you use? 
Well, we have good news for you! **Piksel Kapcio** is now available! It's a captcha library,
but not really! It's really easy to read, both for humans and bots!

By the way, ever wondered how captcha would look like on C64?
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

## Flow
You basically have two touchpoints using Piksel Kapcio.
1. Generate new code, save it in user session and serve the image.
2. Verify if code provided by user matches the code stored in session.

The first step you achieve by generating new code (generate text, store it in session and generate corresponding image data):

```php
$codeManager = new CodeManager();
$newCode = $codeManager->generateCode();
```

`$newCode` object over here is instance of `WymarzonyLogin\PikselKapcio\Code`. You can call 2 methods on instance of `Code` object:
- `getText()` - which returnes text of the code that was stored in the session - you will rather not need that. May be useful for some debugging or logging.
- `getImageData()` - returns image resource for generated text.

To serve the image, simply do this afterwards:
```php
header('Content-Type: image/png');
imagepng($code->getImageData());
```

For code verification, you simply call `validateCode` with user provided captcha solution on `CodeManager`:
```php
$codeManager = new CodeManager($codeConfig);
        
if ($codeManager->validateCode($_POST['captcha_solution'])) {
	//all good, process the form
} else {
	//invalid captcha solution, show error in form etc.
}
```

`validateCode` function returns `true` in case if user provided code is correct or false otherwise.

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
You can configure image generation of the code (the visual aspects of image representing code) by providing valid `ImageGeneratorConfig` object as a second parameter to `CodeManager` constructor:

```php
$imageConfig = new ImageGeneratorConfig();
$codeManager = new CodeManager(null, $imageConfig);

//or if you configure both code and image generation
$codeConfig = new CodeManagerConfig();
$imageConfig = new ImageGeneratorConfig();
$codeManager = new CodeManager($codeConfig, $imageConfig);
```
This parameter can be also `null` and you don't need to pass it at all.
#### Scale
All the code images are build from characters build of "pixels". By defining scale, you can set size of "pixels" in real pixels. The bigger the scale, the bigger the image and each character presented on it. Default value is 5, which means that your image would be 35 pixels tall (5 x 5 for characted height + 2 x 5 for small padding on the bottom and top).
Set your custom scale:
```php
$imageConfig = new ImageGeneratorConfig();
$imageConfig->setScale(7);
```
The above code would make each character on the generated code image be 49 x 49 real pixels.
#### Color pairs
Color pair is array of 2 hex html color codes (background and foreground color). You can define multiple color pairs. Each character on generated image will use just one color pair (picked randomly or in sequence). There is a set of few color pairs defined by default, but you are free to customize it.

This package was developed in Berlin, so lets set our color pairs to reflect german flag:
```php
$imageConfig = new ImageGeneratorConfig();
$imageConfig->setColorPairs([['000000', 'AAAAAA'], ['FF0000', 'AAAAAA'], ['FFFF00', 'AAAAAA']]);
```
First pair has black for background and grey for foreground. Second - red and grey, third - yellow and grey. Result could look like this:

![random-bg](https://wymarzonylog.in/img/github/piksel-kapcio/made-in-berlin-random-bg.png)

You can make it the other way, lets have grey backgrounds and colorful foregrounds:

```php
$imageConfig = new ImageGeneratorConfig();
$imageConfig->setColorPairs([['AAAAAA', '000000'], ['AAAAAA', 'FF0000'], ['AAAAAA', 'FFFF00']]);
```

Result could be like this:

![random-fg](https://wymarzonylog.in/img/github/piksel-kapcio/made-in-berlin-random-fg.png)

You can of course set different foreground and background colors for each pair at the same time, just like in default color pairs set.

#### Color pairs rotation
By default, color pair for each character is picked randomly from defined set of color pairs. If you wish, you can change it to sequence. Let's get back to german flag background colors. Lets define these color pairs again and set sequence rotation for color pairs.

```php
$imageConfig = new ImageGeneratorConfig();
$imageConfig->setColorPairs([['000000', 'AAAAAA'], ['FF0000', 'AAAAAA'], ['FFFF00', 'AAAAAA']]);
$imageConfig->setColorPairsRotation(ImageGeneratorConfig::COLOR_PAIRS_ROTATION_SEQUENCE);
```
The result would be now less chaotic than previously:

![sequence-bg](https://wymarzonylog.in/img/github/piksel-kapcio/made-in-berlin-sequence-bg.png)

### Full configuration example
```php
<?php

declare(strict_types=1);

namespace WymarzonyLogin\PikselKapcio\Controller;

use WymarzonyLogin\PikselKapcio\CodeManager;
use WymarzonyLogin\PikselKapcio\CodeManagerConfig;
use WymarzonyLogin\PikselKapcio\ImageGeneratorConfig;

class ExampleController
{    
    public function serveCustomizedImage()
    {
        $codeConfig = new CodeManagerConfig('your_custom_session_key');
        $codeConfig->setTextGenerationMode(CodeManagerConfig::TEXT_GENERATION_FROM_LIST);
        $codeConfig->setWordsList(['made-in-berlin', 'test', 'your', 'words']);
        
        $imageConfig = new ImageGeneratorConfig();
        $imageConfig->setScale(7);
        $imageConfig->setColorPairsRotation(ImageGeneratorConfig::COLOR_PAIRS_ROTATION_SEQUENCE);
        $imageConfig->setColorPairs([['000000', 'AAAAAA'], ['FF0000', 'AAAAAA'], ['FFFF00', 'AAAAAA']]);
        
        $codeManager = new CodeManager($codeConfig, $imageConfig);
        $code = $codeManager->generateCode();
        
        header('Content-Type: image/png');
        imagepng($code->getImageData());
    }
    
    public function validateSubmittedForm()
    {
        $userSolution = $_POST['captcha_solution'];
        
        $codeConfig = new CodeManagerConfig('your_custom_session_key');
        $codeManager = new CodeManager($codeConfig);
        
        if ($codeManager->validateCode($userSolution)) {
            //all good, process the form
        } else {
            //invalid captcha solution, show error in form etc.
        }
    }
}
```

Please note that `ImageGeneratorConfig` is not passed in form submission validation endpoint - it is not needed here, as it only affects generation of the image. What is more important - in both endpoints, `CodeManagerConfig` objects instantiated with same session key were provided to `CodeManager` constructor.