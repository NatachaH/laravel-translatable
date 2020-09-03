<?php
namespace Nh\Translatable\Traits;

use App;
use Illuminate\Database\Eloquent\Builder;

use Nh\Translatable\Translation;
use Nh\Translatable\Events\TranslationEvent;

trait Translatable
{

      /**
       * Bootstrap any application services.
       *
       * @return void
       */
      protected static function bootTranslatable()
      {
          // After an item is saved
          static::saved(function ($model)
          {
              // Add some translations
              if(request()->has('translations'))
              {
                  $model->setTranslations(request()->translations);
              }
          });

          // Before an item is deleted
          static::deleting(function ($model)
          {
              $translations_to_delete = $model->translations()->withTrashed()->get();
              $hasSoftDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
              $isForceDelete = !$hasSoftDelete || $model->isForceDeleting();

              if($isForceDelete)
              {
                $model->translations()->forceDelete();
              } else {
                $model->translations()->delete();
              }
          });

          // Before an item is restored, restore the translations
          if(method_exists(static::class,'restoring'))
          {
              static::restoring(function ($model)
              {
                  $model->translations()->withTrashed()->restore();
              });
          }
      }

      /**
       * Get the model record associated with the translations.
       * @return Illuminate\Database\Eloquent\Collection
       */
      public function translations()
      {
            return $this->morphMany(Translation::class, 'translatable');
      }

      /**
       * Get a translations for a model.
       * @param string $field
       * @param string $lang
       */
      public function getTranslation($field, $lang = null)
      {
          $lang = $lang ?? App::getLocale();
          return $this->translations()->firstOrNew(['lang' => $lang, 'field' => $field])->value;
      }

      /**
       * Create or update translations for a model.
       * @param array $translations
       */
      protected function setTranslations($translations)
      {
          // Foreach array translations[lang]
          foreach ($translations as $lang => $translation) {
              // Foreach translation in these array
              foreach ($translation as $field => $value)
              {
                  // Update or create the translation
                  $translation = $this->translations()->updateOrCreate(
                      [
                        'translatable_id' => $this->id,
                        'translatable_type' => get_class($this),
                        'lang' => $lang,
                        'field' => $field
                      ],
                      [
                        'value' => $value
                      ]
                  );

                  // Dispatch the event
                  if($translation->wasRecentlyCreated)
                  {
                      TranslationEvent::dispatch('created', $this);
                  } else if($translation->wasChanged()) {
                      TranslationEvent::dispatch('updated', $this);
                  }

              }
          }
      }

      /**
       * Get the title translated.
       *
       * @return string
       */
      public function getTitleAttribute($value)
      {
          return $this->getTranslation('title') ?? $value;
      }

      /**
       * Get the subtitle translated.
       *
       * @return string
       */
      public function getSubtitleAttribute($value)
      {
          return $this->getTranslation('subtitle') ?? $value;
      }

      /**
       * Get the descritpion translated.
       *
       * @return string
       */
      public function getDescriptionAttribute($value)
      {
          return $this->getTranslation('description') ?? $value;
      }

      /**
       * Get the name translated.
       *
       * @return string
       */
      public function getNameAttribute($value)
      {
          return $this->getTranslation('name') ?? $value;
      }

}
