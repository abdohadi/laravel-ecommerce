<header>
    <div class="top-nav container large-devices-navbar">
        @if (request()->is('checkout') || request()->is('checkout/complete'))
	        <div class="logo logo-checkout"><a href="/">Ecommerce</a></div>
	    @else
	        <div class="logo"><a href="/">Ecommerce</a></div>
        @endif
        
        @if (! request()->is('checkout') && ! request()->is('checkout/complete'))
	        {{-- Main menu --}}
	        {{ menu('main', 'partials.menus.main') }}
	    @endif
    </div> <!-- end top-nav -->

    <div class="container small-devices-navbar">
        <div class="top-nav">
	        @if (request()->is('checkout') || request()->is('checkout/complete'))
		        <div class="logo logo-checkout"><a href="/">Ecommerce</a></div>
		    @else
		        <div class="logo"><a href="/">Ecommerce</a></div>
	        @endif    	

	        <span class="navbar-toggler-container">
	            <span></span>
	            <span></span>
	            <span></span>
	        </span>
	    </div>
    </div>
</header>