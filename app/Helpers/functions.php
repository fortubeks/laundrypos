<?php


if (!function_exists('activeClass')) {
    function activeClass($route, $activeClass = 'active')
    {
        return request()->routeIs($route) ? $activeClass : '';
    }
}

if (!function_exists('theme_view')) {
    function theme_view($view, $data = [], $mergeData = [])
    {
        $theme = config('theme.active');
        $path = $theme . '.' . $view;

        if (view()->exists($path)) {
            return view($path, $data, $mergeData);
        }

        // fallback to default theme or flat view
        return view($view, $data, $mergeData);
    }
}

if (!function_exists('arrayToObject')) {
    function arrayToObject($d)
    {
        if (is_array($d)) {
            /*
      * Return array converted to object
      * Using __FUNCTION__ (Magic constant)
      * for recursive call
      */
            return (object) array_map(__FUNCTION__, $d);
        } else {
            // Return object
            return $d;
        }
    }
}

function laundryId()
{
    $user = auth()->user();

    if (!$user) {
        abort(401, 'Unauthorized: User not authenticated.');
    }

    if (!$user->laundry_id) {
        abort(403, 'Forbidden: User has no associated laundry.');
    }

    return $user->laundry_id;
}
