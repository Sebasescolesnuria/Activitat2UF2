<?php
    include 'base.tpl.php';
    //include APP.'/config.php';
    //require APP.'/src/Controllers/TasksControllers.php';

?>
<hr>
<div class="container">
  <h2>Crear nueva lista</h2>
    <form class="form-inline" action="<?=BASE?>tasks/tasks" method="POST" id="form">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter name" name="description">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Date 2020/12/12 24:00:00" name="date"><br>
        <div>      
            <br><input type="submit" value="Insertar" class="btn btn-info" name="insertar"><br>
        </div>
    </form>
</div>
<hr>
<div class="container">
    <h2>Borrar lista</h2>
    <form class="form-inline" action="<?=BASE?>tasks/tasks" method="POST" id="form">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter name" name="description2"><br>
        <div>      
            <br><input type="submit" value="Eliminar" class="btn btn-info" name="borrar"><br>
        </div>
    </form>
</div>
</body>
<?php

include 'footer.tpl.php';

?>