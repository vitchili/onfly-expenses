<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use Exception;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class ExpenseController extends Controller
{

    /**
     * Cria uma nova despesa de acordo com os dados passados por parâmetro.
     * @param StoreExpenseRequest $request
     * @return array
     */
    public function store(StoreExpenseRequest $request): array
    {
        $data = $request->all();

        $expense = new Expense;
        $expense->fill($data);

        try{
            $expense->save();

            return [
                'request_status' => 'Operação realizada com sucesso.',
                'id' => $expense->id
            ];
        }
        catch(Exception $e){
            return [
                'request_status' => 'Erro ao adicionar despesa.', 
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Mostra dados da despesa do id passado por parâmetro, ou de todas, se não houver id especificado.
     * @param int $id
     * @return ExpenseResource|AnonymousResourceCollection|array
     */
    public function show(int $id = null): ExpenseResource|AnonymousResourceCollection|array
    {

        try{
            if(!$id){
                $expense = Expense::where('user_id', auth()->user()->id)->get();
                $resource = ExpenseResource::collection($expense);
            }else{
                $expense = Expense::findOrFail($id);
                if($expense->user_id == auth()->user()->id){
                    $resource = new ExpenseResource($expense);
                }else{
                    $resource = [
                        'request_status' => 'Erro ao realizar operação',
                        'message' => 'Despesa não pertence a usuário logado.'
                    ];
                }
            }

            return $resource;
        }
        catch(ModelNotFoundException $e){
            $id = $e->getIds();
            return [
                'request_status' => 'Erro ao realizar operação',
                'message' => "Despesa não encontrada. ID: " . implode(', ', $id)
            ];
        }
    }

    /**
     * Atualiza despesa do id passado por 'path param' com dados enviados opctionais via json.
     * @param UpdateExpenseRequest $request
     * @param int $id
     * @return array
     */
    public function update(UpdateExpenseRequest $request, int $id): array
    {
        $data = $request->all();

        try{
            $expense = Expense::findOrFail($id);
            $expense->fill($data);
            $expense->save();

            return [
                'request_status' => 'Update realizado com sucesso.',
                'id' => $expense->id
            ];

        }
        catch(Exception $e){
            return [
                'request_status' => 'Erro ao editar despesa.', 
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Remove uma despesa específica do id passado por parâmetro, ou todas, se nenhum id for especificado.
     * @param int $id
     * @return array
     */
    public function destroy(int $id = null): array
    {
        $qtDeleted = 1;
        try{
            if(!empty($id)){
                $expense = Expense::findOrFail($id);
            }else{
                $expense = Expense::where('user_id', auth()->user()->id)->get();
                $qtDeleted = $expense->count();
            }
            $expense->delete();

            return [
                'request_status' => 'Despesas excluídas com sucesso.',
                'quantidade_removidos' => $qtDeleted
        
            ];
        }
        catch(ModelNotFoundException $e){
            $id = $e->getIds();
            return [
                'request_status' => 'Erro ao realizar operação',
                'message' => "Despesa não encontrada. ID: " . implode(', ', $id)
            ];
        }
    }

}
