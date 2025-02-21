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
    $nombreError = $apellidosError = $fechaNacimientoError = $telefonoError =
    $categoriaError = $generoError = $aficionesError = "";
    $nombre = $apellidos = $fechaNacimiento = $telefono =
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
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['telefono'])) {
        if (empty($_POST['telefono'])) {
            $telefonoError = "El telefono es requerido"
        } else { 
            if(!preg_match("/^[0-9\+]*$/", $apellidos)) {
                $apellidosError = "Solo se permiten numero y el caracter +";    
            } 

        }
    }
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
        <label for="sueldo">telefono: </label>
    <input type="text" name="sueldo" value="<?php echo $telefono ?>">
    <span class="error">*<?php echo $telefonoError;?></span>
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