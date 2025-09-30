<?php namespace App\Controllers;

use App\Models\UserModel;
use Google_Client;

class AuthController extends BaseController
{
    public function login()
    {
        
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/locais');
        }
        
        return view('auth/login');
    }

    public function verifyGoogleToken()
    {
        
        $googleClientId = '91311507423-aqi129op0r41muocn33itp6k52opc6sl.apps.googleusercontent.com';

        $credential = $this->request->getPost('credential');
        if (empty($credential)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Token não fornecido.']);
        }

        $client = new Google_Client(['client_id' => $googleClientId]);
        $payload = $client->verifyIdToken($credential);

        if ($payload) {
            $userModel = new UserModel();
            $googleId = $payload['sub'];

            
            $user = $userModel->where('google_id', $googleId)->first();

            if (!$user) {
                
                $userModel->insert([
                    'google_id' => $googleId,
                    'email'     => $payload['email'],
                    'name'      => $payload['name'],
                ]);
                $user = $userModel->where('google_id', $googleId)->first();
            }

            $sessionData = [
                'fk_user_id'    => $user['id'],
                'name'       => $user['name'],
                'email'      => $user['email'],
                'isLoggedIn' => true,
            ];
            session()->set($sessionData);

            return $this->response->setJSON(['success' => true, 'redirect_url' => site_url('locais')]);
        }

        return $this->response->setStatusCode(401)->setJSON(['error' => 'Token inválido.']);
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}