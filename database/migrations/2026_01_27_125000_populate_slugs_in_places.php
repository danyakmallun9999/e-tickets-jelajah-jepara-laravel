<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $places = \Illuminate\Support\Facades\DB::table('places')
            ->whereNull('slug')
            ->orWhere('slug', '')
            ->get();

        foreach ($places as $place) {
            $slug = Str::slug($place->name);

            // Ensure uniqueness
            $count = \Illuminate\Support\Facades\DB::table('places')
                ->where('slug', $slug)
                ->where('id', '!=', $place->id)
                ->count();

            if ($count > 0) {
                $slug = $slug.'-'.($count + 1);
            }

            \Illuminate\Support\Facades\DB::table('places')
                ->where('id', $place->id)
                ->update(['slug' => $slug]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No need to reverse data population
    }
};
