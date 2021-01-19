<?php namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;

class Register extends Controller {
    public function index(){
        //inclusion du helper form
        helper(['form']);
        $data=[];
        echo view('Register/register', $data);
    }

    public function save() {
        helper(['form']);
        //form validation
        $rules = [
            'name'          => 'required|min_length[3]|max_length[20]',
            'email'         => 'required|min_length[6]|max_length[50]|valid_email|is_unique[users.user_email]',
            'password'      => 'required|min_length[6]|max_length[200]',
            'confpassword'  => 'matches[password]'
        ];

        if($this->validate($rules)){
            $model = new UserModel(); // chargement du model 
            $data = [
                'user_name' => $this->request->getVar('name'),
                'user_email' => $this->request->getVar('email'),
                'user_password' => password_hash ($this->request->getVar('password'), PASSWORD_DEFAULT) //crypter les mots de passe
            ];

            $model->add($data);
            return redirect()->to(site_url('connexion'));
            //return redirect('Connexion');
        }else{
            $data['validation'] = $this->validator;
            echo view('Register/register', $data);
        }
    }


    public function test() {
        echo 'test test view';
    }

}