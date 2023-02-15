<?php

namespace Xite\Wiretables\Traits;

use Closure;
use Route;

trait HasRoute
{
    private ?Closure $routeCallback = null;
    private ?string $routeString = null;
    private ?string $routeKey = null;
    private ?array $routeQuery = null;

    public function routeUsing(callable|string $route, ?string $routeKey = null, ?array $routeQuery = []): self
    {
        if (is_callable($route)) {
            $this->routeCallback = $route;
        }

        if (is_string($route)) {
            $this->routeString = $route;
        }

        $this->routeKey = $routeKey;
        $this->routeQuery = $routeQuery;

        return $this;
    }

    private function getRouteParams($row): array
    {
        return collect($this->routeQuery)
            ->when(
                ! is_null($this->routeKey),
                fn ($collection) => $collection->when(
                    array_key_exists('filter', $this->routeQuery),
                    fn ($collection) => $collection->map(
                        fn ($query, $key) => ($key === 'filter')
                            ? $query.';'.implode(':', [$this->routeKey, $row->getKey()])
                            : $query
                    ),
                    fn ($collection) => $collection->put(
                        'filter',
                        implode(':', [$this->routeKey, $row->getKey()])
                    )
                )
            )
            ->filter()
            ->toArray();
    }

    public function getRoute($row): ?string
    {
        if (! is_null($this->routeCallback)) {
            return call_user_func($this->routeCallback, $row);
        }

        if (Route::has($this->routeString)) {
            return route(
                $this->routeString,
                $this->getRouteParams($row)
            );
        }

        return null;
    }
}
