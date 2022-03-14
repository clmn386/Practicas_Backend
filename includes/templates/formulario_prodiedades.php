
 <fieldset>
    <legend>Informacion General</legend>

    <label for="titulo">Titulo:</label>
    <input 
    type="text" 
    id="titulo" 
    value="<?php echo san($propiedad->titulo); ?>" 
    name="propiedad[titulo]" 
    placeholder="Titulo Propiedad"> <!---name premite ver los valores del input.-->

    <label for="precio">Precio:</label>
    <input 
    type="number" 
    id="precio" 
    value="<?php echo san($propiedad->precio); ?>"
    name="propiedad[precio]" 
    placeholder="Precio Propiedad" 
    min="1" 
    max="9999999999">

    <label for="imagen">Imagen:</label>
    <input 
    type="file" 
    id="imagen" 
    name="propiedad[imagen]" 
    accept="image/jpeg, image/png">
    <?php if($propiedad->imagen){ ?>
        <img src="/imagenes/<?php echo $propiedad->imagen ?>" class="imagen-small">
    <?php } ?>



    <label for="descripcion">Descripcion</label>
    <textarea id="descripcion" name="propiedad[descripcion]"><?php echo san($propiedad->descripcion); 
    ?></textarea>
</fieldset>

<fieldset>
    <legend>Información Propiedad</legend>

    <label for="habitaciones">Habitaciones:</label>
    <input 
    type="number" 
    id="habitaciones" 
    value="<?php echo san($propiedad->habitaciones); ?>" 
    name="propiedad[habitaciones]" 
    placeholder="ej: 3"
    min="1" 
    max="9">

    <label for="wc">Baños:</label>
    <input 
    type="number" 
    id="wc" 
    value="<?php echo san($propiedad->wc); ?>" 
    name="propiedad[wc]" 
    placeholder="ej: 3" 
    min="1" 
    max="9">

    <label for="estacionamientos">Estacionamiento:</label>
    <input 
    type="number" 
    id="estacionamientos" 
    value="<?php echo san($propiedad->estacionamientos); ?>"
    name="propiedad[estacionamientos]" 
    placeholder="ej: 3" 
    min="1" 
    max="9">
</fieldset>

<fieldset>
 <legend>Vendedor</legend>
        <label for="vendedor">Vendedor</label>
        <select name="propiedad[vendedorId]" id="vendedor">
            <option selected value="">--Seleccionar--</option>
            <?php foreach($vendedores as $vendedor) { ?>
                <option
                <?php echo $propiedad->vendedorId === $vendedor->id ? 'selected' : ''; ?> value="<?php echo san($vendedor->id); ?>"
                ><?php echo san($vendedor->nombre). " " .san($vendedor->apellido);  ?></option>
            <?php } ?>
        </select>
</fieldset>