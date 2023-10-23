<?php

namespace App\Http\Controllers\GuiaPagamento;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\GuiaPagamento\GuiaPagamento;
use Illuminate\Support\Str;
use Validator;
use DB;
use Exception;

class GuiaPagamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $guia_pagamento = GuiaPagamento::where('entidade', 'LIKE', '%'.$search.'%')
                            ->orWhere('situacao', 'LIKE', '%'.$search.'%')
                            ->orWhere('rupe', 'LIKE', '%'.$search.'%')
                            ->orderBy('id', 'DESC')
                            ->paginate($request->per_page);
        if($guia_pagamento){
            return response()->json([
                'success' => true,
                'message' => 'Listagem de RUPE',
                'data' => $this->transformCollection($guia_pagamento)
                ]
            );
        }
    }

    private function transformCollection($guia_pagamento){

        $pagamento = $guia_pagamento->toArray();
        return [
            'total' => $pagamento['total'],
            'per_page' => intval($pagamento['per_page']),
            'current_page' => $pagamento['current_page'],
            'last_page' => $pagamento['last_page'],
            'next_page_url' => $pagamento['next_page_url'],
            'prev_page_url' => $pagamento['prev_page_url'],
            'from' => $pagamento['from'],
            'to' =>$pagamento['to'],
            'data' => array_map([$this, 'transform'], $pagamento['data'])
        ];
    }

    private function transform($guia_pagamento){
        return $guia_pagamento;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $validator = Validator::make($data, $this->rules(), $this->message());

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validação',
                    'error' =>  $validator->errors()
                ]);
            } else {

                    /*'60001 AGT Impostos',
                        '60002 AGT IVA e ADUANA',
                        '60201 Portal do Munícipe',
                        '60300 Instituto Nacional de Segurança Social',
                        '60500 MINFIN UGD (Títulos do Tesouro)'
                     */
                    $entidade = [
                        '60001',
                        '60002',
                        '60201',
                        '60300',
                        '60500'
                    ];
                    
                    $guia_pagamento = new GuiaPagamento();
                    
                    if($data['entidade'] == " " || $data['entidade'] == null){

                        $numeroAleatorio = str_pad(mt_rand(1, 999999999999), 15, '0', STR_PAD_LEFT);    
                        $rupeGerado = $entidade[2].$numeroAleatorio;

                        $guia_pagamento->entidade = $entidade[2];
                        $guia_pagamento->rupe = $rupeGerado;
                        $guia_pagamento->valor = $data['valor'];
                        $guia_pagamento->gpt = $data['gpt'];
                        $guia_pagamento->situacao = $data['situacao'];
                        $guia_pagamento->data_vencimento = $data['data_vencimento'];

                        $guia_pagamento->save();

                    }else{
                        
                        $numeroAleatorio = str_pad(mt_rand(1, 999999999999), 15, '0', STR_PAD_LEFT);    
                        $rupeGerado = $data['entidade'].$numeroAleatorio;                        
                        
                        $guia_pagamento->entidade = $data['entidade'];
                        $guia_pagamento->rupe = $rupeGerado;
                        $guia_pagamento->valor = $data['valor'];
                        $guia_pagamento->gpt = $data['gpt'];
                        $guia_pagamento->situacao = $data['situacao'];
                        $guia_pagamento->data_vencimento = $data['data_vencimento'];
                        $guia_pagamento->save();
                    }

                    return response()->json([
                        'success' => true,
                        'message' => 'Guia de Pagamento emitido com sucesso',
                        'data' => $guia_pagamento
                    ]);
                }
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Não foi possível registar',
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    //metodo de regra de validação
    public function rules()
    {
        return  [
            // 'entidade' => 'required',
            'valor' => 'required'
        ];
    }

    // metodo das mensage da regra de validação
    public function message()
    {
        return  [
            // 'entidade.required' => 'O campo entidade é obrigatório',
            'valor.required' => 'O campo valor é obrigatório'
        ];
    }
}
