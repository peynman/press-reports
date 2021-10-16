<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetricsCountersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('metric_counters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('domain_id', false, true);
            $table->bigInteger('user_id', false, true)->nullable();
            $table->bigInteger('product_id', false, true)->nullable();

            $table->integer('type')->nullable();
            $table->string('group')->nullable();

            $table->string('key');
            $table->decimal('value', 12, 2);
            $table->json('data')->nullable();

            $table->timestamp('created_at');

            $table->index([
                'created_at',
                'domain_id',
                'user_id',
                'product_id',
                'type',
                'group',
                'key',
            ], 'metrics_full_idex');

            $table->foreign('domain_id')->references('id')->on('domains');
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('product_id')->references('id')->on('products');
        });

        Schema::create('metric_counter_group_pivot', function (Blueprint $table) {
            $table->bigInteger('group_id', false, true)->nullable();
            $table->bigInteger('metric_id', false, true)->nullable();

            $table->foreign('group_id')->references('id')->on('groups');
            $table->foreign('metric_id')->references('id')->on('metric_counters');
        });

        Schema::create('metric_numbers', function (Blueprint $table) {
            $table->bigInteger('num', false, true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('metric_numbers');
        Schema::dropIfExists('metrics_counter_group_pivot');
        Schema::dropIfExists('metrics_counters');
    }
}
