<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class UpdateExpenseRequest extends FormRequest
{

    /**
     * Seta as validações do update de despesas
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $response = '';

        if(Str::isJson($this->content)){
            $response = [
                'description' => ['nullable', 'string', 'max:191'],
                'date'        => ['nullable', 'date', 'before:today'],
                'value'       => ['nullable', 'numeric', 'min:0'],
            ];
        }else{
            $response = ['invalid_json' => ['required']];
        }

        return $response;
    }

    /**
     * Mensagens customizadas para as validações da API.
     */
    public function messages(): array
    {
        $response = '';

        if(Str::isJson($this->content)){
            $response = [
                'description.required' => 'O campo "descrição" é obrigatório.',
                'description.string'   => 'O campo "descrição" deve ser um texto.',
                'description.max'      => 'O campo "descrição" deve ter no máximo 191 caracteres.',
                'date.date'            => 'O campo "data" está inválido.',
                'date.before'          => 'O campo "data" deve ser anterior a hoje.',
                'value.required'       => 'O campo "valor" é obrigatório.',
                'value.numeric'        => 'O campo "valor" deve ser decimal.',
            ];
        }else{
            $response = ['invalid_json.required' => 'O json é inválido.'];
        }

        return $response;
    }
    
}
