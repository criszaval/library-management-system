<?php

require_once '../../classes/Biblioteca.php';
require_once '../../classes/Usuario.php';


$biblioteca = new Biblioteca();


// Variable para editar

$usuario = null;



// ==========================
// GUARDAR / EDITAR
// ==========================

if($_SERVER['REQUEST_METHOD'] == 'POST'){


    // EDITAR

    if(isset($_POST['id']) && !empty($_POST['id'])){


        $datos = [

            "nombre" => $_POST['nombre'],
            "email" => $_POST['email'],
            "telefono" => $_POST['telefono']

        ];


        $biblioteca->editarUsuario(
            $_POST['id'],
            $datos
        );



    }else{


        // CREAR


        $nuevoUsuario = new Usuario(

            $_POST['nombre'],
            $_POST['email'],
            $_POST['telefono']

        );


        $biblioteca->agregarUsuario($nuevoUsuario);


    }



    header("Location: ../../index.php?action=usuarios");
    exit;

}




// ==========================
// CARGAR USUARIO PARA EDITAR
// ==========================


if(isset($_GET['id'])){


    $usuario = $biblioteca->buscarUsuario($_GET['id']);


}



?>



<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>

<?= $usuario ? "Editar Usuario" : "Crear Usuario" ?>

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



input{

    width:100%;
    padding:12px;
    border:1px solid #ddd;
    border-radius:8px;
    margin-bottom:20px;
    font-size:15px;
    outline:none;

}



input:focus{

    border-color:#333;

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



</style>


</head>


<body>



<div class="container">


<div class="card">



<h1>

<?= $usuario ? "Editar Usuario" : "Nuevo Usuario" ?>

</h1>


<p class="subtitulo">

Gestión de usuarios de biblioteca

</p>





<form method="POST">



<?php if($usuario): ?>

<input 
type="hidden"
name="id"
value="<?= $usuario['id'] ?>"
>

<?php endif; ?>





<label>
Nombre completo
</label>


<input

type="text"

name="nombre"

placeholder="Ingrese nombre del usuario"

value="<?= $usuario['nombre'] ?? '' ?>"

required

>






<label>
Correo electrónico
</label>


<input

type="email"

name="email"

placeholder="correo@ejemplo.com"

value="<?= $usuario['email'] ?? '' ?>"

required

>






<label>
Teléfono
</label>


<input

type="text"

name="telefono"

placeholder="Número de teléfono"

value="<?= $usuario['telefono'] ?? '' ?>"

>





<button type="submit">

<?= $usuario ? "Actualizar Usuario" : "Guardar Usuario" ?>

</button>



</form>





<a class="volver" href="../../index.php?action=usuarios">

← Volver a usuarios

</a>




</div>


</div>


</body>

</html>