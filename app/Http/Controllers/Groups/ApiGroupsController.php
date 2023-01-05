<?php

namespace App\Http\Controllers\Groups;

use App\Http\Controllers\Controller;
use App\Manager\ApiGroupsManager\ApiGroupManager;
use App\Models\Groups\Group;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiGroupsController extends Controller
{
    
    private $apiGroupManager;
    
    
    public function __construct(ApiGroupManager $apiGroupManager)
    {
        $this->apiGroupManager = $apiGroupManager;
    }


    public function groupsIndex(Request $request)
    {
        return new JsonResponse($this->apiGroupManager->obtemGroups( $request) ); 
    }

    
    public function obtemGroupPorId($id)
    {
        
        return new JsonResponse($this->apiGroupManager->obtemGroupsPorId($id));
    }

   
    public function editarGroupPorId(Request $request,$id)
    {
        return new JsonResponse($this->apiGroupManager->editarGroupPorId($request ,$id));
        
    }

   
    public function novoGroup(Request $request)
    {
        return new JsonResponse($this->apiGroupManager->criarNovaGroup($request));
    }


    public function deleteGroup($id)
    {
        return new JsonResponse($this->apiGroupManager->excluirGroupPorId($id));
    }
}
