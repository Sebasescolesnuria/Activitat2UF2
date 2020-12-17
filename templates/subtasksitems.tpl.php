<?php
    include 'base.tpl.php';
    //include APP.'/config.php';
    //require APP.'/schema.php';

?>
<hr>
<div class="container">
    <h2>Ver sublista</h2>
    <form action="<?=BASE?>tasks/sublistas" method="POST">
        <input type="number" class="form-control mb-2 mr-sm-2" placeholder="Enter id" name="idtask"><br>
        <br><input type="submit" name="enviar" class="btn btn-info" value="Enviar">
    </form>
</div>
<hr>
<div class="container">
    <h2>Insertar nueva sublista</h2>
    <form action="<?=BASE?>tasks/sublistas" method="POST">
        <input type="number" class="form-control mb-2 mr-sm-2" placeholder="Enter id" name="idtask2">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter name" name="description2">
        Completed:<input type="checkbox" class="form-control mb-2 mr-sm-2" name="completed"><br>
        <br><input type="submit" name="insertar" class="btn btn-info" value="Insertar">
    </form>
</div>
<hr>
    <h2>Borrar sublista</h2>
    <form action="<?=BASE?>tasks/sublistas" method="POST">
        <input type="number" class="form-control mb-2 mr-sm-2" placeholder="Enter id from tasks" name="idtask3">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter name" name="namesublist"><br>
        <br><input type="submit" name="borrar" class="btn btn-info" value="Borrar">
    </form>
</div><br>
<a href="<?=BASE?>tasks/tasks">Volver atras</a>
<?php

include 'footer.tpl.php';

?>