<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Auto Admin Groups
    |--------------------------------------------------------------------------
    |
    | When enabled, all site admins (users with role 'moderator' or 'super-admin')
    | will automatically be added as admins to all new groups and have access
    | to all existing groups.
    |
    */
    'auto_admin_groups' => env('CHAT_AUTO_ADMIN_GROUPS', true),
];
