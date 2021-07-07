<?php

// TODO: maybe we should swap this for the standard Route::resource()
//       (not clear yet what it does differently)
if (!function_exists('resource')) {
    // Helper to generate routes similar to laravels Route::resource()
    function resource($router, $uri, $controller, array $options = [])
    {
        // Get id pattern from options, or use default
        $id = isset($options['id']) ? $options['id'] : 'id:[0-9]+';

        // Build list of methods to register routes for
        $methods = $defaults = ['index', 'store', 'show', 'update', 'destroy'];
        if (isset($options['only'])) {
            $methods = array_intersect($defaults, (array) $options['only']);
        } elseif (isset($options['except'])) {
            $methods = array_diff($defaults, (array) $options['except']);
        }

        // prefix for the routes
        $options['as'] = $options['as'] ?? str_replace('/', '.', trim($uri, ' ./'));

        // Finally register the routes
        $router->group([
            'prefix' => $uri
        ] + $options, function () use ($router, $id, $methods, $controller) {
            if (in_array('index', $methods)) {
                $router->get('/', [ 'as' => "index", 'uses' => $controller.'@index' ]);
            }

            if (in_array('store', $methods)) {
                $router->post('/', [ 'as' => "store", 'uses' => $controller.'@store' ]);
            }

            if (in_array('show', $methods)) {
                $router->get('/{'.$id.'}', [ 'as' => "show", 'uses' => $controller.'@show' ]);
            }

            if (in_array('update', $methods)) {
                $router->put('/{'.$id.'}', [ 'as' => "update", 'uses' => $controller.'@update' ]);
            }

            if (in_array('destroy', $methods)) {
                $router->delete('/{'.$id.'}', [ 'as' => "destroy", 'uses' => $controller.'@destroy' ]);
            }
        });
    }
}

if (! function_exists('config_path')) {
    /**
     * Get the configuration path.
     *
     * @param  string $path
     * @return string
     */
    function config_path($path = '')
    {
        return app()->basePath() . '/config' . ($path ? '/' . $path : $path);
    }
}
