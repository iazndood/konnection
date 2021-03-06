<?php

use LaravelBook\Ardent\Ardent;
use Illuminate\Database\Eloquent\SoftDeletingTrait;

/**
 * The Activity class contains a single record of a user's activity
 * from an event.
 *
 * @property-read \User $user
 * @property float $service_hours
 * @property float $admin_hours
 * @property float $social_hours
 * @property float $mileage
 * @property mixed $notes
 * @property integer $id
 * @property integer $user_id
 * @property string $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\Activity whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereServiceHours($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereLeadershipHours($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereFellowshipHours($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereMileage($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereNotes($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Activity whereUpdatedAt($value)
 */
class Activity extends Ardent
{

    use SoftDeletingTrait;

    protected $table = 'activity_log';

    public $autoHydrateEntityFromInput = true;

    public static $relationsData = array(
        'event' => array(
            self::BELONGS_TO,
            'CalendarEvent',
            'foreignKey' => 'event_id'
        ),
        'user' => array(self::BELONGS_TO, 'User'),
    );

    public static $rules = array(
        'user_id' => 'required|exists:users,id',
        'event_id' => 'required|exists:events,id',
        'service_hours' => 'numeric',
        'admin_hours' => 'numeric',
        'social_hours' => 'numeric',
        'mileage' => 'numeric'
    );

    protected $guarded = array('id');

    /**
     * Sets service hours
     *
     * @param $value
     */
    public function setServiceHoursAttribute($value)
    {
        $this->attributes['service_hours'] = floatval($value);
    }

    /**
     * Set admin hours
     *
     * @param $value
     */
    public function setAdminHoursAttribute($value)
    {
        $this->attributes['admin_hours'] = floatval($value);
    }

    /**
     * Set social hours
     *
     * @param $value
     */
    public function setFellowshipHoursAttribute($value)
    {
        $this->attributes['social_hours'] = floatval($value);
    }

    /**
     * Set mileage
     *
     * @param $value
     */
    public function setMileageAttribute($value)
    {
        $this->attributes['mileage'] = floatval($value);
    }

    /**
     * Set activity notes/comments
     *
     * @param $value
     */
    public function setNotesAttribute($value)
    {
        $this->attributes['notes'] = strip_tags($value);
    }

    public function getTransformer()
    {
        return new ActivityTransformer;
    }
}
