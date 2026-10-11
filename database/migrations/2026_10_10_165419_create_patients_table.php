
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // cria a tabela de pacientes com os campos id, name, cpf, birth_date, phone e health_unit_id.
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->char('cpf', 11)->unique();
            $table->date('birth_date');
            $table->string('phone', 20)->nullable();

            $table->foreignId('health_unit_id')
                ->constrained('health_units')
                // evita a exclusao de uma unidade de saude caso existam pacientes vinculados a ela.
                ->restrictOnDelete();

            $table->timestamps();
        });
    }

    // em caso de rollback, a tabela de pacientes será removida.
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
