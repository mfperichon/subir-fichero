<?php
session_start();

$message = ''; 
if (isset($_POST['btnSubir']) && $_POST['btnSubir'] == 'ENVIAR ARCHIVO')
{
  if (isset($_FILES['archivoSubir']) && $_FILES['archivoSubir']['error'] === UPLOAD_ERR_OK)
  {
    // Obtengo los detalles del archivo
    $fileTmpPath = $_FILES['archivoSubir']['tmp_name'];
    $fileName = $_FILES['archivoSubir']['name'];
    $fileSize = $_FILES['archivoSubir']['size'];
    $fileType = $_FILES['archivoSubir']['type'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Renombrar el archivo
//    $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
    $newFileName = $fileName;

    // Verificar ue el archivo corresponda con las extensiones permitidas
    $allowedfileExtensions = array('jpg', 'gif', 'png', 'zip', 'txt', 'xls', 'xlsx', 'doc', 'docx', 'pdf');

    if (in_array($fileExtension, $allowedfileExtensions))
    {
      // Directorio de destino donde guardaremos los archivos
      $uploadFileDir = './archivos_subidos/';
      $dest_path = $uploadFileDir . $newFileName;

      if(move_uploaded_file($fileTmpPath, $dest_path)) 
      {
        $message ='success';
      }
      else 
      {
        $message = 'error';
      }
    }
    else
    {
      $message = 'invalid_file';
      $descripcion = implode(',', $allowedfileExtensions);
      $_SESSION['descripcion'] = $descripcion;
    }
  }
  else
  {
    $message = 'fail';
    $descripcion = 'Error:' . $_FILES['archivoSubir']['error'];
    $_SESSION['descripcion'] = $descripcion;
  }
}
$_SESSION['message'] = $message;
header("Location: index.php");
