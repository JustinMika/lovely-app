<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('app_settings', function (Blueprint $table) {
			$table->id();

			// Informations générales
			$table->string('app_name')->default('Lovely App');
			$table->text('app_description')->nullable();
			$table->string('company_name')->nullable();
			$table->text('company_address')->nullable();
			$table->string('company_phone')->nullable();
			$table->string('company_email')->nullable();
			$table->string('app_logo')->nullable();

			// Paramètres de devise
			$table->string('currency_symbol')->default('FC');
			$table->enum('currency_position', ['before', 'after'])->default('after');

			// Paramètres de stock
			$table->integer('low_stock_threshold')->default(10);
			$table->boolean('enable_stock_alerts')->default(true);

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('app_settings');
	}
};
