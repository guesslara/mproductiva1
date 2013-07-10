<?      
	session_start();
	session_regenerate_id(true);
	include("../../includes/txtApp.php");
	include("../../clases/regLog.php");
	$objLog=new regLog();
	$objLog->consulta($_SESSION[$txtApp['session']['loginUsuario']],date("Y-m-d"),date("H:i:s"),$_SERVER['REMOTE_ADDR'],"ASIGNACIONES",$_SESSION[$txtApp['session']['origenSistemaUsuario']]);
	if(!isset($_SESSION[$txtApp['session']['idUsuario']])){
		echo "<script type='text/javascript'> alert('Su sesion ha terminado por inactividad'); window.location.href='../mod_login/index.php'; </script>";
		exit;
	}
?>
<link rel="stylesheet" type="text/css" href="css/estilosEmpaque.css" />
<script type="text/javascript" src="js/funcionesEnsamble.js"></script>
<script type="text/javascript" src="../../clases/jquery-1.3.2.min.js"></script>
<!--Inclusion de los tabs-->
<script type="text/javascript" src="../../recursos/tabs/js/tabs.js"></script>
<link rel="stylesheet" type="text/css" href="../../recursos/tabs/css/tabs.css" />
<!--Fin inclusion de los tabs-->
<!--calendario-->
<link rel="stylesheet" type="text/css" media="all"  href="js/calendar-green.css"  title="win2k-cold-1" />
<!-- librería principal del calendario -->
<script type="text/javascript" src="js/calendar.js"></script>
<!-- librería para cargar el lenguaje deseado -->
<script type="text/javascript" src="js/calendar-es.js"></script>
<!-- librería que declara la función Calendar.setup, que ayuda a generar un calendario en unas pocas líneas de código -->
<script type="text/javascript" src="js/calendar-setup.js"></script>
<!--fin calendario-->
<script type="text/javascript">
	$(document).ready(function(){
		redimensionar();
		
		$("#left").click(function(){			
		  $("#contenedorTabs").animate({"left": "+=100px"}, "fast");
		  verificaMovIzq();	  
		  posicionReal = $("#contenedorTabs").offset();
		  alert(posicionReal.left);
		});
	
		$("#right").click(function(){
		  $("#contenedorTabs").animate({"left": "-=100px"}, "fast");
		  verificaMovDer();  
		});
	});
	
	function redimensionar(){
		var altoDiv=$("#contenedorEnsamble3").height();
		var anchoDiv=$("#contenedorEnsamble3").width();
		var altoCuerpo=altoDiv-3;		
		$("#infoEnsamble3").css("height",altoCuerpo+"px");
	}
	
	window.onresize=redimensionar;

	document.onkeypress=function(elEvento){
		var evento=elEvento || window.event;
		var codigo=evento.charCode || evento.keyCode;
		var caracter=String.fromCharCode(codigo);
		if(codigo==27){
			cerrarVentanaValidacion();
		}
	}
//setInterval("procesarDatosGrid()",5000);
</script>
<!--<div id="cargadorEmpaque" class="cargadorEmpaque">Cargando...</div>-->
<input type="hidden" name="txtProcesoEmpaque" id="txtProcesoEmpaque" value="<?=$proceso;?>" />
<input type="hidden" name="txtProcesoEmpaqueEnvio" id="txtProcesoEmpaqueEnvio" value="<?=$proceso1;?>" />
<input type="hidden" name="txtIdUsuarioEmpaque" id="txtIdUsuarioMatriz" value="<?=$_SESSION[$txtApp['session']['idUsuario']];?>" />
<div id="contenedorEnsamble">
	<div id="contenedorEnsamble3">		
		<div id="infoEnsamble3" style="overflow: auto;">
			
		</div>
	</div>
</div>
<div id="ventanaDatosABuscar" class="ventanaDialogo" style="display:block;">
	<div id="barraTitulo1" style="height: 19px;padding: 5px;background: #000;color: #FFF;font-size: 12px;">Buscar...<div id="btnCerrar" style="float: right;"><a href="#" onclick="cerrarVentana('ventanaDialogo')" title="Cerrar Ventana Dialogo"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="opcionesBusqueda" style="border: 0px solid #FF0000;width: 598px;height: 269px;overflow: auto;"><br>
		<table border="0" cellpadding="1" cellspacing="1" width="575" style="font-size: 10px;margin: 4px;background: #FFF;">
			<tr>
				<td width="100">#:</td>
				<td width="475">
					<input type="text" readonly="readonly" value="" style="width: 65px;" name="txtBNoEmpleado" id="txtBNoEmpleado">
					<input type="button" value="..." onclick="abrir('buscarEmpleado','busqueda')" >
				</td>
			</tr>
			<tr>
				<td>Nombre:</td>
				<td><div id="nombreCompletoABuscar" style="width: 250px;height: 25px;font-size: 12px;">Nombre del Empleado</div></td>
			</tr>				
			<tr>
				<td>Fecha 1</td>
				<td>
					<input type="text" name="busquedaRegistro1" id="busquedaRegistro1" style="width: 150px;" value="" readonly="readonly">
					<input type="button" id="lanzadorB1"  value="..." />
					<!-- script que define y configura el calendario-->
					<script type="text/javascript">
					    Calendar.setup({
						inputField     :    "busquedaRegistro1",      // id del campo de texto
						ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "lanzadorB1"   // el id del botón que lanzará el calendario
					    });
					</script>&nbsp;y&nbsp;
					Fecha 2&nbsp;<input type="text" name="busquedaRegistro2" id="busquedaRegistro2" style="width: 150px;" value="" readonly="readonly">
					<input type="button" id="lanzadorB2"  value="..." />
					<!-- script que define y configura el calendario-->
					<script type="text/javascript">
					    Calendar.setup({
						inputField     :    "busquedaRegistro2",      // id del campo de texto
						ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
						button         :    "lanzadorB2"   // el id del botón que lanzará el calendario
					    });
					</script>&nbsp;&nbsp;&nbsp;
					
				</td>
			</tr>
			<tr><td colspan="2"><hr style="background:#CCC;"></td></tr>
			<tr>
				<td colspan="2" style="text-align: right;"><input type="button" value="Buscar..." onclick="buscarDatosMatriz();" /></td>
			</tr>			
		</table>
	</div>
</div>
<div id="ventanaDialogo" class="ventanaDialogo" style="display:none;z-index:999;">
	<div id="barraTitulo1" style="height: 15px;padding: 5px;background: #000;color: #FFF;font-size: 12px;">Opciones...<div id="btnCerrar" style="float: right;"><a href="#" onclick="cerrarVentana('ventanaDialogo')" title="Cerrar Ventana Dialogo"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="msgVentanaDialogo" class="msgVentanaDialogo" style="border: 0px solid #FF0000;width: 99.5%;height: 272px;overflow: auto;"></div>
</div>
<div id="buscarEmpleado" style="border:1px solid #000;background-color:#FFF;height:300px;width:600px;left: 50%;top: 50%;margin-left: -300px;margin-top: -150px;position:absolute; display: none;z-index: 998;"  >
	<div id="barraTituloBuscar" class="barraTitulo1VentanaDialogoValidacion">Seleccionar...<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentana('buscarEmpleado');" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="listadoResultados" style="border:1px solid #CCC; margin:4px; font-size:10px;height:87%; overflow:hidden;"><br><br>
		<center>
		<input type="hidden" name="txtOpcionBusqueda" id="txtOpcionBusqueda">
		<form>
		Buscar:<input type="text" name="buscar"  id="buscar" onkeyup="buscarEmpleado();"></i>
		 <input type="button" name="buscar" id="buscar"value="buscar" onclick="">
		</form></center>
		<div id="ListarEmpleados" style="border: 0px solid #ff0000;background:#fff; height: 75.8%;width: 99%;font-size:12px;margin:3px;overflow: auto;"></div>		
	</div>
</div>

<div id="transparenciaGeneral" style="display:none;">	
	<div id="divGuardadoMatriz" class="ventanaDialogo" style="height: 150px;">
    		<div id="barraTitulo1VentanaDialogo">Informaci&oacute;n</div>
		<div style="height: 120px;border: 0px solid #FF0000;overflow: auto;"></div>		
	</div>
</div>




<div id="formularioOpciones" class="transparenciaGeneral" style="display:none;">	
	<div id="capturaCaja" class="ventanaDialogoCapturaFinal">
    		<div id="barraTitulo1VentanaDialogoCapturaFinal">Formulario</div>
			<div id="contenidoFormularioOpciones" style="border: 0px solid #ff0000;height: 368px;width:598px;overflow: auto;"></div>
		</div>
	</div>
</div>

<div id="formularioOpciones2" class="transparenciaGeneral" style="display:none;">	
	<div id="capturaCaja" class="ventanaDialogoCapturaFinal" style="z-index: 200;">
    		<div id="barraTitulo1VentanaDialogoCapturaFinal">Formulario 2</div>
			<div id="contenidoFormularioOpciones2" style="border: 0px solid #ff0000;height: 368px;width:598px;overflow: auto;"></div>
		</div>
	</div>
</div>


<div id="transparenciaGeneral1" class="transparenciaGeneral" style="display:none;">
	<div id="divListadoCapturaValidacion" class="ventanaDialogo">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n de la Captura<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentanaValidacion()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueValidacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
	</div>
</div>	
<div id="divVerificacionEquipoValidacion" class="ventanaDialogoVerificacionEquipoValidacion" style="display:none;">
	<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n <input type="hidden" name="txtVentanaValidacion" id="txtVentanaValidacionID" value=""><div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoValidado()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="listadoEmpaqueVerificacionValidacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
</div>
<div id="divVerificacionEquipoEntregas" class="ventanaDialogoVerificacionEquipoEntregas" style="display:none;">
	<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n <input type="hidden" name="txtVentanaValidacion" id="txtVentanaValidacionID" value=""><div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoEntrega()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
	<div id="listadoEmpaqueVerificacionValidacionEntregas" style="border:1px solid #CCC; margin:4px; font-size:10px;height:90%; overflow:auto;"></div>
</div>
<div id="divVerificacionEquipoEnviado" class="ventanaDialogoVerificacionEquipoEnviado" style="display:none;">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n<div id="btnCerrarVentanaDialogo"><a href="#" onclick="ventanaDialogoVerificacionEquipoEnviado()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueVerificacionEnviado" style="border:1px solid #CCC; margin:4px; font-size:10px;height:93%; overflow:auto;"></div>
	</div>
<div id="notificaciones" style=" display:none; text-align:center;position:absolute; width:300px; height:35px;z-index:1000;background:#F5F6CE; color:#000; left:40%; top:100px; padding:10px;"></div>
<div id="cargadorGeneral" class="transparenciaGeneral" style="display: none;">
	<div id="cargador" style=" display:block; text-align:center;position:absolute; width:150px; height:35px;z-index:1000;background:#FFF; color:#000; left:40%; top:300px; padding:10px;"><img src="../../img/cargador.gif" border="0"></div>
</div>
<div id="transparenciaGeneral10" class="transparenciaGeneral" style="display:none;">
	<div id="divListadoCapturaFinalizacion" class="ventanaDialogoFinalizacion">
		<div id="barraTitulo1VentanaDialogoValidacion" class="barraTitulo1VentanaDialogoValidacion">Informaci&oacute;n de la Captura<div id="btnCerrarVentanaDialogo"><a href="#" onclick="cerrarVentanaFinalizacion1()" title="Cerrar Ventana"><img src="../../img/close.gif" border="0" /></a></div></div>
		<div id="listadoEmpaqueFinalizacion" style="border:1px solid #CCC; margin:4px; font-size:10px;height:87%; overflow:auto;"></div>
	</div>
</div>
<?
include ("../../includes/pie.php");
?>