<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Expense\StoreExpenseRequest;
use App\Http\Requests\Expense\UpdateExpenseRequest;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\ExpenseResource;
use App\Models\Expense;
use App\Models\User;
use App\Notifications\ExpenseNotification;
use Exception;
use Illuminate\Support\Facades\Notification;

class ExpenseController extends Controller
{

    /**
     * Cria uma nova despesa de acordo com os dados passados por parâmetro.
     * @param StoreExpenseRequest $request
     * @return mixed
     */
    public function store(StoreExpenseRequest $request): mixed
    {
        $data = $request->all();

        $expense = new Expense;
        $expense->fill($data);

        try{
            $expense->save();
            $user = User::find(auth()->user());
            Notification::send($user, (new ExpenseNotification($expense)));

            return response()->json([
                'request_status' => 'Operação realizada com sucesso.',
                'id' => $expense->id
            ], 201);
        }
        catch(Exception $e){
            return response()->json([
                'request_status' => 'Erro ao adicionar despesa.', 
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mostra dados da despesa do id passado por parâmetro, ou de todas, se não houver id especificado.
     * @param int $id
     * @return mixed
     */
    public function show(int $id = null): mixed
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
                    return response()->json([
                        'request_status' => 'Erro ao realizar operação',
                        'message' => 'Despesa não pertence a usuário logado.'
                    ], 500);
                }
            }

            return $resource;
        }
        catch(ModelNotFoundException $e){
            $id = $e->getIds();
            return response()->json([
                'request_status' => 'Erro ao realizar operação',
                'message' => "Despesa não encontrada. ID: " . implode(', ', $id)
            ], 500);
        }
    }

    /**
     * Atualiza despesa do id passado por 'path param' com dados enviados opctionais via json.
     * @param UpdateExpenseRequest $request
     * @param int $id
     * @return mixed
     */
    public function update(UpdateExpenseRequest $request, int $id): mixed
    {
        $data = $request->all();

        try{
            $expense = Expense::findOrFail($id);
            $expense->fill($data);
            $expense->save();

            return response()->json([
                'request_status' => 'Operação realizada com sucesso.',
                'id' => $expense->id
            ], 200);

        }
        catch(Exception $e){
            return response()->json([
                'request_status' => 'Erro ao editar despesa.', 
                'message' => $e->getMessage()
            ], 200);
        }
    }

    /**
     * Remove uma despesa específica do id passado por parâmetro, ou todas, se nenhum id for especificado.
     * @param int $id
     * @return mixed
     */
    public function destroy(int $id): mixed
    {
        try{
            $expense = Expense::findOrFail($id);
            if($expense->user_id == auth()->user()->id){
                $delete = $expense->delete();
                $response =  response()->json([
                    'request_status' => 'Despesa excluída com sucesso.',
                    'message' => $delete
                ], 200);
            }else{
                $response = response()->json([
                    'request_status' => 'Erro ao realizar operação',
                    'message' => 'Despesa não pertence a usuário logado.'
                ], 500);
            }
            return $response;
            
        }
        catch(ModelNotFoundException $e){
            $id = $e->getIds();
            return response()->json([
                'request_status' => 'Erro ao realizar operação',
                'message' => "Despesa não encontrada. ID: " . implode(', ', $id)
            ], 500);
        }
    }

}
