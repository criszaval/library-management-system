<?php

require_once '../../classes/Biblioteca.php';


$biblioteca = new Biblioteca();



// ==========================
// GUARDAR PRESTAMO
// ==========================


if($_SERVER['REQUEST_METHOD'] == 'POST'){


    $libro_id = $_POST['libro_id'];

    $usuario_id = $_POST['usuario_id'];



    $resultado = $biblioteca->prestarLibro(
        $libro_id,
        $usuario_id
    );



    header("Location: ../../index.php?action=prestamos");
    exit;


}




// ==========================
// CARGAR DATOS
// ==========================


$libros = $biblioteca->obtenerLibros();

$usuarios = $biblioteca->obtenerUsuarios();



?>



<!DOCTYPE html>
<html lang="es">


<head>


<meta charset="UTF-8">


<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Nuevo Préstamo
</title>

<style>

*{
    box-sizing:border-box;
}


body{

    font-family:'Segoe UI', Arial, sans-serif;
    background:#f4f6f9;
    margin:0;
    padding:40px;

}



.container{

    width:500px;
    max-width:100%;
    margin:auto;

}



.card{

    background:white;
    padding:35px;
    border-radius:15px;
    box-shadow:0 10px 25px rgba(0,0,0,0.1);

}



h1{

    text-align:center;
    color:#333;
    margin-bottom:5px;

}



.subtitulo{

    text-align:center;
    color:#777;
    margin-bottom:30px;

}



label{

    display:block;
    margin-bottom:8px;
    color:#555;
    font-weight:bold;

}



select{

    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:25px;
    font-size:15px;
    background:white;
    cursor:pointer;

}



select:focus{

    border-color:#333;
    outline:none;

}



button{

    width:100%;
    padding:12px;
    background:#222;
    color:white;
    border:none;
    border-radius:8px;
    font-size:16px;
    cursor:pointer;
    transition:.3s;

}



button:hover{

    background:#444;

}



.volver{

    display:block;
    text-align:center;
    margin-top:20px;
    color:#333;
    text-decoration:none;

}



.info{

    background:#f1f1f1;
    padding:12px;
    border-radius:8px;
    margin-bottom:25px;
    font-size:14px;
    color:#555;

}



</style>


</head>



<body>



<div class="container">


<div class="card">



<h1>
Nuevo Préstamo
</h1>


<p class="subtitulo">

Registrar salida de libro

</p>





<div class="info">

Seleccione un libro disponible y el usuario que realizará el préstamo.

</div>






<form method="POST">






<label>
Seleccionar Libro
</label>



<select name="libro_id" required>


<option value="">
Seleccione un libro
</option>




<?php foreach($libros as $libro): ?>


<?php if($libro['cantidad'] > 0): ?>


<option value="<?= $libro['id'] ?>">


<?= $libro['titulo'] ?>

-
Disponibles:
<?= $libro['cantidad'] ?>


</option>



<?php endif; ?>


<?php endforeach; ?>



</select>








<label>
Seleccionar Usuario
</label>




<select name="usuario_id" required>



<option value="">
Seleccione un usuario
</option>




<?php foreach($usuarios as $usuario): ?>


<option value="<?= $usuario['id'] ?>">


<?= $usuario['nombre'] ?>


</option>



<?php endforeach; ?>



</select>






<button type="submit">

Registrar Préstamo

</button>




</form>





<a class="volver" href="../../index.php?action=prestamos">

← Volver a préstamos

</a>





</div>


</div>



</body>


</html>