<?php
//Sesion
require '../../sesion.php';

//Conexión a la base de datos
require '../../../includes/config/database.php';
$db = conectarDB();

$queryEmpleado = "SELECT * FROM empleado WHERE $idUsuarios = empleado.Usuarios_idUsuarios";
$resultadoEmpleado = mysqli_query($db, $queryEmpleado);
$resultadoEmpleado = mysqli_fetch_assoc($resultadoEmpleado);
$idRol = $resultadoEmpleado['Rol_idRol'];
$idRol = (int)$idRol;

$queryRol = "SELECT Nombre_rol FROM rol WHERE idRol = $idRol";
$resultadoRol = mysqli_query($db, $queryRol);
$resultadoRol = mysqli_fetch_assoc($resultadoRol);
$queryInmueble = "SELECT
idInmueble,
inmueble.Activo,
Fecha_Registro,
Nombre_Apellido,
Nombre_Contrato,
Nombre_Tipo_Inmueble,
Nombre_Operacion,
-- datos_basicos.Fotos_idFotos1,
fotos.FotoPortada,
inmueble.Oficina,
fotos.FotoPortada,
datos_basicos.Direccion,
empleado.Nombre_Apellido,
datos_basicos.Direccion,
datos_basicos.Precio,
datos_basicos.Ubicacion_Maps,
datos_basicos.Colonias_idColonias,
caracteristicas.Superficie_Terreno,
caracteristicas.Superficie_Construccion,
caracteristicas.Descripcion,
colonias.nombre
FROM
inmueble
INNER JOIN empleado ON idEmpleado = id_Empleado
INNER JOIN tipo_contrato ON inmueble.idTipo_Contrato   = tipo_contrato.idTipo_Contrato
INNER JOIN tipo_inmueble ON inmueble.idTipo_Inmueble   = tipo_inmueble.idTipo_Inmueble
INNER JOIN tipo_operacion ON inmueble.idTipo_Operacion = tipo_operacion.idTipo_Operacion
INNER JOIN fotos ON inmueble.idInmueble = fotos.id_Inmueble_Fotos
INNER JOIN caracteristicas ON inmueble.idInmueble = caracteristicas.idInmueble_Caracteristicas
INNER JOIN datos_basicos ON inmueble.idInmueble = datos_basicos.Inmueble_idInmueble
INNER JOIN colonias ON datos_basicos.Colonias_idColonias = colonias.id WHERE FotoPortada IS NOT NULL AND VoBo = 0 ORDER BY idInmueble ASC";

$resultadoInmueble = mysqli_query($db, $queryInmueble);


// Consulta de los asesores activos para el select

$consultaAsesor = "SELECT idEmpleado, Nombre_Apellido FROM empleado";
$resultadoAsesor = mysqli_query($db, $consultaAsesor);

// Consulta de los idInmueble para el select

$consultaIdInmueble = "SELECT idInmueble FROM inmueble";
$resultadoIdImbueble = mysqli_query($db, $consultaIdInmueble);

// Consulta de los tipos de contratos activos para el select

$consultaContrato = "SELECT idTipo_Contrato, Nombre_Contrato, Activo FROM tipo_contrato";
$resultadoContrato = mysqli_query($db, $consultaContrato);

// Consulta de los tipos de inmueble activos para el select

$consultaTipoInmueble = "SELECT idTipo_Inmueble, Nombre_Tipo_Inmueble, Activo FROM tipo_inmueble";
$resultadoTipoInmueble = mysqli_query($db, $consultaTipoInmueble);

// Consulta de los tipos de operación activos para el select

$consultaOperacion = "SELECT idTipo_Operacion, Nombre_Operacion, Activo FROM tipo_operacion";
$resultadoOperacion = mysqli_query($db, $consultaOperacion);


$asesor = null;
$contrato = null;
$inmueble = null; 
$operacion = null;
$id = null;
$activo = null;


if(isset($_GET['del'])) {
    $idInmueble = $_GET['del'];
    $queryBorrarInmueble = "DELETE from inmueble where idInmueble = $idInmueble";
    $queryBorrarDatosBasicos = "DELETE from datos_basicos where Inmueble_idInmueble = $idInmueble";
    $queryBorrarCaracteristicas = "DELETE from caracteristicas where idInmueble = $idInmueble";
    $resultadoBorrar = mysqli_query($db, $queryBorrarDatosBasicos);
    $resultadoBorrar = mysqli_query($db, $queryBorrarCaracteristicas);
    $resultadoBorrar = mysqli_query($db, $queryBorrarInmueble);
    header('Location: index.php');
}





if($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['aprobar'])){
            
        $idInmueble = (int)$_POST['aprobar'];
        $queryAprobar = "UPDATE inmueble SET VoBo = 1 WHERE idInmueble = $idInmueble";
        $resultadoAprobar = mysqli_query($db, $queryAprobar);
        header ('Location: index.php');

    } else {

         // isset($_POST['id_Empleado']) != NULL OR isset($_POST['idTipo_Contrato']) != NULL OR isset($_POST['idTipo_Inmueble']) != NULL OR isset($_POST['idTipo_Operacion']) != NULL OR isset($_POST['idInmueble']) != NULL OR isset($_POST['activo']) != NULL
    // $_POST['id_Empleado']!= NULL OR $_POST['idTipo_Contrato'] != NULL OR $_POST['idTipo_Inmueble'] != NULL OR $_POST['idTipo_Operacion'] != NULL OR $_POST['idInmueble'] != NULL OR $_POST['activo'] != NULL

    if($_POST['idTipo_Contrato'] != NULL OR $_POST['idTipo_Inmueble'] != NULL OR $_POST['idTipo_Operacion'] != NULL OR  $_POST['activo'] != NULL OR $_POST['Superficie_Terreno'] != NULL OR $_POST['Superficie_Construccion'] != NULL) {


        // Asignación de variables y escape de datos para la prevención de inyección SQL
    
            $contrato = (int)$_POST['idTipo_Contrato'];
            $inmueble = (int)$_POST['idTipo_Inmueble'];
            $operacion = (int)$_POST['idTipo_Operacion'];
            $activo = (int)$_POST['activo'];
            $terreno = (int)$_POST['Superficie_Terreno'];
            $construccion = (int)$_POST['Superficie_Construccion'];
            $habitaciones = (int)$_POST['Habitaciones'];
            $estacionamiento = (int)$_POST['Puestos_Estacionamiento'];
    
            $queryInmueble = $queryInmueble ." WHERE ";
    
    
            foreach ($_POST as $key => $value) {
    
                if ($value == NULL){
                    unset($_POST[$key]);
                }
            }
    
    
            $queryBuscar = "SELECT
            inmueble.idInmueble,
            inmueble.Activo,
            Fecha_Registro,
            Nombre_Apellido,
            Nombre_Contrato,
            Nombre_Tipo_Inmueble,
            Nombre_Operacion,
            caracteristicas.Superficie_Construccion,
            caracteristicas.Superficie_Terreno,
            caracteristicas.Habitaciones,
            caracteristicas.Puestos_Estacionamiento,
            datos_basicos.Fotos_idFotos1,
            datos_basicos.Direccion
    
            FROM
    
            inmueble
    
            INNER JOIN empleado ON idEmpleado = id_Empleado
            INNER JOIN tipo_contrato ON inmueble.idTipo_Contrato = tipo_contrato.idTipo_Contrato
            INNER JOIN tipo_inmueble ON inmueble.idTipo_Inmueble = tipo_inmueble.idTipo_Inmueble
            INNER JOIN tipo_operacion ON inmueble.idTipo_Operacion = tipo_operacion.idTipo_Operacion
            INNER JOIN caracteristicas ON inmueble.idInmueble = caracteristicas.idInmueble
            INNER JOIN datos_basicos ON inmueble.idInmueble = datos_basicos.Inmueble_idInmueble
    
            WHERE ";
    
            foreach ($_POST as $key => $value) {
    
                if($key == "activo" ){
                    $key = ucfirst($key);
                }
    
                if(count($_POST) > 1 ) {
    
                    if($key == "Superficie_Terreno" OR $key == "Superficie_Construccion" OR $key == "Habitaciones" OR $key == "Puestos_Estacionamiento"){
                        $queryBuscar = $queryBuscar ."caracteristicas.". $key . " = " . $value . " AND " ;
                        unset($_POST[$key]);
                    } else {
    
                        $queryBuscar = $queryBuscar ."inmueble.". $key . " = " . $value . " AND " ;
                        unset($_POST[$key]);
                    }
    
                } else if (count($_POST) == 1){
    
                    if($key == "Superficie_Terreno" OR $key == "Superficie_Construccion" OR $key == "Habitaciones" OR $key == "Puestos_Estacionamiento"){
                        $queryBuscar = $queryBuscar ."caracteristicas.". $key . " = " . $value . " AND VoBo = 0";
                        unset($_POST[$key]);
                    }
                    else {                
                        
                        $queryBuscar = $queryBuscar ."inmueble.". $key . " = " . $value . " AND VoBo = 0" ;
                        unset($_POST[$key]);}
    
    
                }
            }
    
    
            $resultadoBuscar = mysqli_query($db, $queryBuscar);
        }
    
    
    
    
            
    
    
     
        if(isset($resultadoBuscar)){
            $resultadoInmueble = $resultadoBuscar;
        }
    }

    }


   




?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="CSS/MOBILE/mobile.css" media="(max-width: 840px)">
        <link rel="stylesheet" href="CSS/MEDIUM/mobile.css" media="(min-width:840px)">
        <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;400;700;900&display=swap" rel="stylesheet"/>
        <title>Unika|Listado Propiedades</title>
</head>
<body>
<header class="header__propiedades">
        <div>
            <section class="header__logo">
                <a href="index.php"><img src="../../../Assets/logo.png" alt=""></a>
            </section>

            <section class="header__name" >
                <p> Bienvenido <?php echo $resultadoEmpleado['Nombre_Apellido']?></p>
                <p class="name__rol"> Su Rol es: <?php echo $resultadoRol['Nombre_rol']?> </p>
            </section>
        </div>

    </header>

    <main>


        <section class="main__menu--content">
        <section class="main__menu" id="main__menu">
            <i>
                <a id="button_open" href="#">
                    <img src="../../../Assets/menu.png" alt="">
                </a>
            </i>
        </section>
        </section>

        <section class="main__content">
        
        <section class="main__nav" id="main__nav" >
            <nav>
                <ul>
                    <li><a href="../Listado/index.php"><span>Inmuebles</span></a></li>
                    <li><a href="index.php"><span>VoBo Inmuebles</span></a></li>
                    <li><a href="../../Empleados/Listado/index.php">Asesores</a></li>
                    <li><a href="../../Clientes/Listado/index.php">Clientes</a></li>
                    <li><a href="../Documentos/index.php">Documentos/Inmuebles</a></li>
                    <li class="nav__logout"><a href="../../cerrar-sesion.php">Cerrar Sesión</a></li>
                </ul>
            </nav>
        </section>


    <section class="main__table">





    <section class="table__config ">

        <section class="table__buttons">
            
            
        </section>

        <form method="POST" class="table__search">
            <div class="search__title">
                <p>Buscar por:</p>
            </div>

                <div class="search__element">
                    <span>Super. Terreno</span>
                    <input type="number" name="Superficie_Terreno" placeholder = "En m2">

                </div>

                <div class="search__element">
                    <span>Super. Construcción</span>
                    <input type="number" name="Superficie_Construccion" placeholder = "En m2">

                </div>
                
                <div class="search__element">
                    <span>Habitaciones</span>
                    <input type="number" name="Habitaciones" placeholder = "#">

                </div>
                
                <div class="search__element">
                    <span>Estacionamiento</span>
                    <input type="number" name="Puestos_Estacionamiento" placeholder = "#">

                </div>
                

                <div class="search__element">

                    <span>Asesor</span>
                    <select name="id_Empleado" id="">
                            <option value=""><----></option>
                            <?php while($row = mysqli_fetch_assoc($resultadoAsesor)) : ?>
                                <option value="<?php echo $row['idEmpleado']; ?>"><?php echo $row['Nombre_Apellido']; ?></option>
                            <?php endwhile; ?>
                        </select>
                </div>

                <div class="search__element">

                    <span>Contrato</span>
                    <select name="idTipo_Contrato" id="">
                            <option value=""><----></option>
                            <?php while($row = mysqli_fetch_assoc($resultadoContrato)) : ?>
                                <option value="<?php echo $row['idTipo_Contrato']; ?>"><?php echo $row['Nombre_Contrato']; ?></option>
                            <?php endwhile; ?>
                        </select>

                </div>

                <div class="search__element">

                    <span>Tipo de Inmueble</span>
                    <select name="idTipo_Inmueble" id="">
                            <option value=""><----></option>
                            <?php while($row = mysqli_fetch_assoc($resultadoTipoInmueble)) : ?>
                                <option value="<?php echo $row['idTipo_Inmueble']; ?>"><?php echo $row['Nombre_Tipo_Inmueble']; ?></option>
                            <?php endwhile; ?>
                    </select>

                </div>
                
                <div class="search__element">
                    
                    <span>Operación</span>
                    <select name="idTipo_Operacion" id="">
                        <option value=""><----></option>
                        <?php while($row = mysqli_fetch_assoc($resultadoOperacion)) : ?>
                            <option value="<?php echo $row['idTipo_Operacion']; ?>"><?php echo $row['Nombre_Operacion']; ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>


                    <div class="search__element">
                        <span>Activo</span>
                        <select name="activo" id="">
                            <option value=""><----></option>
                            <option value="1">Activo</option>
                            <option value="0">Inactivo</option>
                        </select>


                    </div>

            <div class="search__title">
                <input type="submit" value="Buscar">
            </div>


        </form>


        <table class="table__title">
            <tr>
                <td>Ver</td>
                <td>Foto</td>
                <td>Direccion</td>
                <td>Asesor</td>
                <td>Contrato</td>
                <td>Tipo de Inmueble</td>
                <td>Operacion</td>
                <td>Editar</td>
                <td>Aprobar</td>
                <td>Borrar</td>

            </tr>
        </table>
    </section>


    <?php while($row = mysqli_fetch_assoc($resultadoInmueble)): ?>





        <section class="table__content">

            <table>
                <tr>
                    <td class="content__ver">
                        <a  class="input-view" href="../Ver/index.php?id=<?php echo $row['idInmueble']?>"></a>
                    </td>
                    <td ><img class="content__imagen" src="../Imagenes/<?php 
                    if($row['FotoPortada'] == NULL){
                        echo "vacio.png";
                    } else {
                        echo $row['FotoPortada'];
                    }?>" alt=""></td>
                    <td><?php  echo $row['Direccion']?></td>
                    <td><?php  echo $row['Nombre_Apellido']?></td>
                    <td><?php  echo $row['Nombre_Contrato']?></td>
                    <td><?php  echo $row['Nombre_Tipo_Inmueble']?></td>
                    <td><?php  echo $row['Nombre_Operacion']?></td>

                    <td class="table__editar">
                        <a href="../Editar/index.php?id=<?php echo $row['idInmueble']?>">
                            <img src="../../../Assets/editar.png" alt="">
                        </a>
                    </td>
                    <td class="table__editar">
                        <form method="POST">

                            <a href="">
                                <input type="hidden"  class="input-aprobar" name="aprobar" value="<?php echo $row['idInmueble']?>">
                                <input type="submit" class="input-aprobar" alt="" >
                            </a>
                            
                        </form>




                </td>

                    <td class="table__editar">

                                <input type="hidden"  class="input-borrar" onclick="preguntar(<?php echo $row['idInmueble']?>)" >
                                <input type="button" class="input-borrar" alt="" onclick="preguntar(<?php echo $row['idInmueble']?>)">
                    </td>
                </tr>
            </table>
        </section>

    <?php endwhile; ?>
    </section>
    </section>

    </main>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script type="text/javascript">

        function preguntar(id){
            // id.preventDefault();

            Swal.fire({
            title: '¿Estás seguro de borrar este inmueble?',
            text: "",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Si, borrarlo'
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire(
                    'Borrado!',
                    'El inmueble ha sido borrado.',
                    'success'
                    )
                    setTimeout(function(){
                        window.location.href = "index.php?del=" + id;
                    }, 1750);
                }
                
            })
        }

    </script>

    <script src="JS/menu.js" ></script>
    <script src="JS/borrar.js" ></script>
</body>
</html>