<?php

# Ticket management
$router->group(['prefix' => 'tickets', 'middleware' => ['auth', 'csrf']], function() use ($router)
{
    $router->get('/', ['as' => 'tickets', 'uses' => 'Tickets\TicketsController@index']);
    $router->post('/', ['as' => 'tickets.store', 'uses' => 'Tickets\TicketsController@store']);

    $router->get('create', ['as' => 'tickets.create', 'uses' => 'Tickets\TicketsController@create']);

    $router->get('{ticket}', ['as' => 'tickets.show', 'uses' => 'Tickets\TicketsController@show']);
    $router->put('{ticket}', ['as' => 'tickets.update', 'uses' => 'Tickets\TicketsController@update']);
});