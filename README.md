Judge
=====
[![Build Status](https://travis-ci.org/chipbell4/Judge.svg?branch=develop)](https://travis-ci.org/chipbell4/Judge)

## Menu ##
* [Setup](#setup)
* [Judge Client](#judge-client)

## <a name='setup'/> Setup

Steps to set up Judge on your machine:

1. Make sure you have PHP 5.4 with PDO and SQlite enabled.
2. Have [Composer](http://getcomposer.org) installed and in your PATH.
3. Run ```composer install``` 
4. Run ```php artisan asset:publish```
5. Run ```php artisan migrate```
6. Run ```php artisan db:seed```
7. Run ```php artisan serve``` to start the server.

NOTE: For us developers, if your latest pull included a new migration, make sure that you run
```composer dumpautoload``` so that Laravel can find the new migration.


## <a name='judge-client'/> Judge Client

To use the judge client, you need to 
* ```curl``` (or ```wget```) this root url of the site: ```/judge_client.zip```. Unzip this to a directory where you want to work.
* Run ```judge setup``` and follow the prompts. You'll need to provide the api url of the site, and your api key.
* Claim a solution ```judge claim```. The client will let you know if it was successful.
* Judge the solution ```judge judge```. Redundant yes, but it automates the judging process.
* Push the results back up to the server ```judge push```. This actually saves your changes to the server and allows to claim another solution.

Some other commands you might need
* ```judge unclaim``` This essentially "gives up" the solution you are judging, allowing other judges to judge it, and allow you to judge other problems.
* ```judge override``` This allows to to override the automated judging result that the judge script calculated (for presentation errors, etc.)

This covers it. If something totally screws up when you're judging, you can complete delete the ```config.json``` file
that gets created upon setup. This file essentially stores your api key, api url, and information on the problem
you are currently judging. You'll need to check with an admin that you're no longer the owner of a solution,
and re-enter your credentials. You can, of course, manually edit your ```config.json``` to remove your judging data,
but that's pretty prone to user error (spoken from experience).
