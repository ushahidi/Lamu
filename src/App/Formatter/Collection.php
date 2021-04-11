<?php

/**
 * Ushahidi Collection Formatter
 *
 * Implements URL handling for paging parameters.
 *
 * @author     Ushahidi Team <team@ushahidi.com>
 * @package    Ushahidi\Application
 * @copyright  2014 Ushahidi
 * @license    https://www.gnu.org/licenses/agpl-3.0.html GNU Affero General Public License Version 3 (AGPL3)
 */

namespace Ushahidi\App\Formatter;

use Ushahidi\Core\Tool\Formatter\CollectionFormatter;
use Ushahidi\Core\SearchData;

class Collection extends CollectionFormatter
{
    // CollectionFormatter
    public function getPaging()
    {
        // Get paging parameters, ensuring all values are set
        $params = array_merge($this->search->getSorting(true), $this->search->getSearchFilters());

        $prev_params = $next_params = $params;
        $next_params['offset'] = $params['offset'] + $params['limit'];
        $prev_params['offset'] = $params['offset'] - $params['limit'];
        $prev_params['offset'] = $prev_params['offset'] > 0 ? $prev_params['offset'] : 0;

        // @todo inject this
        $request = app('request');

        $curr = url($request->path()) . '?' . http_build_query($params);
        $next = url($request->path()) . '?' . http_build_query($next_params);
        $prev = url($request->path()) . '?' . http_build_query($prev_params);

        return [
            'limit'       => $params['limit'],
            'offset'      => $params['offset'],
            'order'       => $params['order'],
            'orderby'     => $params['orderby'],
            'curr'        => $curr,
            'next'        => $next,
            'prev'        => $prev,
            'total_count' => $this->total
        ];
    }
}
