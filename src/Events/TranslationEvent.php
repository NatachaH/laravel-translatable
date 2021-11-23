<?php

namespace Nh\Translatable\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Translation;

class TranslationEvent
{
    use Dispatchable, SerializesModels;

    /**
     * Name of the event
     * @var string
     */
    public $name;

    /**
     * The model parent of the translation
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The translation model
     * @var \Nh\Translatable\Models\Translation
     */
    public $relation;

    /**
     * The number of translation model affected
     * @var int
     */
    public $number;

    /**
     * Create a new event instance.
     * @param string  $name
     * @param \Illuminate\Database\Eloquent\Model  $model
     * @param \App\Models\Translation  $relation
     * @param int  $number
     */
    public function __construct($name,$model,$relation = null,$number = null)
    {
          $this->name     = $name;
          $this->model    = $model;
          $this->relation = is_null($relation) ? new Translation : $relation;
          $this->number   = $number;
    }
}
