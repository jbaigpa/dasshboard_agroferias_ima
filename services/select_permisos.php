<?php if($_SESSION['level']=='500' || $_SESSION['level']=='800' || $_SESSION['level'] == '1000'){ ?>
	<div class="form-group">
		<label for="level">* Nivel de Permisos:</label>
		<select tabindex="9" name="level" id="level" class="form-control">
			<option value="">- seleccione -</option>					   
			<option <?php  if($rowGetUser['level']=='100'){echo "selected";} ?> value="100">Visor (Estadisticas)</option>
			<option <?php  if($rowGetUser['nivel']=='300'){echo "selected";} ?> value="300">Publicador de Avisos y Alertas</option>
			<option <?php  if($rowGetUser['level']=='500'){echo "selected";} ?> value="500">Coordinador (Puede gestionar reportes)</option>
			<option <?php  if($rowGetUser['level']=='90'){echo "selected";} ?> value="90">Operador Telecom y Monitoreo</option>
			
			<?php if($_SESSION['level'] == '1000'){ ?>							
				<option <?php  if($rowGetUser['level'] == '800'){echo "selected";} ?> value="800">Administrador</option>
				<option <?php if($rowGetUser['level']=='1000'){echo "selected";} ?> value="1000">Super Administrador</option>
			<?php } ?>
			
		</select>
	</div>
<?php } ?>