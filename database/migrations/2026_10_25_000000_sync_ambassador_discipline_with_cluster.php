<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('ambassadors')
            ->where(fn ($query) => $query->whereNull('discipline')->orWhere('discipline', ''))
            ->update(['discipline' => DB::raw('cluster')]);
    }

    public function down(): void
    {
        //
    }
};
