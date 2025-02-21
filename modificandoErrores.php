<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        
        body {
            display:flex;
            justify-content: center;
        }
        .error { 
            color : red;
        }
        input[type = "text"],
        input[type = "number"],
        input[type = "date"]{
            box-sizing: border-box;
        }
        form{
            border: 1px black solid;
            padding: 20px;
            
        }
        button [type = "submit"],
        button [type = "reset"] {
            text-align:center;

        }



    </style>
</head>
<body>
<?php
session_start();
    function test_input($dato) {
        $dato = trim($dato);
        $dato = stripslashes($dato);
        $dato = htmlspecialchars($dato);
        return $dato;
    }
    //definimos variables a usar en el resto del codigo
    $nombreError = $apellidosError = $fechaNacimientoError = $sueldoError =
    $categoriaError = $generoError = $aficionesError = "";
    $nombre = $apellidos = $fechaNacimiento = $sueldo =
    $categoria = $genero = $aficiones = "";
    //validaciones del nombre, requerido, caracteres no numericos, mayor de 3 caracteres y numero de guiones validos
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["nombre"])) {
            $nombreError = "Nombre requerido";
        } else {
            $nombre = test_input($_POST["nombre"]);
            if(!preg_match("/^[a-zA-Z-áéíóúÁÉÍÓÚ\s]*$/", $nombre)) {
                $nombreError = "Solo se permiten letras y espacios en blanco";
            }
            $valor = $_POST['nombre'];
            if(isset($valor) && $valor !== '' && strlen($valor) < 3) {
                $nombreError = "La longitud del nombre debe ser mayor de 3 caracteres";
            }
            $nombre = test_input($_POST["nombre"]);
            $cadena = str_split($nombre);
            $cont=0;
            for($i=0; $i < count($cadena); $i++) {
                if($cadena[$i] == "-") {
                    $cont = $cont+1;
                    if($cont > 1) {
                        $nombreError = "No se permite mas de un guion";
                    } else {
                        $cont = $cont;
                    }
                }
            }
        }
    }
    //validacion de que el apellido es correcto, tiene que ser minimo dos apellidos, separados por espacio, sin caracteres especiales
    // solo se permite 1 guion
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        if(empty($_POST["apellidos"])) {
            $apellidosError = "apellidos requerido";
        } else {
            $apellidos = test_input($_POST["apellidos"]);
            if(!preg_match("/^[a-zA-Z-áéíóúÁÉÍÓÚ\s]*$/", $apellidos)) {
                $apellidosError = "Solo se permiten letras y espacios en blanco";    
            } else {
                $espacios = substr_count($apellidos, " ");
                if($espacios < 1) {
                    $apellidosError = "Se requieren al menos dos apellidos";
                }
            }
            $apellidos = test_input($_POST["apellidos"]);
            $cadena = str_split($apellidos);
            $cont=0;
            for($i=0; $i < count($cadena); $i++){
                if($cadena[$i] == "-") {
                    $cont = $cont +1;
                    if ($cont > 1) {
                        $apellidosError= "No se permite mas de un guion";
                    } else {
                       $cont = $cont;
                    }
                }
            }  
        }
    }
    
    $array = [];  

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['FechaNacimiento'])) {
        if (empty($_POST["FechaNacimiento"])) {
            $fechaNacimientoError = "Fecha de nacimiento requerida";
        } else {
            
            $fechaNacimiento = $_POST["FechaNacimiento"];
            
            //echo "La fecha de nacimiento es: " . htmlspecialchars($fechaNacimiento);
            
            
            $array = explode("-", $fechaNacimiento);
        }
    }
}
if (!empty($array) && count($array) == 3) {
    $añoNacimiento = (int) $array[0]; 
    $mesNacimiento = (int) $array[1];
    $diaNacimiento = (int) $array[2];
   if($añoNacimiento <= 1950){
    $fechaNacimientoError = "La fecha no es valida, debe ser superior a 1950";
   } else {
    $añoActual = (int)date("Y");
    $mesActual = (int)date("m");
    $diaActual = (int)date("d");
    $difAño = $añoActual - $añoNacimiento;
    if ($difAño > 18 || ($difAño === 18 && ($mesActual > $mesNacimiento || ($mesActual === $mesNacimiento && $diaActual >= $diaNacimiento)))) {
       $fechaNacimiento;
    } else {
        $fechaNacimientoError = "No tiene 18 años.";
    }
}
}
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Validación de sueldo
        if (isset($_POST['sueldo'])) {
            $sueldo = test_input($_POST["sueldo"]);
            if (empty($sueldo)) {
                $sueldoError = "Salario requerido";
            } else {
                if (!preg_match("/^[0-9]+(\.[0-9]{1,2})?$/", $sueldo)) {
                    $sueldoError = "Error: El salario debe ser un número válido, con máximo dos decimales.";
                } else {
                    // Validación del rango salarial general
                    $sueldo = (float)$sueldo;
                    if ($sueldo < 600 || $sueldo > 3000) {
                        $sueldoError = "El salario debe estar entre 600€ y 3000€.";
                    }
                }
            }
        }
        if (empty($_POST["categoria"])) {
            $categoriaError = "La categoría es obligatoria.";
        } else {
            $categoria = $_POST["categoria"];
        }
        // Validación del salario según la categoría
        if ($categoria) {
            switch ($categoria) {
                case 'peon':
                    if ($sueldo > 1200) {
                        $sueldoError = "El salario del peón debe estar entre 600€ y 1200€.";
                    }
                    break;
                case 'oficial':
                    if ($sueldo < 900 || $sueldo > 1500) {
                        $sueldoError = "El salario del oficial debe estar entre 900€ y 1500€.";
                    }
                    break;
                case 'Jefe Departamento':
                    if ($sueldo < 1400 || $sueldo > 2500) {
                        $sueldoError = "El salario del Jefe de Departamento debe estar entre 1400€ y 2500€.";
                    }
                    break;
                case 'director':
                    if ($sueldo < 2000) {
                        $sueldoError = "El salario del director debe estar entre 2000€ y 3000€.";
                    }
                    break;
            }
        }
    }
    $arrayAficiones = [];
    if(isset($_POST['aficiones'])) {
        $aficiones=$_POST['aficiones'];
        foreach ($aficiones as $aficion) {
        $arrayAficiones[] = $aficion;
    }
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (empty($_POST["genero"])) {
            $generoError = "el genero es obligatorio.";
        } else {
            $genero = $_POST["genero"];
        }
    
        if (empty($_POST["aficiones"])) {
            $aficionesError = "la aficion es obligatoria.";
        } else {
            $aficiones = $_POST["aficiones"];
        }
        
        }
    if ($genero == "masculino" && count($aficiones) == 1 && isset($aficiones['deportes'])) {
        $aficionesError = "Para el genero masculino se debe seleccionar al menos una aficion adicional.";
    }
    if($_SERVER["REQUEST_METHOD"]=="POST") {
        if(isset($_POST['nombre'])) $nombre = $_POST['nombre'];
        if(isset($_POST['apellidos'])) $apellidos = $_POST['apellidos'];
        if(isset($_POST['FechaNacimiento'])) $fechaNacimiento = $_POST['FechaNacimiento'];
        if(isset($_POST['sueldo'])) $sueldo = $_POST['sueldo'];
        if(isset($_POST['categoria'])) $categoria = $_POST['categoria'];
        if(isset($_POST['genero'])) $genero = $_POST['genero'];
        if(isset($_POST['aficiones'])) $aficiones = implode(", ", $_POST['aficiones']);
    }
    
    ?>
    <div class="container">
    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <h2 style ="text-align:center;"><u>Alta Datos Empleado</u></h2>
        <label for="nombre">Nombre: </label>
        <input type = "text" name="nombre" value="<?php echo $nombre;?>">
        <span class="error">*<?php echo $nombreError;?></span>
        <br><br>
        <label for="apellidos">Apellidos: </label>
        <input type = "text" name="apellidos" value="<?php echo $apellidos;?>">
        <span class="error">*<?php echo $apellidosError;?></span>
        <br><br>
        <label for="FechaNacimiento">Fecha de nacimiento: </label>
        <input type = "date" name="FechaNacimiento" value="<?php echo $fechaNacimiento;?>">
        <span class="error">*<?php echo $fechaNacimientoError;?></span>
        <br><br>
        <label for="sueldo">Sueldo: </label>
    <input type="text" name="sueldo" value="<?php echo $sueldo ?>">
    <span class="error">*<?php echo $sueldoError;?></span>
    <br><br>
    <label for="categoria">Categoría: </label>
    <select name="categoria">
        <option value="peon" <?php echo ($categoria == "peon") ? 'selected' : ''; ?>>Peón</option>
        <option value="oficial" <?php echo ($categoria == "oficial") ? 'selected' : ''; ?>>Oficial</option>
        <option value="Jefe Departamento" <?php echo ($categoria == "Jefe Departamento") ? 'selected' : ''; ?>>Jefe Departamento</option>
        <option value="director" <?php echo ($categoria == "director") ? 'selected' : ''; ?>>Director</option>
    </select>
    <span class="error">*<?php echo $categoriaError;?></span>
    <br><br>
    <label for="genero">Sexo:</label>
        <input type="radio" name="genero" value="masculino" <?php echo ($genero == "masculino") ? 'checked' : ''; ?>/>Hombre
        <input type="radio" name="genero" value="femenino"<?php echo ($genero == "femenino") ? 'checked' : ''; ?>/>Mujer
        <span class="error"> *<?php echo $generoError;?></span>
    <br><br>
        <label for="aficiones">Aficiones: </label><br>
                <input type="checkbox" name="aficiones[deportes]" value="Deportes" <?php echo isset($aficiones['deportes']) ? 'checked' : ''; ?>/>Deportes
                <input type="checkbox" name="aficiones[lectura]" value="Lectura" <?php echo isset($aficiones['lectura']) ? 'checked' : ''; ?>/>Lectura
                <input type="checkbox" name="aficiones[musica]" value="Musica" <?php echo isset($aficiones['musica']) ? 'checked' : ''; ?>/>Musica
                <input type="checkbox" name="aficiones[cine]" value="Cine" <?php echo isset($aficiones['cine']) ? 'checked' : ''; ?>/>Cine
                <input type="checkbox" name="aficiones[idiomas]" value="Idiomas" <?php echo isset($aficiones['idiomas']) ? 'checked' : ''; ?>/>Idiomas
                <span class="error"> *<?php echo $aficionesError;?></span>
                <br><br>
        <button type="Submit" name= "revisar">REVISAR DATOS</button>
        <button type="Reset">LIMPIAR</button> 
    </form>
    </div>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['revisar'])) {
    // Guardar los datos en las variables de sesión
    $_SESSION['nombre'] = $nombre;
    $_SESSION['apellidos'] = $apellidos;
    $_SESSION['fechaNacimiento'] = $fechaNacimiento;
    $_SESSION['sueldo'] = $sueldo;
    $_SESSION['categoria'] = $categoria;
    $_SESSION['genero'] = $genero;
    $_SESSION['aficiones'] = $aficiones;
     // Si el usuario ha hecho clic en "Revisar" (formulario intermedio)
     if ((empty($nombreError)) && (empty($apellidoError)) && (empty($fechaNacimientoError)) && (empty($sueldoError)) && (empty($categoriaError)) && (empty($generoError)) && (empty($aficionesError))) {
     echo "<pre>";
     echo '<form action="" method="POST">';
         echo "<h2>Revisa tus datos:</h2>";
         echo "<p><strong>Nombre:</strong> $nombre</p>";
         echo "<p><strong>Apellidos:</strong> $apellidos</p>";
         echo "<p><strong>Fecha Nacimiento:</strong> $fechaNacimiento</p>";
         echo "<p><strong>Sueldo:</strong> $sueldo</p>";
         echo "<p><strong>Categoria:</strong> $categoria</p>";
         echo "<p><strong>Genero:</strong> $genero</p>";
         echo "<p><strong>Aficiones:</strong> $aficiones</p>";
         echo '<button type="submit" name="modificar">Modificar Datos</button>';
         echo '<button type="submit" name="enviar">Enviar Datos</button>';
         //echo '<input type="hidden" name="enviar" value="enviar"/>';
     echo '</form>';
     echo "</pre>";
 }
}
   $empleadosExistentes = [];

 if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['enviar'])) {
    $empleado = implode(", ", $_SESSION);
    chdir ("/temp1");
    $archivo = fopen("archivo.txt", "a+") or die("no se puede abrir");
    if ($archivo) {
        $empleadosExistentes = file("archivo.txt", FILE_IGNORE_NEW_LINES);

        if (in_array($empleado, $empleadosExistentes)) {
            echo "El empleado ya a sido registrado anteriormente.";
        } else {
            fwrite($archivo, $empleado . "\n");
            echo "<pre>";
        // Si el usuario decide enviar los datos, muestra los datos guardados en la sesión
        echo "<h2>Datos enviados correctamente:</h2>";
        echo "<p><strong>Nombre:</strong> " . $_SESSION['nombre'] . "</p>";
        echo "<p><strong>Apellidos:</strong> " . $_SESSION['apellidos'] . "</p>";
        echo "<p><strong>Fecha Nacimiento:</strong> " . $_SESSION['fechaNacimiento'] . "</p>";
        echo "<p><strong>Sueldo:</strong> " . $_SESSION['sueldo'] . "</p>";
        echo "<p><strong>Categoria:</strong> " . $_SESSION['categoria'] . "</p>";
        echo "<p><strong>Genero:</strong> " . $_SESSION['genero'] . "</p>";
        echo "<p><strong>Aficiones:</strong> " . $_SESSION['aficiones'] . "</p>";
        echo "</pre>";
        
        }
        fclose($archivo);
    } else {
        echo "No se pudo abrir el archivo.";
    }
    
}


print_r ($empleadosExistentes);
?>







</body>
</html>
