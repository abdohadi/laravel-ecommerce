<header>
    <div class="top-nav container">
        <div class="logo"><a href="/">Laravel Ecommerce</a></div>
        @if (! request()->is('checkout'))
            @include('partials.right-nav-list')
        @endif
    </div> <!-- end top-nav -->
</header>