<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\StockFifoService;
use App\Services\CartService;

class AppServiceProvider extends ServiceProvider
{
	/**
	 * Register any application services.
	 */
	public function register(): void
	{
		$this->app->singleton(StockFifoService::class, function ($app) {
			return new StockFifoService();
		});

		$this->app->singleton(CartService::class, function ($app) {
			return new CartService($app->make(StockFifoService::class));
		});
	}

	/**
	 * Bootstrap any application services.
	 */
	public function boot(): void
	{
		//
	}
}
