<?php

namespace App\Controllers;

use App\Models\LocalModel;
use App\Libraries\NominatimService;
use CodeIgniter\RESTful\ResourceController;

class LocaisController extends ResourceController
{
    protected $modelName = 'App\Models\LocalModel';
    protected $format    = 'json';
    private $nominatimService;

    public function __construct()
    {
        $this->nominatimService = new NominatimService();
    }

    public function index()
    {
        $data['locais'] = $this->model
            ->where('fk_user_id', session()->get('fk_user_id'))
            ->findAll();
        return view('locais/index', $data);
    }

    public function new()
    {
        return view('locais/form');
    }

    public function create()
    {
        $data = $this->request->getPost();

        $data['fk_user_id'] = session()->get('fk_user_id');

        $coordinates = $this->nominatimService->getCoordinates([
            'rua' => $data['rua'],
            'numero' => $data['numero'],
            'cidade' => $data['cidade'],
            'estado' => $data['estado']
        ]);

        if ($coordinates) {
            $data['latitude'] = $coordinates['lat'];
            $data['longitude'] = $coordinates['lon'];
        }

        if ($this->model->insert($data)) {
            return redirect()->to('/locais')->with('message', 'Local cadastrado com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    private function _verifyOwnership($id)
    {
        $local = $this->model->find($id);
        if (!$local || $local['fk_user_id'] != session()->get('fk_user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Você não tem permissão para acessar este recurso.');
        }
        return $local;
    }


    public function edit($id = null)
    {
        $data['local'] = $this->_verifyOwnership($id);
        return view('locais/form', $data);
    }


    public function update($id = null)
    {
        $this->_verifyOwnership($id);

        $data = $this->request->getPost();

        $coordinates = $this->nominatimService->getCoordinates([
            'rua' => $data['rua'],
            'numero' => $data['numero'],
            'cidade' => $data['cidade'],
            'estado' => $data['estado']
        ]);

        if ($coordinates) {
            $data['latitude'] = $coordinates['lat'];
            $data['longitude'] = $coordinates['lon'];
        }

        $data['fk_user_id'] = session()->get('user_id');

        if ($this->model->update($id, $data)) {
            return redirect()->to('/locais')->with('message', 'Local atualizado com sucesso!');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id = null)
    {
        $this->_verifyOwnership($id);

        if ($this->model->delete($id)) {
            return redirect()->to('/locais')->with('message', 'Local excluído com sucesso!');
        }
        return redirect()->to('/locais')->with('error', 'Erro ao excluir o local.');
    }



    // Em app/Controllers/LocaisController.php

    public function apiList()
    {
        $query  = $this->request->getGet('q');
        $cidade = $this->request->getGet('cidade');
        $lat    = $this->request->getGet('lat');
        $lon    = $this->request->getGet('lon');
        $radius = 20; // Raio de busca em KM
        $builder = $this->model;

        if ($lat && $lon) {
            
            $safe_lat = (float) $lat;
            $safe_lon = (float) $lon;

            $haversine = "(
            6371 * acos(
                cos(radians({$safe_lat}))
                * cos(radians(latitude))
                * cos(radians(longitude) - radians({$safe_lon}))
                + sin(radians({$safe_lat}))
                * sin(radians(latitude))
            )
        )";

            $builder->select("*, {$haversine} AS distance", false)
                ->having('distance <=', $radius)
                ->orderBy('distance', 'ASC');
        } else {

            if ($query) {
                $words = array_filter(explode(' ', $query));
                if (!empty($words)) {
                    $builder->groupStart();
                    foreach ($words as $word) {
                        $builder->orLike('nome', $word);
                        $builder->orLike('descricao', $word);
                        $builder->orLike('bairro', $word);
                        $builder->orLike('cidade', $word);
                        $builder->orLike('rua', $word);
                        $builder->orLike('numero', $word);
                        $builder->orLike('estado', $word);
                    }
                    $builder->groupEnd();
                }
            }
            if ($cidade) {
                $builder->where('cidade', $cidade);
            }
        }

        return $this->respond($builder->findAll());
    }

    public function apiShow($id = null)
    {
        $local = $this->model->find($id);
        if ($local === null) {
            return $this->failNotFound('Local não encontrado');
        }
        return $this->respond($local);
    }
}
