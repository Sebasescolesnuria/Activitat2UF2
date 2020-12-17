<?php

    namespace App\Controllers;
    use App\Controller;
    use App\Session;
    use App\Request;

    final class IndexController extends Controller{
        public function __construct(Request $request, Session $session){
            parent::__construct($request,$session);
        }
        
        public function index(){
            $db=$this->getDB();
            $dataview = ['title'=>'Todo'];
            $this->render($dataview);
        }
    }
?>