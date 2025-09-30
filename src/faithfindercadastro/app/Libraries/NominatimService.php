<?php namespace App\Libraries;

class NominatimService
{
    protected $baseUrl = 'https://nominatim.openstreetmap.org/search';

    /**
     * Converte um endereço em coordenadas geográficas.
     * @param array $addressData Dados do endereço (rua, numero, cidade, estado).
     * @return array|null Retorna ['lat' => ..., 'lon' => ...] ou null se não encontrar.
     */
    public function getCoordinates(array $addressData): ?array
    {
        $client = \Config\Services::curlrequest([
            'timeout' => 5,
            'http_errors' => false // Não lançar exceção para erros HTTP
        ]);

        $addressString = implode(',', [
            $addressData['rua'],
            $addressData['numero'],
            $addressData['cidade'],
            $addressData['estado']
        ]);

        try {
            $response = $client->request('GET', $this->baseUrl, [
                'query' => [
                    'q' => $addressString,
                    'format' => 'json',
                    'limit' => 1
                ],
                'headers' => [
                    'User-Agent' => 'EncontroDeFeApp/1.0' // Boa prática
                ]
            ]);

            if ($response->getStatusCode() === 200) {
                $data = json_decode($response->getBody(), true);
                if (!empty($data)) {
                    return [
                        'lat' => $data[0]['lat'],
                        'lon' => $data[0]['lon']
                    ];
                }
            }
        } catch (\Exception $e) {
            log_message('error', 'Nominatim Service Error: ' . $e->getMessage());
            return null;
        }

        return null;
    }
}