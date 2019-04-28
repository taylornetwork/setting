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
     * If you choose to modify the user setting model change this
     */
    'user_setting_model' => \TaylorNetwork\Setting\UserSetting::class,

    /*
     * The foreign key on the settings table
     * Only change this if you modify the actual migration itself
     */
    'related_column' => 'user_id',

    /*
     * If you choose to modify the app setting model change this
     */
    'app_setting_model' => \TaylorNetwork\Setting\AppSetting::class,

];
