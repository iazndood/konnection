<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

use Dingo\Api\Transformer\TransformableInterface;
use LaravelBook\Ardent\Ardent;
use Zizaco\Entrust\HasRole;
use Codesleeve\Stapler\ORM\StaplerableInterface;
use Codesleeve\Stapler\ORM\EloquentTrait;

/**
 * User
 *
 * @property integer $id
 * @property string $username
 * @property string $email
 * @property string $first_name
 * @property string $last_name
 * @property string $password
 * @property string $remember_token
 * @property string $confirmation_code
 * @property boolean $confirmed
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust::role[] $roles
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereUsername($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereFirstName($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereLastName($value) 
 * @method static \Illuminate\Database\Query\Builder|\User wherePassword($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereConfirmationCode($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereConfirmed($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value) 
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value) 
 */
class User extends Ardent implements UserInterface, RemindableInterface, TransformableInterface, StaplerableInterface
{

    use UserTrait, RemindableTrait, HasRole, EloquentTrait;

    public function __construct(array $attributes = array()) {
        $this->hasAttachedFile('avatar', [
                'styles' => [
                    'medium' => '200x200',
                    'thumb' => '100x100'
                ]
            ]);

        parent::__construct($attributes);
    }

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    public $autoHydrateEntityFromInput = true;

    public $autoPurgeRedundantAttributes = true;

    public static $passwordAttributes  = array('password');

    public $autoHashPasswordAttributes = true;

    public static $relationsData = array(
        'activities' => array(self::HAS_MANY, 'Activity'),
        'events_created' => array(self::HAS_MANY, 'CalendarEvent'),
        'registrations' => array(self::HAS_MANY, 'EventRegistration'),
        'profile' => array(self::HAS_ONE, 'Profile'),
        'posts' => array(self::HAS_MANY, 'Post')
    );

    public static $rules = array(
        'username' => 'required|alpha_dash|unique:users',
        'email' => 'required|email|unique:users',
        'password' => 'required|min:6|confirmed',
        'password_confirmation' => 'min:6'
    );

    public function afterCreate($user) {
        // Create profile
        $profile = new Profile;
        $profile->id = $user->id;
        $profile->forceSave();

        // Add default role
        $memberRole = Role::find(3);
        $user->attachRole($memberRole);
    }

    protected $with = array('roles');

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = array('password', 'remember_token', 'confirmation_code');

    protected $guarded = array('id', 'confirmation_code');

    public function getTransformer()
    {
        return new UserTransformer;
    }
}
