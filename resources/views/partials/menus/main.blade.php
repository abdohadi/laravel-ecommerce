<ul>
    @foreach($items as $menu_item)
        <li>
    		@if ($menu_item->title == 'Wishlist')
        		<a href="{{ $menu_item->link() }}" title="Wishlist">
        			<i class="far fa-heart"></i>
					@if (Cart::instance('wishlist')->count())
						<span class="wishlist-count">{{ Cart::instance('wishlist')->count() }}</span>
					@endif
        		</a> 
        	@elseif ($menu_item->title == 'Cart')
        		<a href="{{ $menu_item->link() }}" title="Cart">
					<i class="fa fa-cart-plus"></i> 
					@if (Cart::instance('default')->count())
						<span class="cart-count">{{ Cart::instance('default')->count() }}</span>
					@endif
        		</a> 
        	@else
        		<a href="{{ $menu_item->link() }}">{{ $menu_item->title }}</a>
    		@endif
        </li>
    @endforeach
</ul>