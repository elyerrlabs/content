<?php
/**
 * This file is part of the Elymod package.
 *
 * It defines morph map aliases used for polymorphic relationships.
 * Instead of relying on fully qualified class names, models are
 * referenced using stable string tags.
 *
 * This approach allows internal refactoring and namespace changes
 * without breaking existing polymorphic relations.
 */

return [

  /*
  |--------------------------------------------------------------------------
  | Polymorphic Model Map
  |--------------------------------------------------------------------------
  |
  | Define a map of morph tags to their corresponding model classes.
  | The tag should be a unique, stable identifier for the model.
  |
  | Usage example:
  | - Assign a tag to the model
  | - Reference the tag in polymorphic relations instead of the class path
  |
  */

  // Example morph models
  //"elymod_model_tag_name" => "\Content\App\Models\Content",

];
