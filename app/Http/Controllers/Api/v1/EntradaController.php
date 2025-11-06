<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEntradaRequest;
use App\Http\Requests\UpdateEntradaRequest;
use App\Http\Resources\V1\EntradaResource;
use App\Models\Conta;
use App\Models\Entrada;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EntradaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $page = $request->input('page',1);

        try{
            $entradas = Cache::tags(['entradas'])
            ->remember("cache_entradas_{$page}", 60, function () {
                return EntradaResource::collection(
                    Entrada::with(['user','conta'])
                    ->orderByDesc('id')
                    ->paginate(10)
                );
            });

            return response()->json($entradas, 200);
        } catch(Exception $error){

            Log::error('Ocorreu um erro: '.$error->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'registros não encontrados'
            ], 404);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreEntradaRequest $request)
    {
        DB::beginTransaction();
        try{
            $validatedData = $request->validated();
            $validatedData['user_id'] = User::all()->random()->id; //iso é só pra teste

            
            $entrada = new EntradaResource(Entrada::create($validatedData));

            Conta::where('id', $entrada->conta_id)
            ->increment('amount', $entrada->amount);

            $entrada->load(['user', 'conta']);

            DB::commit();

            Cache::tags(['entradas'])->flush();

            return response()->json($entrada, 201);
        } catch(Exception $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro do lado do servidor'
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try{
            $userId = 2;

            $entradaValid = Cache::tags(['entrada'])
            ->remember("cache_entrada_{$id}", 600, function() use($userId,$id) {
                $entradaValidated = Entrada::with(['user','conta'])
                    // ->where('user_id', $userId)
                    ->findOrFail($id);
                return new EntradaResource($entradaValidated);
            });

            return response()->json($entradaValid, 200);
        } catch(Exception $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            return response()->json([
                'status' => false,
                'message' => 'registros não encontrados'
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntradaRequest $request, string $id)
    {
        DB::beginTransaction();
        try{
            $validatedData = $request->validated();
            $entrada = Entrada::findOrFail($id);//where('user_id', 3)->find($id); //user apenas de teste ainda não temos autenticação

            if(!$entrada){
                throw new ModelNotFoundException('Entrada não encontrada ou sem acesso para este usuário');
            }

            $oldEntradaAmount = $entrada->amount;
            $oldContaId = $entrada->conta_id;

            Conta::where('id', $oldContaId)
            ->decrement('amount', $oldEntradaAmount);

            $entrada->update($validatedData);

            Conta::where('id', $entrada->conta_id)
            ->increment('amount', $entrada->amount);   

            $entrada->load(['user', 'conta']);

            DB::commit();

            Cache::tags(['entradas','entrada'])->flush();

            return response()->json(new EntradaResource($entrada), 200);
        } catch(ModelNotFoundException $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $error->getMessage(),
            ], 404);
        }catch(Exception $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro do lado do servidor'
            ], 500);
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
         DB::beginTransaction();
        try{
            $entrada = Entrada::findOrFail($id);//where('user_id', 3)->find($id); //user apenas de teste ainda não temos autenticação

            if(!$entrada){
                throw new ModelNotFoundException('Entrada não encontrada ou sem acesso para este usuário');
            }

            Conta::where('id', $entrada->conta_id)
            ->decrement('amount', $entrada->amount);

            $entrada->delete($id);

            DB::commit();

            Cache::tags(['entradas','entrada'])->flush();

            return response()->json(200);
        } catch(ModelNotFoundException $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => $error->getMessage(),
            ], 404);
        }catch(Exception $error){
            Log::error('Ocorreu um erro: '.$error->getMessage());

            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Ocorreu um erro do lado do servidor'
            ], 500);
        } 
    }
}
