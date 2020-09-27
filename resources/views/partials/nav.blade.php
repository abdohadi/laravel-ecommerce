<header>
    <div class="top-nav container">
        <div class="logo"><a href="/">Ecommerce</a></div>
        
        @if (! request()->is('checkout'))
	        {{-- Main menu --}}
	        {{ menu('main', 'partials.menus.main') }}
	    @endif
    </div> <!-- end top-nav -->
</header>