<?php

    include 'base.tpl.php';
    
?>

<div class="container">
  <h2>Formulario Register</h2>
  <p>Introduce tus datos</p>
    <form class="form-inline" action="<?=BASE?>user/registerform" method="POST">
        <input type="email" class="form-control mb-2 mr-sm-2" placeholder="Enter email" name="email" required>
        <input type="text" class="form-control mb-2 mr-sm-2" placeholder="Enter name" name="uname" required>
        <input type="password" class="form-control mb-2 mr-sm-2" placeholder="Enter password" name="passw" required>
        <div>    
            <input type="submit" value="Registrar" class="btn btn-info">
        </div>
    </form>
</div>
<br><a href="<?=BASE?>user/login">Logearse<br>
<?php

    include 'footer.tpl.php';

?>