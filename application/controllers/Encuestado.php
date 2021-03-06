<?php 
    if (!defined('BASEPATH'))  exit('No direct script access allowed');

    class Encuestado extends CI_Controller {
        public function __construct() {
            parent::__construct();
            $this->load->library(array('form_validation','session'));
            $this->load->helper(array('auth/login_rules'));
            $this->load->model('encuestado_model');
            $this->load->helper(array('getmenu'));
            $this->load->model('Auth');
        }
    
        public function index() {
            $data = array();
            $data['menu'] = main_menu();
            $data['encuestado'] = $this->encuestado_model->obtener_todos();
            if ($this->session->userdata('is_logged')) {
                $this->load->view('layout/header');
                $this->load->view('layout/navbar',$data);
                $this->load->view('encuestado/index', $data);
                $this->load->view('layout/footer');
            }else{
                redirect(base_url('login'));
            }      	
        }

        public function guardar($id=null){
            $data = array(); 
            if($id){
                $encuestado = $this->encuestado_model->obtener_por_id($id); 
                $data['idEncuestado'] = $encuestado->idEncuestado;
                $data['nombre'] = $encuestado->nombre;
                $data['telefono'] = $encuestado->telefono;
                $data['email'] = $encuestado->email;
            }else{
                $data['idEncuestado'] = null;
                $data['nombre'] = null;
                $data['telefono'] = null;
                $data['email'] = null;
            }

            $data['menu'] = main_menu();
            $this->load->view('layout/header');
            $this->load->view('layout/navbar',$data);
            $this->load->view('encuestado/guardar', $data);
            $this->load->view('layout/footer');
        }


        public function guardar_post($id=null){
            if($this->input->post()){
                $nombre = $this->input->post('nombre');
                $telefono = $this->input->post('telefono');
                $email = $this->input->post('email');
                $config = array(
                    array(
                        'field' => 'nombre',
                        'label' => 'Nombre de Usuario',
                        'rules' => 'required',
                    ),
                    array(
                        'field' => 'telefono',
                        'label' => 'Telefono',
                        'rules' => 'required',
                        'errors' => array(
                            'required' => 'El %s es invalido.',
                        )
                    ),
                    array(
                        'field' => 'email',
                        'label' => 'Correo',
                        'rules' => 'required|valid_email',
                        'errors' => array(
                            'required' => 'El %s es invalido.',
                        )
                    ),
                );

                $this->form_validation->set_rules($config);

                if ($this->form_validation->run() == TRUE){
                    $this->encuestado_model->guardar($nombre, $telefono, $email, $id);
                    redirect('encuestado');
                }else{
                $data = array();
                $data['idEncuestado'] = $id;
                $data['nombre'] = $nombre;
                $data['telefono'] = $telefono;
                $data['email'] = $email;
                $this->load->view('layout/header');
                $this->load->view('encuestado/guardar', $data);
                $this->load->view('encuestado/footer');
                }          	
            }else{
                $this->guardar();
            } 
        }


        /* public function eliminar($id){
            $this->encuestado_model->eliminar($id);
            redirect('encuestado');
        } */
        
        /*public function ver($id){
            $data = array();
            $encuestado = $this->encuestado_model->obtener_por_id($id);
            $data['encuestado'] = $encuestado;

            $this->load->view('layout/header');
            $this->load->view('encuestado/ver', $data);
            $this->load->view('layout/footer');
        }*/
    }
 ?>