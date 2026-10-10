<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PatientRequest extends FormRequest
{
    // no desafio nao esta pedindo autenticacao, por isso o true
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        // remove traco ou ponto do cpf e inseri apenas numeros
        if (is_string($this->input('cpf'))) {
            $this->merge([
                'cpf' => preg_replace('/\D/', '', $this->input('cpf')),
            ]);
        }

        // da mesma forma do cpf, apenas numeros no telefone
        if (is_string($this->input('phone'))) {
            $this->merge([
                'phone' => preg_replace('/\D/', '', $this->input('phone')),
            ]);
        }
    }

    public function rules(): array
    {
        $patient = $this->route('patient');

        return [
            'name' => ['required', 'string', 'max:255'],

            'cpf' => [
                'required',
                'digits:11',
                // rule::permite editar o paciente sem acusar cpf cadastrado
                Rule::unique('patients', 'cpf')
                    ->ignore($patient?->id),
            ],

            'birth_date' => [
                'required',
                'date_format:Y-m-d',
                'before_or_equal:today',
            ],

            'phone' => [
                'nullable',
                'digits_between:10,11',
            ],

            'health_unit_id' => [
                'required',
                'integer',
                Rule::exists('health_units', 'id'),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'O nome é obrigatório.',
            'name.string' => 'Informe um nome válido.',
            'name.max' => 'O nome deve ter no máximo 255 caracteres.',

            'cpf.required' => 'O CPF é obrigatório.',
            'cpf.digits' => 'O CPF deve conter exatamente 11 dígitos.',
            'cpf.unique' => 'Este CPF já está cadastrado.',

            'birth_date.required' => 'A data de nascimento é obrigatória.',
            'birth_date.date_format' => 'Informe uma data válida no formato AAAA-MM-DD.',
            'birth_date.before_or_equal' => 'A data de nascimento não pode ser futura.',

            'phone.digits_between' => 'O telefone deve conter 10 ou 11 dígitos.',

            'health_unit_id.required' => 'Selecione uma unidade de saúde.',
            'health_unit_id.integer' => 'A unidade de saúde informada é inválida.',
            'health_unit_id.exists' => 'A unidade de saúde selecionada não existe.',
        ];
    }
}
