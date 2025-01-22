<?php

use App\Models\PersonInCharge;
use App\Models\User;
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
        Schema::create('packing_performances', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PersonInCharge::class)->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'admin_id')->constrained('users', 'id')->nullOnDelete();
            $table->timestamp('timestamp');
            $table->float('gross_weight_kg');
            $table->unsignedBigInteger('qty_pack_a_0_2kg');
            $table->unsignedBigInteger('qty_pack_b_0_3kg');
            $table->unsignedBigInteger('qty_pack_b_0_4kg');
            $table->float('reject_kg');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('packing_performances');
    }
};
