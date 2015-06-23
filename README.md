Judge
=====
[![Build Status](https://travis-ci.org/chipbell4/Judge.svg?branch=develop)](https://travis-ci.org/chipbell4/Judge)
[![Coverage Status](https://coveralls.io/repos/chipbell4/Judge/badge.svg?branch=develop)](https://coveralls.io/r/chipbell4/Judge?branch=develop)

## Menu ##
* [Setup](#setup)
* [Tutorial](#tutorial)

## <a name='setup'/> Setup
Steps to set up Judge on your machine:

1. Make sure you have PHP 5.4 or greater with PDO, SQlite, and MCrypt enabled.
2. Have [Composer](http://getcomposer.org) installed and in your PATH.
3. Run ```composer install``` to install any dependencies.
4. Run ```php artisan migrate --seed``` which will setup the database, and fill with some dummy data.
5. Run ```php artisan serve``` to start the server on port 8000.

NOTE: If you are developing on the project, I'd recommend a ```composer install``` after any pull, followed by a
```php artisan migrate``` to capture any new packages/database changes that have been included in the application.

## <a name='tutorial' /> Tutorial
### Admins
A default install (even without seeding with the ```--seed``` flag) will create a user in the database with username
and password ```admin```. From here, you can log in, and create users along with contests and problem sets. Also,
you can see any submitted solutions, undo judging status for a solution, or even delete the submission if necessary.

### Judges
Users with judging permissions can view the judging page. From here, they can see a list of solutions they have judged,
and solutions awaiting judging. In order to judge a solution, the judge must first "claim" the problem. This will
prevent any other judge from changing the judged status while the current judge is editing it. When viewing the 
problem, the judge can download a solution package which contains the teams code and judging input and output.
Currently, the application relies on the judge to run the code manually. In the future, I'll improve the judge client
to deal with this better. Once the judge has ran the code and decided, he/she can set the status of the problem and
save it, which will simultaneously update the scoreboard.

Judges can also respond to messages sent by teams, or simply send a global message.
