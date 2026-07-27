<?php
require_once 'Database.php';
require_once 'Libro.php';
require_once 'Usuario.php';
require_once 'Prestamo.php';

class Biblioteca {
    private $db;
    private $conn;

    public function __construct() {
        // TODO: Inicializar conexión a base de datos
        $this->db = new Database();
        $this->conn = $this->db->getConnection();
    }

    // Gestión de Libros
    public function agregarLibro(Libro $libro) {
        // TODO: Insertar libro en base de datos
        $sql = "INSERT INTO libros (titulo,autor,isbn,cantidad) VALUES (:titulo, :autor, :isbn, :cantidad)";

        $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(':titulo', $libro->getTitulo());
    $stmt->bindValue(':autor', $libro->getAutor());
    $stmt->bindValue(':isbn', $libro->getIsbn());
    $stmt->bindValue(':cantidad', $libro->getCantidad());

    return $stmt->execute();

    }

    public function editarLibro($id, $nuevosDatos) {
        // TODO: Actualizar libro en base de datos
        $sql = "UPDATE libros 
            SET titulo = :titulo,
                autor = :autor,
                isbn = :isbn,
                cantidad = :cantidad
            WHERE id = :id";


    $stmt = $this->conn->prepare($sql);


    $stmt->bindValue(':titulo', $nuevosDatos['titulo']);
    $stmt->bindValue(':autor', $nuevosDatos['autor']);
    $stmt->bindValue(':isbn', $nuevosDatos['isbn']);
    $stmt->bindValue(':cantidad', $nuevosDatos['cantidad']);
    $stmt->bindValue(':id', $id);


    return $stmt->execute();
    }

    public function eliminarLibro($id) {

    // Verificar préstamos activos
    $sql = "SELECT COUNT(*)
            FROM prestamos
            WHERE libro_id = :id
            AND estado = 'activo'";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $prestamosActivos = $stmt->fetchColumn();


    if($prestamosActivos > 0){

        return "No se puede eliminar este libro porque tiene préstamos activos. Debe devolverse primero.";

    }


    // Verificar si tiene historial de préstamos
    $sql = "SELECT COUNT(*)
            FROM prestamos
            WHERE libro_id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $historial = $stmt->fetchColumn();



    if($historial > 0){

        // Tiene historial, solo desactivar
        $sql = "UPDATE libros
                SET activo = 0
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return "El libro fue desactivado porque tiene historial de préstamos.";

    } else {


        // Nunca tuvo préstamos, eliminar
        $sql = "DELETE FROM libros
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();


        return "El libro fue eliminado porque nunca tuvo préstamos.";

    }

}
    public function obtenerLibros() {

    $sql = "SELECT * FROM libros WHERE activo = 1";

    $stmt = $this->conn->prepare($sql);

    $stmt->execute();

    return $stmt->fetchAll();
}

    public function buscarLibro($id) {
        // TODO: Retornar un libro específico
          $sql = "SELECT * FROM libros WHERE id = :id";

    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(':id', $id);

    $stmt->execute();

    return $stmt->fetch();
    }

    // Gestión de Usuarios
    public function agregarUsuario(Usuario $usuario) {
        // TODO: Insertar usuario en base de datos
        $sql = "INSERT INTO usuarios (nombre,email,telefono)
            VALUES (:nombre,:email,:telefono)";


    $stmt = $this->conn->prepare($sql);


    $stmt->bindValue(':nombre', $usuario->getNombre());
    $stmt->bindValue(':email', $usuario->getEmail());
    $stmt->bindValue(':telefono', $usuario->getTelefono());


    return $stmt->execute();
    }

    public function editarUsuario($id, $nuevosDatos) {
        // TODO: Actualizar usuario en base de datos
         $sql = "UPDATE usuarios 
            SET nombre = :nombre,
                email = :email,
                telefono = :telefono
            WHERE id = :id";


    $stmt = $this->conn->prepare($sql);


    $stmt->bindValue(':nombre', $nuevosDatos['nombre']);
    $stmt->bindValue(':email', $nuevosDatos['email']);
    $stmt->bindValue(':telefono', $nuevosDatos['telefono']);
    $stmt->bindValue(':id', $id);


    return $stmt->execute();
    }

   public function eliminarUsuario($id) {

    // Revisar préstamos activos
    $sql = "SELECT COUNT(*)
            FROM prestamos
            WHERE usuario_id = :id
            AND estado = 'activo'";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $prestamosActivos = $stmt->fetchColumn();


    if($prestamosActivos > 0){

        return "No se puede eliminar este usuario porque tiene préstamos activos. Debe devolver los libros primero.";

    }


    // Revisar si tiene historial
    $sql = "SELECT COUNT(*)
            FROM prestamos
            WHERE usuario_id = :id";

    $stmt = $this->conn->prepare($sql);
    $stmt->bindValue(':id', $id);
    $stmt->execute();

    $historial = $stmt->fetchColumn();


    if($historial > 0){

        // Tiene historial pero todo está devuelto
        $sql = "UPDATE usuarios
                SET activo = 0
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return "El usuario fue desactivado porque tiene historial de préstamos.";

    } else {

        // Nunca tuvo préstamos
        $sql = "DELETE FROM usuarios
                WHERE id = :id";

        $stmt = $this->conn->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return "El usuario fue eliminado porque no tenía préstamos.";

    }

}
    public function obtenerUsuarios() {
        // TODO: Retornar lista de usuarios

      $sql = "SELECT * FROM usuarios WHERE activo = 1";


    $stmt = $this->conn->prepare($sql);


    $stmt->execute();


    return $stmt->fetchAll();
    }
    public function buscarUsuario($id) {

    $sql = "SELECT * FROM usuarios WHERE id = :id";


    $stmt = $this->conn->prepare($sql);


    $stmt->bindValue(':id', $id);


    $stmt->execute();


    return $stmt->fetch();

}

    // Gestión de Préstamos
    public function prestarLibro($libro_id, $usuario_id) {
       $sql = "INSERT INTO prestamos 
            (libro_id, usuario_id, fecha_prestamo)
            VALUES (:libro_id,:usuario_id,CURDATE())";


    $stmt = $this->conn->prepare($sql);


    $stmt->bindValue(':libro_id',$libro_id);
    $stmt->bindValue(':usuario_id',$usuario_id);


    $resultado = $stmt->execute();


    if($resultado){

        $sql = "UPDATE libros 
                SET cantidad = cantidad - 1
                WHERE id = :id";


        $stmt = $this->conn->prepare($sql);


        $stmt->bindValue(':id',$libro_id);


        return $stmt->execute();
    }


    return false;
    }

    public function devolverLibro($prestamo_id) {
        // TODO: Actualizar fecha de devolución y estado del préstamo, actualizar stock
         $sql = "SELECT libro_id 
            FROM prestamos 
            WHERE id = :id";


    $stmt = $this->conn->prepare($sql);

    $stmt->bindValue(':id', $prestamo_id);

    $stmt->execute();


    $prestamo = $stmt->fetch();


    if($prestamo){

        $libro_id = $prestamo['libro_id'];


        // Actualizar préstamo
        $sql = "UPDATE prestamos
                SET fecha_devolucion = CURDATE(),
                    estado = 'devuelto'
                WHERE id = :id";


        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':id',$prestamo_id);

        $stmt->execute();



        // Aumentar stock del libro
        $sql = "UPDATE libros
                SET cantidad = cantidad + 1
                WHERE id = :libro_id";


        $stmt = $this->conn->prepare($sql);

        $stmt->bindValue(':libro_id',$libro_id);


        return $stmt->execute();

    }


    return false;
    }

 public function obtenerPrestamosActivos() {

    $sql = "SELECT 
                prestamos.id,
                libros.titulo,
                usuarios.nombre,
                prestamos.fecha_prestamo,
                prestamos.fecha_devolucion,
                prestamos.estado
            FROM prestamos

            INNER JOIN libros 
            ON prestamos.libro_id = libros.id

            INNER JOIN usuarios
            ON prestamos.usuario_id = usuarios.id

            WHERE prestamos.estado = 'activo'";


    $stmt = $this->conn->prepare($sql);


    $stmt->execute();


    return $stmt->fetchAll();
}
 public function obtenerPrestamosHistorial() {


    $sql = "SELECT 
                prestamos.id,
                libros.titulo,
                usuarios.nombre,
                prestamos.fecha_prestamo,
                prestamos.fecha_devolucion,
                prestamos.estado

            FROM prestamos

            INNER JOIN libros 
            ON prestamos.libro_id = libros.id

            INNER JOIN usuarios
            ON prestamos.usuario_id = usuarios.id

            WHERE prestamos.estado = 'devuelto'";



    $stmt = $this->conn->prepare($sql);


    $stmt->execute();


    return $stmt->fetchAll();

}
}

