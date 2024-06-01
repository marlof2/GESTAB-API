<?php

namespace App\Services;
use Illuminate\Http\Response;
use App\Models\Establishment;

class EstablishmentService
{
    protected $establishment;
    protected $pageLimit;

    public function __construct(Establishment $establishment){
            $this->establishment = $establishment;
            $this->pageLimit = 5;
    }
    public function index($request)
    {
       $data = $this->establishment->with('tipoPessoa')->orderBy('id');

        if ($request->filled('search')) {
            return response()->json($this->establishment::Filtro($request->search, $this->pageLimit));
        }
        if ($request->filled('limit')) {
            $data = ["data" => $this->establishment->get()];
            return response()->json($data, Response::HTTP_OK );
        } else {
            $data = $data->paginate($this->pageLimit);
        }

        return response()->json($data, Response::HTTP_OK );
    }
    public function store($request)
    {
        $request['cnpj'] = $this->removeCaracters($request->cnpj);
        $request['cpf'] = $this->removeCaracters($request->cpf);
        $request['phone'] = $this->removeCaracters($request->phone);

        $dataFrom = $request->all();
        try {
            $data = $this->establishment->create($dataFrom);
            return response()->json($data,Response::HTTP_CREATED ) ;
        }
        catch (\Exception $e) {
            return response()->json(["message"=>'Não foi possível cadastrar',"error"=>$e], Response::HTTP_NOT_ACCEPTABLE );
        }
    }
    public function show($id)
    {
        $data = $this->establishment->find($id);
        if(!$data){
            return response()->json(['error'=>'Dados não encontrados'],Response::HTTP_NOT_FOUND) ;
        }
        return response()->json($data,Response::HTTP_OK ) ;
    }
    public function update($request, $id)
    {
        $data = $this->establishment->find($id);
        if(!$data){
            return response()->json(['error'=>'Dados não encontrados'],Response::HTTP_NOT_FOUND) ;
        }
        $dataFrom = $request->all();
        try {
            $data->update($dataFrom);
            return response()->json($data,Response::HTTP_OK ) ;
            }
        catch (\Exception $e)
             {
             return response()->json(["message"=>'Não foi possível atualizar',"error"=>$e], Response::HTTP_NOT_ACCEPTABLE );
            }
    }

    public function destroy($id)
    {
        $data = $this->establishment->find($id);
        if(!$data){
            return response()->json(['error'=>'Dados não encontrados'],Response::HTTP_NOT_FOUND) ;
        }
         try {
                $data->delete();
                return response()->json(['success'=>'Deletado com sucesso.'],Response::HTTP_OK ) ;
         }
        catch (\Exception $e)
             {
                return response()->json(["message"=>'Não foi possível excluir',"error"=>$e], Response::HTTP_NOT_ACCEPTABLE );
            }
    }

    public function removeCaracters($value)
    {
        return preg_replace('/\D/', '', $value);
    }

}
