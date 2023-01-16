<?php

/**
 * Define here every class you want to call to build the backend menu
 * Every class need a method make(Builder $menu)
 *
 * @see \Sebastienheyd\Boilerplate\ViewComposers\MenuComposer
 */

return [
    'providers' => [
        \Sebastienheyd\Boilerplate\Menu\Clients::class,
        \Sebastienheyd\Boilerplate\Menu\Proyects::class,
        \Sebastienheyd\Boilerplate\Menu\Tasks::class,
        \Sebastienheyd\Boilerplate\Menu\Users::class,
        \Sebastienheyd\Boilerplate\Menu\Logs::class,
       
    ]
];