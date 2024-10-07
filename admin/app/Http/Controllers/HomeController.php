<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrdemServico;
use App\Models\Clientes;
use App\Models\Contatos;

class HomeController extends Controller
{
    public function pageView($routeName, $page = null)
    {
        // Construct the view name based on the provided routeName and optional page parameter
        $viewName = ($page) ? $routeName . '.' . $page : $routeName;
        // Check if the constructed view exists
        if (\View::exists($viewName)) {
            // If the view exists, return the view
            if ($routeName === 'dashboard') {
                return $this->dashboard();
                

            }
            return view($viewName);
        } else {
            // If the view doesn't exist, return a 404 error
            abort(404);
        }
    }

    public function dashboard()
    {
        $ordensDeServico = OrdemServico::with('cliente')->get();
        $googleApiKey = 'AIzaSyCWElviRT5t3A1PhEGKEId4EE2EDXOc4w4';
        $client = new \GuzzleHttp\Client();

        $localidadesOS = [];

        foreach ($ordensDeServico as $ordem) {
            $endereco = $ordem->cliente->rua . ', ' . $ordem->cliente->numero . ' - ' . $ordem->cliente->bairro . ', ' . $ordem->cliente->cidade . ' - ' . $ordem->cliente->estado . ', ' . $ordem->cliente->cep;
            $response = $client->get("https://maps.googleapis.com/maps/api/geocode/json", [
                'query' => [
                    'address' => $endereco,
                    'key' => $googleApiKey
                ]
            ]);

            $body = json_decode($response->getBody(), true);
            
            if (!empty($body['results'])) {
                $location = $body['results'][0]['geometry']['location'];
                $ordem->cliente->latitude = $location['lat'];
                $ordem->cliente->longitude = $location['lng'];

                $localidadesOS[$ordem->cliente->id] = [
                    'ordem_id' => $ordem->id,
                    'cliente_id' => $ordem->cliente->id,
                    'latitude' => $location['lat'],
                    'longitude' => $location['lng'],
                    'endereço' => $endereco,
                    'cliente' => $ordem->cliente->razao,
                ];

            }
        }

        $data = [
            'localidadesOS' => $localidadesOS,
        ];
        return view('dashboard', $data);

    }
}
