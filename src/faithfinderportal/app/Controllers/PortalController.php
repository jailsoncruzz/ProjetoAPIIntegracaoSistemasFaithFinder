<?php namespace App\Controllers;

class PortalController extends BaseController
{
    public function index()
    {
        $data['apiUrl'] = 'http://localhost/faithfindercadastro/public/index.php/api/';
        return view('busca', $data);
    }
}