<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('bangla_name', 180)->nullable()->after('name');
        });

        $names = [
            'linen-shirt' => 'প্রতিদিনের লিনেন শার্ট',
            'studio-kurti' => 'স্টুডিও কটন কুর্তি',
            'weekend-polo' => 'উইকএন্ড নিট পোলো',
            'soft-weave-scarf' => 'নরম বুননের স্কার্ফ',
            'city-tote' => 'প্রতিদিনের টোট ব্যাগ',
            'relaxed-trouser' => 'আরামদায়ক টেইলর্ড ট্রাউজার',
        ];

        foreach ($names as $slug => $name) {
            DB::table('products')->where('slug', $slug)->whereNull('bangla_name')->update(['bangla_name' => $name]);
        }
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('bangla_name');
        });
    }
};
