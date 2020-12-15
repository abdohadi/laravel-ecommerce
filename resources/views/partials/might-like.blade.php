<div class="might-like-section">
    <div class="container">
        <h2>You might also like...</h2>
        <div class="might-like-grid">
            @foreach ($mightAlsoLike as $product)
                @include('partials/product-card')
            @endforeach
        </div>
    </div>
</div>
