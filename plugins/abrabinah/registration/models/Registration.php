<?php namespace Abrabinah\Registration\Models;

use Model;

/**
 * Model
 */
class Registration extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = false;


    /**
     * @var string The database table used by the model.
     */
    public $table = 'abrabinah_registration_';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
