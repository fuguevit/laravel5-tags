<?php

return [

    /*
    |----------------------------------------------------------------------
    | TagModel
    |----------------------------------------------------------------------
    |
    | Default tag model.
    |
    */
    'tagModel' => \Fuguevit\Tags\Tag::class,

    /*
    |----------------------------------------------------------------------
    | PivotModel
    |----------------------------------------------------------------------
    |
    | Default tags pivot model.
    |
    */
    'pivotModel' => \Fuguevit\Tags\Tagged::class,

    /*
    |----------------------------------------------------------------------
    | Delimiter
    |----------------------------------------------------------------------
    |
    | Default value of tags delimiter.
    |
    */
    'delimiter' => ',',

    /*
    |----------------------------------------------------------------------
    | SlugGenerator
    |----------------------------------------------------------------------
    |
    | Default method for slug generation.
    |
    */
    'slugGenerator' => 'Illuminate\Support\Str::slug',

];