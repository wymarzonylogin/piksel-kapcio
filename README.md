# Piksel Kapcio
This is a **PHP** captcha-like library.

![piksel-kapcio-title](https://wymarzonylog.in/img/github/piksel-kapcio/piksel-kapcio-title.png)

## What is it?
Are you tired of users complaining that this obscure captcha you use on your website is sooo annonying 
and hard to solve? Are you bored with all the bots being defeated by the captcha you use? 
Well, we have good news for you! **Piksel Kapcio** is now available! It's a captcha library,
but not really! It's really easy to read, both for humans and bots! 
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

Luckily you dont need to configure anything. You can see basic usage in [\WymarzonyLogin\PikselKapcio\Controller\ExampleController](https://github.com/wymarzonylogin/piksel-kapcio/blob/master/src/Controller/ExampleController.php) class.

## Configuration
Coming soon...
