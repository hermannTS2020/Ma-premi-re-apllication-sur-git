<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;

class Connexion extends Controller{
    public function index()
    {
        helper(['form']);
        echo view('Connexion/login');
    } 

    public function auth(){
        $session = session(); // utilisation des sessions
        $model = new UserModel(); // on charge le model
        $email = $this->request->getVar('email'); // on recupère la valeur du champ email
        $password = $this->request->getVar('password');
        $data = $model->where('user_email', $email)->first(); //recupere l'utilisateur ayant l'email entrée
        //var_dump($data); die;
        if($data){
            $pass = $data['user_password'];
            $verify_pass = password_verify($password, $pass);
            if($verify_pass){
                $ses_data = [
                    'user_id'       => $data['user_id'],
                    'user_name'     => $data['user_name'],
                    'user_email'    => $data['user_email'],
                    'logged_in'     => TRUE
                ];
                $session->set($ses_data); // on sauvegarde les données du user connecté dans la session
                //return redirect()->to('/dashboard');
                return redirect()->to(site_url('dashboard'));
            }else{
                $session->setFlashdata('msg', 'Wrong Password');
                //return redirect()->to('/Connexion');
                return redirect()->to(site_url('connexion'));
            }
        }else{
            $session->setFlashdata('msg', 'Email not Found');
            //return redirect()->to('/Connexion');
            return redirect()->to(site_url('connexion'));
        }
    }

    public function logout(){
        $session = session();
        $session->destroy();
        return redirect()->to('/Connexion');
    }
}