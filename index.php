<!doctype html>
<html lang="es">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <title>Subir un archivo al servidor usando PHP</title>
    <meta name="description" content="Subir un archivo al servidor usando PHP">
    <meta name="keywords" content="ejemplo, codigo, importar, subir, archivos, servidor, upload file, PHP">

    <!-- Favicons -->
    <link href="favicon.ico" rel="icon">
    <link href="apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Hojas de estilo CSS -->
    <link rel="stylesheet" href="css/normalize.css">
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/misestilos.css">

    <!-- Incluir Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <!-- Incluir Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <!-- Incluir jQuery JS -->
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
</head>
<body>
<div class="content-wrapper">
    <br>
    <section class="container-md">
        <h3>
            Subir archivos<br>
            <small class="text-body-secondary">proceso para enviar un archivo al servidor con PHP</small>
        </h3>
    </section>

<?php
    // incia sesion
    session_start();

    $mensaje="";
    $descripcion="";
    // Recibo el estado de la operacion en las variables de SESSION
    // Segun el resultado, se genera un alerta diferente (elemento 'alert' de Bootstrap)
    if (isset($_SESSION['message']) && $_SESSION['message'])
    {
        $resultado = $_SESSION['message'];
        switch ($resultado) {
            case 'success':
                $mensaje="<div class='container-lg'>
                <div class='alert alert-success alert-dismissible fade show' role='alert' id='myAlert'>
                    El archivo se subió con éxtito al servidor!
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>";
                break;
            case 'error':
                $mensaje="<div class='container-lg'>
                <div class='alert alert-danger alert-dismissible fade show' role='alert' id='myAlert'>
                    Ocurrió un ERRROR al copiar el archivo al directorio. Revisar que el directorio de destino tiene los permisos de acceso
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>";
                break;
            case 'invalid_file':
                $descripcion = $_SESSION['descripcion'];
                $mensaje="<div class='container-lg'>
                <div class='alert alert-warning alert-dismissible fade show' role='alert' id='myAlert'>
                    El archivo que intentó importar tiene un formato INVÁLIDO.<br>Los tipos de archivos permitidos son los siguientes: $descripcion
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>";
                unset($_SESSION['descripcion']);
                break;
            case 'fail':
                $descripcion = $_SESSION['descripcion'];
                $mensaje="<div class='container-lg'>
                <div class='alert alert-info alert-dismissible fade show' role='alert' id='myAlert'>
                    Se produjo un error durante la subida del archivo. Por favor revisar el siguiente error:<br> $descripcion
                    <button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                </div>
            </div>";
                unset($_SESSION['descripcion']);
                break;
            default:
                $mensaje="";
        }
        unset($_SESSION['message']);
    }

    // Aca se muestra el mensaje de alerta segun el resultado de la operacion
    echo $mensaje; 
?>

    <section class="content">
        <div class="container">            
            <div class="panel panel-default">
                <div class="panel-body">
                    <br>
                    <div class="row">
                        <form action="upload.php" method="post" enctype="multipart/form-data" id="import_form">
                            <div class="col-6">
                                <label for="file" class="form-label">Subir archivo al servidor</label>
                                <input type="file" name="archivoSubir" class="form-control"/>
                            </div>
                            <div class="col-6 p-3">
                                <input type="submit" class="btn btn-primary" name="btnSubir" id="btnSubir" value="ENVIAR ARCHIVO">
                                <span id="cargando" style="visibility: hidden;"><img src='img/loading.gif' width='20px' height='20px'/> Procesando Archivo ...</span>
                            </div>
                        </form>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-6">
                            <h3>Contenido del directorio:</h3>

                            <?php
                                function listarArchivos( $path ){
                                    // Abro la carpeta que nos pasan como parámetro
                                    $dir = opendir($path);
                                    // Leo todos los ficheros de la carpeta
                                    while ($elemento = readdir($dir)){
                                        // Evitamos los elementos . y .. que tienen todas las carpetas
                                        if( $elemento != "." && $elemento != ".." && $elemento != "desktop.ini"){
                                            // Si es una carpeta
                                            if( is_dir($path.$elemento) ){
                                                // Muestro la carpeta en negritas
                                                echo "<strong>&lt;" . $elemento . "&gt;</strong><br>";
                                            // Si es un fichero
                                            } else {
                                                // Muestro el fichero
                                                echo $elemento ."<br>";
                                            }
                                        }
                                    }
                                }

                                // Llamamos a la función para que nos muestre el contenido de la carpeta archivos_subidos
                                listarArchivos("./archivos_subidos/");
                            ?>
                        </div>
                    </div>
                    <hr>
                    <div class="row">
                        <div class="col-6">
                            <h3>Leer el directorio usando <i>ScanDir</i></h3>

                            <?php
                                $dir    = './archivos_subidos';
                                $files1 = scandir($dir);
                                //print_r($files1);
                                foreach ($files1 as $key => $value)
                                {
                                  echo $value . '<br>';
                                }
                            ?>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // **** Mostrar el gif de prograso de carga para que se vea alguna actividad
    // **** cuando el archivo es grande y demora un poco la subida
    // **** Al hacer click en el boton, se muestra el div que esta oculto
    btnSubir.onclick = temuestro;
    function temuestro() {
        document.getElementById("cargando").style.visibility = "visible";
    }
</script>

<script type="text/javascript">
    // **** Funcion setTimeOut para ocultar las notificaciones de Bootstrap
    // **** Pasados los 5 segundos, se oculta el elemento alert con id=myAlert
    setTimeout(function () {
        // Cerrar la notificacion 'alert' de Bootstrap
        $('#myAlert').alert('close');
    }, 3000);
</script>

</body>
</html>