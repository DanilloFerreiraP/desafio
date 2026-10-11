
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // cria a tabela de unidades de saúde com os campos id, name e city.
    public function up(): void
    {
        Schema::create('health_units', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('city');
            $table->timestamps();
        });
    }

    // em caso de rollback, a tabela de unidades de saúde será removida.
    public function down(): void
    {
        Schema::dropIfExists('health_units');
    }
};
