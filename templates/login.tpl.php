<?php

    include 'base.tpl.php';
    
?>

<div class="container">
  <h2>Formulario Login</h2>
  <p>Introduce tus datos</p>
    <form class="form-inline" action="<?=BASE?>user/loginform" method="POST">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter email" name="email">
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter user" name="uname">
        <input type="password" class="form-control mb-2 mr-sm-2" placeholder="Enter password" name="passw">
        <div>
            <input type="checkbox" value="Recordar" class="btn btn-info" name="recordar"> Recordar       
            <input type="submit" value="Logearse" class="btn btn-info">
        </div>
    </form>
</div>
<?php

    include 'footer.tpl.php';

?>