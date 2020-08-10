<ul>
	<li><a href="{{ route('shop.index') }}">Shop</a></li>
	<li><a href="#">About</a></li>
	<li><a href="#">Blog</a></li>
	<li>
		<a href="{{ route('wishlist.index') }}" title="Wishlist">
			<i class="far fa-heart"></i>
			@if (Cart::instance('wishlist')->count())
				<span class="wishlist-count">{{ Cart::instance('wishlist')->count() }}</span>
			@endif
		</a>
	</li>
	<li>
		<a href="{{ route('cart.index') }}" title="Cart">
			<i class="fa fa-cart-plus"></i> 
			@if (Cart::instance('default')->count())
				<span class="cart-count">{{ Cart::instance('default')->count() }}</span>
			@endif
		</a>
	</li>
</ul>