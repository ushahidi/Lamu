<?php

/*
 * Utility function to add cache control middleware to a route
 *
 * It takes a single parameter to specify the minimum level of caching
 * settings that should activate caching behavior for this route.
 * See config/api.php for the defined levels
 */
if (!function_exists('add_cache_control')) {

    function add_cache_control(string $route_level)
    {
        /*
         * We are choosing not to cache responses to authenticated requests for the moment,
         * focusing only on guest requests.
         *
         * In order to implement this, two middleware instantiations of cache headers are layered.
         * The first one (bottom first) adds the default caching headers. The second middleware
         * (top in the list) applies only to logged in users and sets cache forbidding values in
         * the cache-control header.
         */
        return [
            // These are parsed bottom first, so the default is to cache (if the config allows it)
            "cache.headers.ifAuth:{$route_level},api,preset/dont-cache",    # applies to api-authenticated requests
            "cache.headers.ifAuth:{$route_level},,preset/default",
        ];
    }

}
