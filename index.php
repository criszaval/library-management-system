<?php

require_once 'classes/Biblioteca.php';


// Crear instancia

$biblioteca = new Biblioteca();



// ==========================
// ELIMINAR REGISTROS
// ==========================

if(isset($_GET['delete']) && isset($_GET['tipo'])){


    $id = $_GET['delete'];
    $tipo = $_GET['tipo'];

    // Mantener la vista actual
    $vista = $_GET['action'] ?? 'libros';

    $mensaje = "";


    if($tipo == "libro"){

        $mensaje = $biblioteca->eliminarLibro($id);

    }


    if($tipo == "usuario"){

        $mensaje = $biblioteca->eliminarUsuario($id);

    }


    echo "
    <script>
        alert('$mensaje');
        window.location='index.php?action=$vista';
    </script>
    ";

    exit;

}





// ==========================
// DEVOLVER LIBRO
// ==========================

if(isset($_GET['devolver'])){


    $idPrestamo = $_GET['devolver'];


    $biblioteca->devolverLibro($idPrestamo);


    header("Location:index.php?action=prestamos");
    exit;

}






// ==========================
// SECCIÓN
// ==========================

$action = $_GET['action'] ?? 'libros';





switch($action){



    case 'usuarios':


        $datos = $biblioteca->obtenerUsuarios();

        $titulo = "Usuarios";


    break;





    case 'prestamos':


        $datos = $biblioteca->obtenerPrestamosActivos();

        $titulo = "Préstamos Activos";


    break;





    case 'historial':


        $datos = $biblioteca->obtenerPrestamosHistorial();

        $titulo = "Historial de Préstamos";


    break;





    default:


        $datos = $biblioteca->obtenerLibros();

        $titulo = "Libros";


    break;


}



?>



<!DOCTYPE html>
<html lang="es">


<head>

<meta charset="UTF-8">

<meta name="viewport" content="width=device-width, initial-scale=1.0">


<title>
Sistema Biblioteca
</title>



<style>


body{

font-family:Arial;
margin:20px;

}



.container{

max-width:1000px;
margin:auto;

}



nav{

background:#eee;
padding:15px;
margin-bottom:20px;

}



nav a{

margin-right:15px;
text-decoration:none;
color:#333;

}



table{

width:100%;
border-collapse:collapse;

}



table,th,td{

border:1px solid #ccc;

}



th,td{

padding:10px;

}



th{

background:#f2f2f2;

}



.btn{

padding:8px 12px;
background:#333;
color:white;
text-decoration:none;
border-radius:5px;

}



.acciones a{

margin-right:10px;

}



.estado{

font-weight:bold;

}


</style>


</head>



<body>



<div class="container">



<h1>
Sistema de Gestión de Biblioteca
</h1>




<nav>


<a href="index.php">
Libros
</a>


<a href="index.php?action=usuarios">
Usuarios
</a>


<a href="index.php?action=prestamos">
Préstamos Activos
</a>


<a href="index.php?action=historial">
Historial
</a>


</nav>





<h2>

<?= $titulo ?>

</h2>






<!-- BOTONES -->



<?php if($action == "libros"): ?>


<a class="btn" href="views/libros/formulario.php">

Nuevo Libro

</a>



<?php elseif($action == "usuarios"): ?>


<a class="btn" href="views/usuarios/formulario.php">

Nuevo Usuario

</a>




<?php elseif($action == "prestamos"): ?>


<a class="btn" href="views/prestamos/formulario.php">

Nuevo Préstamo

</a>



<?php endif; ?>





<br><br>





<table>



<thead>


<tr>




<?php if($action == "libros"): ?>


<th>ID</th>
<th>Título</th>
<th>Autor</th>
<th>ISBN</th>
<th>Cantidad</th>
<th>Acciones</th>





<?php elseif($action == "usuarios"): ?>


<th>ID</th>
<th>Nombre</th>
<th>Email</th>
<th>Teléfono</th>
<th>Acciones</th>






<?php elseif($action == "prestamos" || $action == "historial"): ?>


<th>ID</th>
<th>Libro</th>
<th>Usuario</th>
<th>Fecha préstamo</th>
<th>Fecha devolución</th>
<th>Estado</th>


<?php if($action == "prestamos"): ?>

<th>Acción</th>

<?php endif; ?>



<?php endif; ?>



</tr>


</thead>





<tbody>



<?php foreach($datos as $item): ?>


<tr>



<?php if($action == "libros"): ?>


<td>
<?= $item['id'] ?>
</td>


<td>
<?= $item['titulo'] ?>
</td>


<td>
<?= $item['autor'] ?>
</td>


<td>
<?= $item['isbn'] ?>
</td>


<td>
<?= $item['cantidad'] ?>
</td>



<td class="acciones">


<a href="views/libros/formulario.php?id=<?= $item['id'] ?>">
Editar
</a>



<a href="index.php?delete=<?= $item['id'] ?>&tipo=libro&action=libros"
onclick="return confirm('¿Desea procesar este libro?')">
Eliminar

</a>


</td>









<?php elseif($action == "usuarios"): ?>



<td>
<?= $item['id'] ?>
</td>


<td>
<?= $item['nombre'] ?>
</td>


<td>
<?= $item['email'] ?>
</td>


<td>
<?= $item['telefono'] ?>
</td>



<td class="acciones">


<a href="views/usuarios/formulario.php?id=<?= $item['id'] ?>">
Editar
</a>



<a href="index.php?delete=<?= $item['id'] ?>&tipo=usuario&action=usuarios"
onclick="return confirm('¿Desea procesar este usuario?')">

Eliminar

</a>


</td>









<?php elseif($action == "prestamos" || $action == "historial"): ?>



<td>
<?= $item['id'] ?>
</td>


<td>
<?= $item['titulo'] ?>
</td>


<td>
<?= $item['nombre'] ?>
</td>


<td>
<?= $item['fecha_prestamo'] ?>
</td>


<td>

<?= $item['fecha_devolucion'] ?? 'Pendiente' ?>

</td>



<td class="estado">

<?= $item['estado'] ?>

</td>





<?php if($action == "prestamos"): ?>


<td>


<a href="index.php?devolver=<?= $item['id'] ?>"
onclick="return confirm('¿Registrar devolución?')">

Devolver

</a>


</td>


<?php endif; ?>





<?php endif; ?>



</tr>



<?php endforeach; ?>



</tbody>


</table>




</div>



</body>


</html>