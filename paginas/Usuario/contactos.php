<?php 
include __DIR__ . '/../../plantilla/header-publico.php'; 
?>
<link rel="stylesheet" href="../../estilos/contactoestilo.css">

<div class="container my-5">
    
  
    <div class="row text-center mb-5">
        
        <!-- Stitch -->
        <div class="col-md-6 mb-4">
            <img src="/pinatas-mel/imagenes/stich.png" alt="Stitch" class="img-piinata">
        </div>

   
        <div class="col-md-6 mb-4">
            <div class="caja-borde">
               
                <img src="/pinatas-mel/imagenes/ubicacion.png" alt="Ubicación" class="icono-img">
                
                <p>
                    Av. San Jonathan<br>
                    Fraccionamiento H2O, Col. Morelos<br>
                    C.P. 50120<br>
                    Toluca, Estado de México.
                </p>
            </div>
        </div>

      
        <div class="col-md-6 mb-4">
            <div class="caja-borde">
    
                <img src="/pinatas-mel/imagenes/whatsapp.png" alt="Teléfono" class="icono-img">
                
                <p>
                    Número de teléfono<br>
                    <strong>729 120 6121</strong>
                </p>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <img src="/pinatas-mel/imagenes/alicia.png" alt="Alicia" class="img-piinata">
        </div>
    </div>


    <div class="text-center mb-5 banner-horario">
        <h4>Horario de atención</h4>
        <p>Lunes a Sábado de 9:00 a.m. a 6:00 p.m.</p>
    </div>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="caja-formulario">
                <h3 class="text-center mb-4">Contáctanos</h3>
                
                <form>
                    <div class="mb-3">
                        <label>Nombre</label><br>
                        <input type="text" class="input-simple" placeholder="Ingresa tu Nombre">
                    </div>
                    
                    <div class="mb-3">
                        <label>Correo</label><br>
                        <input type="email" class="input-simple" placeholder="Ingresa una dirección de correo">
                    </div>
                    
                    <div class="mb-4">
                        <label>Mensaje</label><br>
                        <textarea class="input-simple" rows="4" placeholder="Mensaje"></textarea>
                    </div>
                    
                    <div class="text-end">
                        <button type="button" class="boton-enviar">Enviar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<?php include __DIR__ . '/../../plantilla/footer.php'; ?>