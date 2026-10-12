<?php

namespace Tests\Feature;

use App\Models\HealthUnit;
use App\Models\Patient;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PatientApiTest extends TestCase
{
    // prepara o banco de testes e mantem os testes isolados.
    use RefreshDatabase;

    public function test_lista_pacientes(): void
    {
        $response = $this->getJson('/api/patients');

        $response->assertStatus(200);
    }

    public function test_cadastra_paciente(): void
    {
        // cria uma unidade de saude necessaria para cadastrar o paciente
        $unit = HealthUnit::create([
            'name' => 'UBS Centro',
            'city' => 'Juazeiro do Norte',
        ]);

        // postJson simula o envio do formulario para a API
        $response = $this->postJson('/api/patients', [
            'name' => 'Maria da Silva',
            'cpf' => '12345678909',
            'birth_date' => '1995-05-20',
            'phone' => '88999999999',
            'health_unit_id' => $unit->id,
        ]);

        // verifica se o servidor confirmou a criacao do paciente
        $response->assertStatus(201);

        // verifica se o registro foi realmente inserido na tabela
        $this->assertDatabaseHas('patients', [
            'name' => 'Maria da Silva',
            'cpf' => '12345678909',
            'health_unit_id' => $unit->id,
        ]);
    }

    public function test_nao_permite_cpf_duplicado(): void
    {
        $unit = HealthUnit::create([
            'name' => 'UBS Centro',
            'city' => 'Juazeiro do Norte',
        ]);

        $patient = [
            'name' => 'Maria da Silva',
            'cpf' => '12345678909',
            'birth_date' => '1995-05-20',
            'phone' => '88999999999',
            'health_unit_id' => $unit->id,
        ];

        // Cadastra o primeiro paciente
        $this->postJson('/api/patients', $patient)
            ->assertStatus(201);

        // Tenta cadastrar outro paciente com o mesmo CPF
        $patient['name'] = 'Maria Oliveira';

        $response = $this->postJson('/api/patients', $patient);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['cpf']);

        // Confirma que apenas um paciente foi cadastrado
        $this->assertDatabaseCount('patients', 1);
    }

    public function test_atualiza_paciente(): void
    {
        $unit = HealthUnit::create([
            'name' => 'UBS Centro',
            'city' => 'Juazeiro do Norte',
        ]);

        $patient = Patient::create([
            'name' => 'Maria da Silva',
            'cpf' => '12345678909',
            'birth_date' => '1995-05-20',
            'phone' => '88999999999',
            'health_unit_id' => $unit->id,
        ]);

        $response = $this->putJson("/api/patients/{$patient->id}", [
            'name' => 'Maria Oliveira',
            'cpf' => '12345678909',
            'birth_date' => '1995-05-20',
            'phone' => '88988888888',
            'health_unit_id' => $unit->id,
        ]);

        $response->assertStatus(200);

        $this->assertDatabaseHas('patients', [
            'id' => $patient->id,
            'name' => 'Maria Oliveira',
            'cpf' => '12345678909',
            'phone' => '88988888888',
        ]);
    }

    public function test_exclui_paciente(): void
    {
        $unit = HealthUnit::create([
            'name' => 'UBS Centro',
            'city' => 'Juazeiro do Norte',
        ]);

        $patient = Patient::create([
            'name' => 'Maria da Silva',
            'cpf' => '12345678909',
            'birth_date' => '1995-05-20',
            'phone' => '88999999999',
            'health_unit_id' => $unit->id,
        ]);

        $response = $this->deleteJson("/api/patients/{$patient->id}");

        $response->assertStatus(204);

        // confirma que o registro nao existe mais no banco
        $this->assertDatabaseMissing('patients', [
            'id' => $patient->id,
        ]);
    }
}
