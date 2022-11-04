<?php

namespace VitorF7\LoadMorePagination;

use Illuminate\Pagination\Paginator;
use Illuminate\Database\Eloquent\Model;
use VitorF7\LoadMorePagination\ModelClassRequiredException;

/**
 * LoadMorePagination Trait
 *
 * Adds an easy way to paginate a Model's Results with an
 * initial amount of items on the first page and then
 * have a different quantity for subsequent pages
 *
 * @author Vitor Faiante
 */
trait LoadMorePagination
{
    /**
     * Paginate Load More
     *
     * Paginates an eloquent Model's results with an initial quantity of items
     * on the first page and then another quantity on subsequent pages
     *
     * @param  Illuminate\Database\Query\Builder $query     [An Eloquent Query Builder]
     * @param  integer $initialQuantity [Quantity of items on the first page]
     * @param  integer $loadMore        [Quantity of items on subsequent pages]
     * @param  Illuminate\Database\Eloquent\Model $model [An Eloquent Model]
     *
     * @return array [Array of results and pagination information]
     */
    public function paginatedLoadMore($initialQuantity = 9, $loadMore = 3, $model = null)
    {
        // If no model is passed as last argument and current class is not an Eloquent Model then abort
        if ($model === null && !$this instanceof Model) {
            throw new ModelClassRequiredException;
        }

        // Model is either the model passed as last argument or the Class in case it is null
        $model = $model ?: $this;

        $page = (int) Paginator::resolveCurrentPage();
        $perPage = ($page == 1) ? $initialQuantity : $loadMore;
        $skip = ($page == 1) ? 0 : ($initialQuantity + ($loadMore * ($page - 2)));

        // Don't get the full collection in order to save PHP memory. Get count() directly from the model to get full total
        $total = $model->count();
        // Get the correct results
        $modelResults = $model->skip($skip)->take($perPage)->get();

        $lastPage = ($total > $initialQuantity) ? (int) ceil((($total - $initialQuantity) / $loadMore) + 1) : 1;
        $from = $skip + 1;
        $to = ($total > $initialQuantity) ? $skip + $perPage : $total;
        $nextPageUrl = ($page !== $lastPage) ? (string) Paginator::resolveCurrentPath() . '?page=' . ($page + 1) : null;
        $previousPageUrl = ($page !== 1) ? (string) Paginator::resolveCurrentPath() . '?page=' . ($page - 1) : null;

        return [
            'current_page' => $page,
            'per_page' => $perPage,
            'total' => $total,
            'last_page' => $lastPage,
            'from' => $from,
            'to' => $to,
            'previous_page_url' => $previousPageUrl,
            'next_page_url' => $nextPageUrl,
            'data' => $modelResults->toArray()
        ];
    }

    /**
     * Query Scope paginateLoadMore
     *
     * This method is will be used when chaining a model with other methods
     * such as with() relationship methods
     *
     * @param  Illuminate\Database\Query\Builder $query     [An Eloquent Query Builder]
     * @param  integer $initialQuantity [Quantity of items on the first page]
     * @param  integer $loadMore        [Quantity of items on subsequent pages]
     * @param  Illuminate\Database\Eloquent\Model $model [An Eloquent Model]
     *
     * @return paginateLoadMore
     */
    public function scopePaginateLoadMore($query, $initialQuantity = 9, $loadMore = 3, $model = null)
    {
        return $this->paginatedLoadMore($initialQuantity, $loadMore, $query);
    }
}
