<?php

namespace App;
use App\Groups;
use App\Identification;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrialMembers extends Model
{
    use SoftDeletes;
    /**
     * Custom Table Name.
     *
     * @var string
     */
    protected $table = 'trialmembers';

    /**
     * Guarded Rules.
     *
     * @var array
     */
    protected $guarded = [''];

    /**
     * Many To Many Relationships.
     *
     * @return obj
     */
    public function professions()
    {
        return $this->belongsToMany(Professions::class, 'trialmembers_professions', 'trialmember_id', 'profession_id');
    }

    /**
     * One To Many Relationships.
     *
     * @return obj
     */
    public function identifications()
    {
        return $this->hasOne(Identification::class, 'trialmember_id');
    }

    /**
     * One To Many Relationships.
     *
     * @return obj
     */
    public function groups()
    {
        return $this->hasOne(Groups::class, 'trialmember_id');
    }

     /**
     * Many To Many Relationships.
     *
     * @return obj
     */
    public function services()
    {
        return $this->belongsToMany(TrialmembersServices::class, 'trialmembers_services', 'trialmember_id', 'service_id');
    }
}
