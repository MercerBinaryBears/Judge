<?php namespace Judge\Models;

use \App;

use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableInterface;
use Carbon\Carbon as Carbon;

class User extends Base implements UserInterface, RemindableInterface
{
    protected $fillable = ['username', 'password', 'admin', 'judge', 'team', 'api_key'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password');

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->id;
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Gets the contests that a user is participating in.
     */
    public function contests()
    {
        return $this->belongsToMany('Judge\Models\Contest');
    }

    /**
     * Gets all of the solutions submitted by a user.
     */
    public function solutions()
    {
        return $this->hasMany('Judge\Models\Solution');
    }

    /**
     * Generates a random API key for a user. VERY low chance of non-uniqueness
     *
     * @param int $length The string length for the key. Default is 20.
     *
     * @return string
     */
    public static function generateApiKey($length = 20)
    {
        $time = microtime(true) * 10000;

        // reverse the string, so we get most commonly changing bit first
        // which makes the tokens easier to distinguish
        $s =  strrev(sprintf('%x', $time));

        // append random numbers on until we reach our length
        while (strlen($s) < $length) {
            $s .= sprintf('%x', rand());
        }

        // trim off the excess
        return substr($s, 0, $length);
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function sentMessages()
    {
        return $this->hasMany('Judge\Models\Message', 'sender_id');
    }
}
