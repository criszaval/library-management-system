<?php

require_once '../../classes/Biblioteca.php';
require_once '../../classes/Libro.php';


$biblioteca = new Biblioteca();

$libro = null;



// GUARDAR O EDITAR

if($_SERVER['REQUEST_METHOD'] == 'POST'){


    if(isset($_POST['id']) && !empty($_POST['id'])){


        $datos = [

            "titulo" => $_POST['titulo'],
            "autor" => $_POST['autor'],
            "isbn" => $_POST['isbn'],
            "cantidad" => $_POST['cantidad']

        ];


        $biblioteca->editarLibro(
            $_POST['id'],
            $datos
        );


    }else{


        $nuevoLibro = new Libro(

            $_POST['titulo'],
            $_POST['autor'],
            $_POST['isbn'],
            $_POST['cantidad']

        );


        $biblioteca->agregarLibro($nuevoLibro);

    }



    header("Location: ../../index.php");
    exit;

}




// CARGAR PARA EDITAR

if(isset($_GET['id'])){

    $libro = $biblioteca->buscarLibro($_GET['id']);

}

?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
<?= $libro ? "Editar Libro" : "Crear Libro" ?>
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



.titulo{

    text-align:center;
    color:#222;
    margin-bottom:5px;

}



.subtitulo{

    text-align:center;
    color:#777;
    margin-bottom:30px;

}



</style>



</head>



<body>



<div class="container">


<div class="card">



<h1 class="titulo">

<?= $libro ? "Editar Libro" : "Nuevo Libro" ?>

</h1>


<p class="subtitulo">

Gestión de biblioteca

</p>





<form method="POST">



<?php if($libro): ?>

<input 
type="hidden" 
name="id"
value="<?= $libro['id'] ?>"
>

<?php endif; ?>




<label>
Título
</label>


<input 
type="text"
name="titulo"
placeholder="Ingrese título del libro"
value="<?= $libro['titulo'] ?? '' ?>"
required
>





<label>
Autor
</label>


<input 
type="text"
name="autor"
placeholder="Ingrese autor"
value="<?= $libro['autor'] ?? '' ?>"
required
>





<label>
ISBN
</label>


<input 
type="text"
name="isbn"
placeholder="Código ISBN"
value="<?= $libro['isbn'] ?? '' ?>"
>





<label>
Cantidad disponible
</label>


<input 
type="number"
name="cantidad"
placeholder="Cantidad"
value="<?= $libro['cantidad'] ?? 1 ?>"
min="1"
required
>




<button type="submit">

<?= $libro ? "Actualizar Libro" : "Guardar Libro" ?>

</button>



</form>





<a class="volver" href="../../index.php">

← Volver al inicio

</a>




</div>


</div>


</body>

</html>