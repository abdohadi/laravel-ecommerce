<!doctype html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ env('APP_NAME') }}</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Montserrat%7CRoboto:300,400,700" rel="stylesheet">
        <link rel="stylesheet" href="{{ asset('css/fontawesome.min.css') }}">

        <!-- Styles -->
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/responsive.css') }}">

    </head>
    <body>
        <header class="with-background">
            <div class="top-nav container">
                <div class="logo"><a href="/">Ecommerce</a></div>

                {{-- Main menu --}}
                {{ menu('main', 'partials.menus.main') }}
            </div> <!-- end top-nav -->

            <div class="hero container">
                <div class="hero-copy">
                    <h1>Laravel Ecommerce Demo</h1>
                    <p>Includes multiple products, categories, a shopping cart, a wishlist and a checkout system with credit card and paypal integration.</p>
                    <div class="hero-buttons">
                        <a href="{{ route('shop.index') }}" class="button button-trans">Shop Now</a>
                    </div>
                </div> <!-- end hero-copy -->

                <div class="hero-image">
                    <img src="{{ asset('/images/macbook-pro-laravel.png') }}" alt="hero image">
                </div> <!-- end hero-image -->
            </div> <!-- end hero -->
        </header>

        <div class="featured-section">

            <div class="container">
                <h1 class="text-center">Laravel Ecommerce</h1>

                <p class="section-description">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Dolore vitae nisi, consequuntur illum dolores cumque pariatur quis provident deleniti nesciunt officia est reprehenderit sunt aliquid possimus temporibus enim eum hic.</p>

                <div class="text-center button-container">
                    <a href="#" class="button button-white">Featured</a>
                    <a href="#" class="button button-black">On Sale</a>
                </div>

                {{-- <div class="tabs">
                    <div class="tab">
                        Featured
                    </div>
                    <div class="tab">
                        On Sale
                    </div>
                </div> --}}

                <div class="products text-center">
                    @foreach ($products as $product)    
                        <div>
                            @include('partials/product-card')
                        </div>
                    @endforeach
                </div> <!-- end products -->

                <div class="text-center button-container">
                    <a href="{{ route('shop.index') }}" class="button button-white">View more products</a>
                </div>

            </div> <!-- end container -->

        </div> <!-- end featured-section -->

        @include('partials.footer')

        <script src="js/app.js"></script>

    </body>
</html>
