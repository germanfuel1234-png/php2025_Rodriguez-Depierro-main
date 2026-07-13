<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Alta de Personas</title>
    <link href="css/bootstrap.min.css" rel="stylesheet"/>
    <link rel="stylesheet" href="css/estilos.css" />
</head>

<body>
    
    <main>
        
       
        <section id="altaPersona" class="container" >
            <div class="row justify-content-center">
                <div class="col-lg-7 col-xl-8">
                    
                    <h2 class="text-center">ALTA DE PERSONA</h2>
                    <p class="text-center"></p>
                    
                    <form action="respuesta.php" method="post" enctype="multipart/form-data">
                        
                        <div class="form-floating col-md mb-3">
                            <input name="nombre" id="nombreOrador" type="text" class="form-control" placeholder="Nombre" required>
                            <label for="nombreOrador">Nombre</label>
                        </div> 
                        
                        <div class="form-floating col-md mb-3">
                            <input name="apellido" id="apellidoOrador" type="text" class="form-control" placeholder="Apellido" required>
                            <label for="apellidoOrador">Apellido</label>
                        </div>

                         <div class="col-md mb-3">
                            <label for="fotoPersona" class="form-label">Subir Foto (JPG o PNG)</label>
                            <input name="foto" id="fotoPersona" type="file" class="form-control" required>
                        </div>

                        <div class="row">
                            <div class="col mb-3">
                                <div class="d-grid">
                                    <button type="submit" name="Alta" class="btn btn-success btn-lg btn-form">Guardar Persona</button>
                                </div>
                            </div>
                        </div>
                    </form>

                    <a href="index.php">Volver al Panel</a>
                
                </div>
            </div>
        </section>
    </main>
    <footer>
       
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOomMi466C8"
        crossorigin="anonymous"></script>
</body>

</html>