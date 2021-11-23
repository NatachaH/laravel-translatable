<?php
namespace Nh\Translatable\Traits;

use App;

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
              $translations_to_delete = $model->translations()->withTrashed()->get()->modelKeys();
              $hasSoftDelete = in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses($model));
              $isForceDelete = !$hasSoftDelete || $model->isForceDeleting();
              $model->deleteTranslation($translations_to_delete,$isForceDelete,false);
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
                      TranslationEvent::dispatch('created', $this, $translation, 1);
                  } else if($translation->wasChanged()) {
                      TranslationEvent::dispatch('updated', $this, $translation, 1);
                  }

              }
          }
      }

      /**
      * Delete multiple translation to a model.
      * @param  array $translations_to_delete
      * @param  boolean $forceDelete
      * @param  boolean $eventPerTranslation
      * @return void
      */
     private function deleteTranslation($translations_to_delete,$forceDelete = false, $eventPerTranslation = false)
     {
         foreach($translations_to_delete as $id)
         {
            // Find the Translation (even if in trash)
            $translation = $this->translations()->withTrashed()->find($id);

            if($forceDelete)
            {
                // Force delete from the DB
                $translation->forceDelete();
            } else {
                // Soft delete from the DB
                $translation->delete();
            }

            if($eventPerTranslation)
            {
              // Fire event per transaltion
              TranslationEvent::dispatch(($forceDelete ? 'force-deleted' : 'soft-deleted'), $this, $translation, 1);
            }

         }

         if(!$eventPerTranslation)
         {
           // Fire event for global delete
           TranslationEvent::dispatch(($forceDelete ? 'force-deleted' : 'soft-deleted'), $this, null, count($translations_to_delete));
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
