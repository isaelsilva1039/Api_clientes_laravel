<?php

namespace App\Http\Controllers\Ocorencia;

use App\Http\Controllers\Controller;
use App\Manager\ManagerOcorrencia\ManagerVeiculo;
use App\Models\Ocorencia\Veiculo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\JsonResponse;

class VeiculoController extends Controller
{
    private $managerVeiculos; 
    
    public function __construct(ManagerVeiculo $managerVeiculo)
    {
        $this->managerVeiculos =  $managerVeiculo;
    }

    // pegar todas os veiculos cadastrado
    public function indexAction()
    {
        return Veiculo::all();
    }


    /**
     * controler para criar um novo Veiculo.
     */
    public function indexStore(Request $request)
    {
      
      $managerVeiculo = $this->managerVeiculos->novoVeiculo($request);

      return new JsonResponse($managerVeiculo);
    }

    /**
     * Display the specified resource.
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
}
