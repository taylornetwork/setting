<?php

return [

    /*
     * The default auth guard to use
     */
    'auth_guard' => null,

    /*
     * Your user model
     */
    'user_model' => \App\User::class,

    /*
     * If you choose to modify the setting model change this
     */
    'setting_model' => \TaylorNetwork\Setting\Setting::class,

    /*
     * The foreign key on the settings table
     * Only change this if you modify the actual migration itself
     */
    'related_column' => 'user_id',

];