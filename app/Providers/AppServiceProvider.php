<?php

namespace App\Providers;

use App\Models\Ecommerce;
use App\Models\Order;
use App\Models\Review;
use App\Models\Seller;
use App\Models\Wishlist;
use App\Policies\EcommercePolicy;
use App\Policies\OrderPolicy;
use App\Policies\ReviewPolicy;
use App\Policies\SellerPolicy;
use App\Policies\WishlistPolicy;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\InventoryService;
use App\Services\OrderService;
use App\Services\Payment\PaymentManager;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(CartService::class);
        $this->app->singleton(InventoryService::class);
        $this->app->singleton(PaymentManager::class);
        $this->app->singleton(OrderService::class);
        $this->app->singleton(CheckoutService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(Ecommerce::class, EcommercePolicy::class);
        Gate::policy(Order::class, OrderPolicy::class);
        Gate::policy(Review::class, ReviewPolicy::class);
        Gate::policy(Seller::class, SellerPolicy::class);
        Gate::policy(Wishlist::class, WishlistPolicy::class);

        Gate::define('create-review', function (\App\Models\User $user, Ecommerce $book) {
            return app(ReviewPolicy::class)->create($user, $book);
        });
    }
}