<?php

namespace App\Listeners;

use App\Events\ProductDeleted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class MakeProductUnsearchable
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ProductDeleted  $event
     * @return void
     */
    public function handle(ProductDeleted $event)
    {
        $searchableProduct = $event->product->searchableProduct;
        collect([$searchableProduct])->unsearchable();
        $searchableProduct->delete();
    }
}
