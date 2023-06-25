<?php

namespace App\Http\Requests\Expense;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreExpenseRequest extends FormRequest
{

    /**
     * Seta as validações do store de despesas
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $response = '';

        if(Str::isJson($this->content)){
            $response = [
                'description' => ['required', 'string', 'max:191'],
                'date'        => ['required', 'date', 'before:today'],
                'value'       => ['required', 'numeric', 'min:0'],
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
                'value.required'       => 'O campo "valor" é obrigatório.',
                'value.numeric'        => 'O campo "valor" deve ser decimal.',
            ];
        }else{
            $response = ['invalid_json.required' => 'O json é inválido.'];
        }

        return $response;
    }
    
}
