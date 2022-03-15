
 <fieldset>
    <legend>Informacion General</legend>

    <label for="titulo">Nombre:</label>
    <input 
    type="nombre" 
    id="nombre" 
    value="<?php echo san($vendedor->nombre); ?>" 
    name="vendedor[nombre]" 
    placeholder="Nombre Vendedor(a)"> <!---name premite ver los valores del input.-->

    <label for="apellido">Apellido:</label>
    <input 
    type="apellido" 
    id="apellido" 
    value="<?php echo san($vendedor->apellido); ?>" 
    name="vendedor[apellido]" 
    placeholder="Apellido Vendedor(a)"> <!---name premite ver los valores del input.-->

</fieldset>

<fieldset>
    <legend>Informacion Extra</legend>

    <label for="telefono">Telefono:</label>
    <input 
    type="text" 
    id="telefono" 
    value="<?php echo san($vendedor->telefono); ?>" 
    name="vendedor[telefono]" 
    placeholder="Telefono Vendedor(a)"> <!---name premite ver los valores del input.-->

 </fieldset>