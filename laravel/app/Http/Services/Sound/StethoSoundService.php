<?php

namespace App\Http\Services\Sound;

use App\StethoSound;
use Carbon\Carbon;

class StethoSoundService
{
    /**
     * @var StethoSound
     */
    protected $stethoSound;

    /**
     * Injecting dependencies.
     *
     * @param StethoSound $stethoSound
     */
    public function __construct(StethoSound $stethoSound)
    {
        $this->stethoSound = $stethoSound;
    }

    /**
     * Retrieve stethoSound.
     *
     * @return obj
     */
    public function listAllStethoSounds()
    {
        return $this->stethoSound->all();
    }

    /**
     * Find stethoSound by id.
     *
     * @param int $id
     *
     * @return obj
     */
    public function findById($id)
    {
        return $this->stethoSound->find($id);
    }
}
