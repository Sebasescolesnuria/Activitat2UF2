<?php

    namespace App\Controllers;
    use App\Controller;
    use App\View;
    use App\Model;
    use App\Request;
    use App\Session;

    class TasksController extends Controller implements View,Model{
        public function __construct(Request $request, Session $session){
            parent::__construct($request,$session);
        }

        function listas(){
            $db=$this->getDB();
            $dataview = ['title'=>'listas'];
            $this->render($dataview,"tasks");
            self::tasks();
        }

        function tasks(){
            $db=$this->getDB();
            //Variables de la función select
            $user = $_SESSION["uname"];
            $idlist = [];
            $descriptionlist = [];
            $due_datelist = [];
            $count2 = 0;
            //Variables de la función insert
            $insertar = filter_input(INPUT_POST,"insertar");
            $description = filter_input(INPUT_POST,"description");
            $date = filter_input(INPUT_POST,"date");
            $insertarlistas = false;
            //Variables de la función delete
            $description2 = filter_input(INPUT_POST,"description2");
            $borrar = filter_input(INPUT_POST,"borrar");
            $borrarlista = false;

            try{  
                $stmt=$db->prepare('SELECT * FROM users WHERE uname=:uname LIMIT 1');
                $stmt->execute([':uname'=>$user]);
                $row=$stmt->fetchAll();  
                $array = $row[0];
                $id = $array['id'];
        
                $stmt2=$db->prepare('SELECT * FROM tasks WHERE user=:user');
                $stmt2->execute([':user'=>$id]);
                $count2=$stmt2->rowCount();
                $row2=$stmt2->fetchAll(); 
                $count2 = count($row2);
        
                if ($count2 != 0){
                    $i = 0;
                    while($i < $count2){
                        $array2 = $row2[$i];
                        foreach ($array2 as $valor => $value) {
                            if ($valor == 'id'){
                                $idlist[] = $value;
                            }
                            if ($valor == 'description'){
                                $descriptionlist[] = $value;
                            } 
                            if ($valor == 'due_date'){
                                $due_datelist[] = $value;
                            }
                        }
                        $i++;
                    }
                    $listas = true;           
                }
                else{
                    $listas = false;
                }

                if($insertar){
                    $stmt3=$db->prepare("
                    INSERT INTO tasks (description,user,due_date) VALUES (:description,$id,'$date')");
                    $stmt3->execute([':description'=>$description]);
                    $insertarlistas = true;
                }

                if($borrar){
                    $stmt2=$db->prepare("
                    DELETE FROM tasks WHERE user = $id AND description = :description");
                    $stmt2->execute([':description'=>$description2]);
                    $borrarlista = true;
                }

            }catch(PDOException $e){
                die($e -> getMessage());
            }

            if($listas){
                $idlist = implode (", ",$idlist);
                $descriptionlist = implode (", ",$descriptionlist);
                $due_datelist = implode (", ",$due_datelist);
                //include '../../templates/base.tpl.php';
                $dataview = ['title'=>'listas'];
                $this->render($dataview,"tasks");
                echo "<div id='divlistas'><p>Se han encontrado $count2 listas</p>
                <table id='listas'>
                    <tr><td class='td'>ID: $idlist</td></tr>
                    <tr><td class='td'>Description: $descriptionlist</td></tr>
                    <tr><td class='td'>Date: $due_datelist</td></tr>
                </table></div>";
                echo "<br><p><a href='".BASE."tasks/sublistas'>Ver tus sublistas</a></p>";
            }
            else{
                $dataview = ['title'=>'listas'];
                $this->render($dataview,"tasks");
                echo "<br>No se han encontrado listas";
            }

            if ($insertarlistas){
                header('Location:'.BASE.'tasks/tasks');
                echo "<br>Lista creada";
            }

            if ($borrarlista){
                header('Location:'.BASE.'tasks/tasks');
                echo "<br>Se ha borrado la lista";
            }
        }

        function sublistas(){
            $db=$this->getDB();
            $dataview = ['title'=>'sublistas'];
            $this->render($dataview,"subtasksitems");
            self::subtasksitems();
        }

        function subtasksitems(){
            $db=$this->getDB();
            //Variables para la función select
            $user = $_SESSION['uname'];
            $id_ti = filter_input(INPUT_POST,"idtask");
            $enviar = filter_input(INPUT_POST,"enviar");
            $descriptionti = [];
            $completedti = [];
            $count = 0;
            $taskitems = false;
            //Variables para la función insert
            $insertar = filter_input(INPUT_POST,"insertar");
            $completed = filter_input(INPUT_POST,"completed");
            $description2 = filter_input(INPUT_POST,"description2");
            $id_ti2 = filter_input(INPUT_POST,"idtask2");
            $insertarsublistas = false;
            //Variables para la función delete
            $borrar = filter_input(INPUT_POST,"borrar");
            $id_ti3 = filter_input(INPUT_POST,"idtask3");
            $namesublist = filter_input(INPUT_POST,"namesublist");
            $borrarsublista = false;

            if($enviar){
                try{   
                    $stmt=$db->prepare('SELECT * FROM task_item WHERE task=:task');
                    $stmt->execute([':task'=>$id_ti]);
                    $count=$stmt->rowCount();
                    $row=$stmt->fetchAll(); 
                    $count = count($row);
        
                    if ($count != 0){
                        $i = 0;
                        while($i < $count){
                            $array = $row[$i];
                            foreach ($array as $valor => $value) {
                                if ($valor == 'id'){
                                    $idti[] = $value;
                                }
                                if ($valor == 'item'){
                                    $descriptionti[] = $value;
                                } 
                                if ($valor == 'completed'){
                                    $completedti[] = $value;
                                }
                            }
                            $i++;
                        }
                        $taskitems = true;           
                    }
                    else{
                        $taskitems = false;
                    }
                }catch(PDOException $e){
                    $taskitems = false;
                    die($e -> getMessage());
                }
            }

            if($insertar){
                //Leemos si el checkbox esta marcado para poder definir a la sublista el estado de finalizada o en curso
                if($completed){
                    $estado = 1;
                }
                else{
                    $estado = 0;
                }
                $stmt=$db->prepare("
                INSERT INTO task_item (item,completed,task) VALUES (:description,$estado,$id_ti2)");
                if($stmt->execute([':description'=>$description2])){
                    $insertarsublistas = true;
                }
            }

            if($borrar){
                $stmt=$db->prepare("
                DELETE FROM task_item WHERE item = :id AND task = $id_ti3");
                if($stmt->execute([':id'=>$namesublist])){
                    $borrarsublista = true;
                }
            }

            if ($taskitems){
                $descriptionti = implode (", ",$descriptionti);
                $completedti = implode (", ",$completedti);
                echo "<br><div id='divlistas'><p>Se han encontrado $count listas</p>
                <table id='listas'>
                    <tr><td class='td'>ID: $id_ti</td></tr>
                    <tr><td class='td'>Description: $descriptionti</td></tr>
                    <tr><td class='td'>Completed: $completedti</td></tr>
                </table></div>";
            }

            if ($insertarsublistas){
                header('Location:'.BASE.'tasks/sublistas');
                echo "<br>Se ha insertado la sublista";
            }

            if ($borrarsublista){
                header('Location:'.BASE.'tasks/sublistas');
                echo "<br>Se ha borrado la sublista";
            }
        }
    }

?>