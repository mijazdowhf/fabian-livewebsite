<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->decimal('price', 28, 8)->default(0);
            $table->text('description')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
        });

        DB::table('packages')->insert([
            ['name' => 'Starter', 'price' => 49.00, 'description' => 'Basic starter package', 'status' => true, 'created_at'=>now(), 'updated_at'=>now()],
            ['name' => 'Pro', 'price' => 99.00, 'description' => 'Professional package', 'status' => true, 'created_at'=>now(), 'updated_at'=>now()],
            ['name' => 'Enterprise', 'price' => 199.00, 'description' => 'Enterprise features', 'status' => true, 'created_at'=>now(), 'updated_at'=>now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('packages');
    }
};


