<?php

    namespace App\Controllers;
    use App\Controller;
    use App\View;
    use App\Model;
    use App\Request;
    use App\Session;
    //use App\DB;

    class UserController extends Controller implements View,Model{
        public function __construct(Request $request, Session $session){
            parent::__construct($request,$session);
        }

        function dashboard(){
            
            $user=$this->session->get('user');
            $data=$this->getDB()->selectAllWithJoin('tasks','users',['tasks.id','tasks.description','tasks.due_date'],'user','id');
            $this->render(['user'=>$user,'data'=>$data],'dashboard');
        }
        
        function log(){
            if (isset($_POST['email'])&&!empty($_POST['email'])
            &&isset($_POST['passw'])&&!empty($_POST['passw']))
            {
                $email=filter_input(INPUT_POST,'email',FILTER_SANITIZE_EMAIL);
                $pass=filter_input(INPUT_POST,'passw',FILTER_SANITIZE_STRING);
            
           
                $user=$this->auth($email,$pass);
                if ($user){
                    $this->session->set('user',$user);
                    //si usuari valid
                    if(isset($_POST['remember-me'])&&($_POST['remember-me']=='on'||$_POST['remember-me']=='1' )&& !isset($_COOKIE['remember'])){
                        $hour = time()+3600 *24 * 30;
                        $path=parse_url($_SERVER['REQUEST_URI'],PHP_URL_PATH);
                        setcookie('uname', $user['uname'], $hour,$path);
                        setcookie('email', $user['email'], $hour,$path);
                        setcookie('active', 1, $hour,$path);          
                    }
                    header('Location:'.BASE.'user/dashboard');
                }
                else{
                    header('Location:'.BASE.'user/login');
                }
            
            }
        }
        
        public function register(){
            $db=$this->getDB();
            $dataview = ['title'=>'register'];
            $this->render($dataview,"register");
        }
        
        public function registerform(){
            $db=$this->getDB();
            $user = filter_input(INPUT_POST,"uname");
            $password = filter_input(INPUT_POST,"passw");
            $email = filter_input(INPUT_POST,"email");
            $role = 1; // El rol 1 significa que es tipo usuario, todos los nuevos usuarios comienzan con este rol
            $tiempo = date("d-M-Y H:i:s");

            $cost = ['cost' => 4];
            $password = password_hash($password, PASSWORD_BCRYPT, $cost);

            try{ //Repito el código de SELECT dado que si no lo utilizo en esta función el register no me funciona correctamente, si el usuario existe no me da error SQL y me dice que se ha insertado pero no se inserta, y si no existe si que lo inserta
                $stmt=$db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1');
                $stmt->execute([':email'=>$email]);
                $count=$stmt->rowCount();
                $row=$stmt->fetchAll();  
                
                if($count == 1){       
                    $register = false;
                }
                else{
                    $command2 = "
                    INSERT INTO users (email,uname,passw,role) VALUES ('$email','$user','$password',$role)";
                    $db -> exec($command2);
                    $register =  true;
                }
            }catch(PDOException $e){
                $register = false;
                die($e -> getMessage());
            }

            if ($register){ //Lee si la función ha devuelto true
                $_SESSION["uname"] = $user; 
                $_SESSION["email"] = $email; 
                setcookie("email",$email,"/");  
                setcookie("uname",$user,"/"); 
                setcookie("tiempovisita",$tiempo,"/");
                $dataview = ['title'=>'loginuser'];
                $this->render($dataview,"loginuser"); //Redirige a la página principal con cookies
                //echo "Registre 1";
            }
            else{ //Si la función devuelve false significa que el usuario ya existe en la base de datos
                header('Location:'.BASE.'user/register'); //Redirigimos al usuario a register
                //echo "Registre 2";
            }
        }

        public function login(){
            $db=$this->getDB();
            $dataview = ['title'=>'login'];
            $this->render($dataview,"login");
            //$this->render(null,"login");
        }

        public function loginform(){
            $db=$this->getDB();
            $email = filter_input(INPUT_POST,"email");
            $password = filter_input(INPUT_POST,"passw");
            $user = filter_input(INPUT_POST,"uname");
            $recordar = filter_input(INPUT_POST,"recordar");
            $tiempo = date("d-M-Y H:i:s");

            try{   
                $stmt=$db->prepare('SELECT * FROM users WHERE email=:email LIMIT 1');
                $stmt->execute([':email'=>$email]);
                $count=$stmt->rowCount();
                $row=$stmt->fetchAll();  
                
                if($count == 1){       
                    $array = $row[0];
                    $res = password_verify($password,$array['passw']);
                   
                    if ($res){
                        $_SESSION['uname'] = $array['uname'];
                        $_SESSION['email'] = $array['email'];
                        $login = true;
                    }else{
                        $login = false;
                    }
                }else{
                    $login = false;
                }
            }catch(PDOException $e){
                $login = false;
                die($e -> getMessage());
            }
            
            if ($login){ //Lee si la función ha devuelto true 
                if ($recordar){
                    setcookie("email",$email,"/");  //Creamos cookie para guardar el nombre
                    setcookie("uname",$user,"/");  
                    setcookie("tiempovisita",$tiempo,"/"); 
                    $dataview = ['title'=>'loginuser'];
                    $this->render($dataview,"loginuser");
                    //echo "Login 1";
                }
                else{
                    $dataview = ['title'=>'loginanonimo'];
                    $this->render($dataview,"loginanonimo");
                    //echo "Login 2";
                } 
            }
            else{ //Lee si la función ha devuelto false para redirigir al login
                $dataview = ['title'=>'login'];
                $this->render($dataview,"login");
                //echo "Login 3";
            }
        }
    }

?>