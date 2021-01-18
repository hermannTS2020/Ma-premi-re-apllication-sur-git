<?php 

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Libraries\FileUploader;


//use App\Model\UserModel;

class Dashboard extends Controller{
    public function index(){
        $session = session();

        $data = [
            'pageTitle' => 'Create a New Post',
            'validation' => \Config\Services::validation()
        ];
        
        echo view('templates/head');
        echo view('templates/header');
        echo view('Dashboard/form', $data);
        echo view('templates/script');
    }

    public function create(){
        // load model
        //$article = new ArticleModel();

        // validation
        helper(['form']);
        $uri = new \CodeIgniter\HTTP\URI();
        $uri = $this->request->uri;
        var_dump($uri->getSegment(3)); die;
        
        $rules = [
            'author' => [
                'rules'=>'required',
                'errors' => [
                    'required' => 'Veuillez saisir le nom de l\'auteur. Le nom de l\'auteur est obligatoire!'
                ]        
            ],
            'title' => [
                'rules' => 'required|min_length[8]',
                'errors' => [
                    'required' => 'veuillez entrer le titre de l\'article svp'
                ]
            ],
            
            'description' => 'required|min_length[100]',
            'content' => 'required'
        ];
        if (!$this->validate($rules)){
            //return redirect()->to('/article/newpost')->withInput();
            

            //echo view('Dashboard/form', $data);
            return redirect()->to(site_url('Dashboard'))->withInput();
        }else{
            #upload de fichiers avec FileUpload 
            //require_once FCPATH."assets/vendor/fileuploader-demo/src/class.fileuploader.php";
            
            // initialize FileUploader
            /*$FileUploader = new FileUploader('files', array(
                'uploadDir' => 'uploads/'
            ));
            
            // call to upload the files
            $data = $FileUploader->upload();
            //var_dump($data);die; 
            // if uploaded and success
            for($i=0; $i<=1; $i++){
                echo '<pre>';
                print_r($data['files'][$i]['name']);
                echo '</pre>';
            }die;
            if($data['isSuccess'] && count($data['files']) > 0) {
                echo '<pre>';
                print_r($data['files'][0]['name']);
                echo '</pre>';
            }

            // if warnings
            if($data['hasWarnings']) {
                echo '<pre>';
                print_r($data['warnings']);
                echo '</pre>';
                exit;
            }*/

            #upload de fichier avec CI4

            $files = $this->request->getFiles('files');
            //var_dump($files); die;
            //$row = new stdclass;
            $da = array();
            if($imagefile = $this->request->getFiles())
            {
                foreach($imagefile['files'] as $img)
                {
                    if ($img->isValid() && ! $img->hasMoved())
                    {
                        $row = new \stdclass;
                        $row->name = $img->getName();
                        $da[] = $row;
                        $name = 'Mon_document12'.'.'.$img->getClientExtension();;
                        //$newName = $img->getRandomName();
                        //var_dump($newName); die;
                        $img->move(WRITEPATH . 'uploads',$name); #sauvegarde dans le dossier writable
                        
                    }
                }
            }
            var_dump(json_encode($da)); die;
        }

        $slug = url_title($this->request->getPost('title'), '-', true);
        /*$article->save([
            'author' => $this->request->getPost('author'),
            'title' => $this->request->getPost('title'),
            'slug' => $slug,
            'description' => $this->request->getPost('description'),
            'date_create' => $this->request->getPost('date_create'),
            'content' => $this->request->getPost('content')
        ]);*/

       // return $this->response->redirect(site_url('article'));
    }
}
