<?
	session_start();
	class modeloEnsamble{

		private function conectarBd(){
			require("../../includes/config.inc.php");
			$link=mysql_connect($host,$usuario,$pass);
			if($link==false){
				echo "Error en la conexion a la base de datos";
			}else{
				mysql_select_db($db);
				return $link;
			}				
		}
		
		public function conectar(){
			require("../../includes/config.inc.php");
			$conexion=@mysql_connect($host,$usuario,$pass) or die ("no se pudo conectar al servidor<br>".mysql_error());
			if(!$conexion){
				echo "Error al conectarse al servidor";	
			}else{
				@mysql_select_db('iqe_rrhh_2010') or die ("No se puede conectar a la base de datos<br>".mysql_error());
			}				
    			return $conexion;
		}
		
		public function actualizaListadoProductos(){
			$sqlProducto="SELECT * FROM SAT_PRODUCTO";
			$resProducto=mysql_query($sqlProducto,$this->conectarBd());
			if(mysql_num_rows($resProducto)==0){
			    echo "No hay productos Capturados";
			}else{
?>
				<select name="cboProductoActividad" id="cboProductoActividad">						       
				     <option value="">Selecciona</option>
<?
				while($rowProducto=mysql_fetch_array($resProducto)){
?>
				     <option value="<?=$rowProducto["id_producto"];?>"><?=$rowProducto["nom_producto"]." ".$rowProducto["modelo"];?></option>  
<?
				}
?>
				</select>
<?
			}
		}
		
		public function guardarProducto($nombreProd,$modeloProd){
			$sqlProd="INSERT INTO SAT_PRODUCTO (nom_producto,modelo) VALUES ('".$nombreProd."','".$modeloProd."')";
			$resProd=mysql_query($sqlProd,$this->conectarBd());
			if($resProd){
				echo "<script type='text/javascript'> alert('Producto Guardado'); cerrarVentana('formularioOpciones2'); actualizarListadoProductos();</script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar la informacion del Producto'); </script>";
			}
		}
		
		public function formNuevoProducto(){
?>
			<br><table border="0" align="center" cellpadding="1" cellspacing="1" width="500" style="font-size: 10px;">
				<tr>
					<td colspan="2" style="background: #666;color: #FFF;height: 15px;padding: 5px;">Nuevo Producto</td>
				</tr>
				<tr>
					<td width="50">Nombre del Producto</td>
					<td width="50"><input type="text" name="txtNomProducto" id="txtNomProducto"></td>
				</tr>
				<tr>
					<td>Modelo</td>
					<td><input type="text" name="txtModeloProducto" id="txtModeloProducto"></td>
				</tr>
				<tr>
					<td colspan="2"><hr style="background: #CCC;"></td>
				</tr>
				<tr>
					<td colspan="2" style="text-align: right;"><input type="button" value="Cancelar" onclick="cerrarVentana('formularioOpciones2')"><input type="button" onclick="guardarProducto()" value="Guardar Producto"></td>
				</tr>
			</table>
<?
		}
		
		public function actualizarStatusActividad($valores,$idProceso,$idUsuario){
			$arrayClaves=explode("|",$valores);			
			for($i=0;$i<count($arrayClaves);$i++){
				$arrayClaves2=explode(",",$arrayClaves[$i]);				
				//se arma la consulta
				if($arrayClaves2[1]=="mas"){
					$arrayClaves2[1]="+";
				}else{
					$arrayClaves2[1]="-";
				}

				$sqlActAct="UPDATE ACTIVIDAD_STATUS SET tiempo='".$arrayClaves2[0]."', operador='".$arrayClaves2[1]."' WHERE id_act_status='".$arrayClaves2[2]."'";
				$resActAct=mysql_query($sqlActAct,$this->conectarBd());
				if($resActAct){
					echo "<br>&nbsp;&nbsp;Actualizacion Realizada";
				}else{
					echo "<br>&nbsp;&nbsp;Error al Actualizar el Registro";
				}
			}
			echo "<br><br><div style='text-align:center;height:15px;padding:5px;'>Presione el boton Cerrar Ventana para Finalizar la Actualizaci&oacute;n</div><br><br>";
			echo "<div style='text-align:center;height:15px;padding:5px;'><a href='#' onclick=\"cerrarVentana('formularioOpciones');listarActividades('".$idProceso."','consulta','".$idUsuario."')\" title='Cerrar ventana'>Cerrar Ventana</a></div>";
		}		
		public function mostrarFormMetrica($ultimoId,$id_proceso,$idUsuario){
			$sqlNAct="SELECT * FROM SAT_ACTIVIDAD WHERE id_actividad='".$ultimoId."'";
			$resNAct=mysql_query($sqlNAct,$this->conectarBd());
			$rowNAct=mysql_fetch_array($resNAct);
			$sqlStatus="SELECT * FROM ACTIVIDAD_STATUS INNER JOIN SAT_STATUS ON ACTIVIDAD_STATUS.id_status = SAT_STATUS.id_status WHERE id_actividad='".$ultimoId."'";
			$resStatus=mysql_query($sqlStatus,$this->conectarBd());
			$CuentaStatus=mysql_num_rows($resStatus);
			if($CuentaStatus==0){
				echo "No existe Informacion a mostrar";
			}else{
				$toF=$CuentaStatus+2;
?>
			<table border="0" cellpadding="1" cellspacing="1" width="580" style="margin: 5px;font-size: 10px;">
				<tr>
					<td colspan="5" style="background: #666;color: #FFF;font-weight: bold;height: 15px;padding: 5px;">M&eacute;trica - &nbsp;<?=$rowNAct["nom_actividad"];?></td>
				</tr>
				<tr>
					<td colspan="5">
						<div style="height: 15px;padding: 5px;background: #CCC;font-weight: bold;">NOTA: El tiempo de la metrica debe ser expresado en minutos</div>
					</td>
				</tr>
				<tr>
					<td rowspan="2" style="text-align: left;border: 1px solid #CCC;background: #f0f0f0;">Status</td>
					<td colspan="4" style="text-align: center;border: 1px solid #CCC;background: #f0f0f0;">M&eacute;trica</td>					
				</tr>
				<tr>	
					<td style="text-align: center;border: 1px solid #CCC;background: #f0f0f0;">Pz</td>
					<td style="text-align: center;border: 1px solid #CCC;background: #f0f0f0;">Tiempo</td>
					<td style="text-align: center;border: 1px solid #CCC;background: #f0f0f0;">Operación</td>
				</tr>
<?
			$i=0;
			while($rowMetricas=mysql_fetch_array($resStatus)){
				$nombreStatus="status".$i;
				$nombreStatus1="txtStatus".$i;
				$nombreIdStatus="txtIdStatus".$i;
				$nombreButt="button".$i;
				$idSta="idSt".$i;
				if($rowMetricas["tiempo"]=="0.000"||$rowMetricas["operador"]=="+"){
					if($rowMetricas["nom_status"]=="SCRAP"){
						?><tr>
							<td width="150" style="text-align: left;border-bottom: 1px solid #CCC;"><input type="hidden" name="<?=$idSta?>" id="<?=$idSta?>" value="<?=$rowMetricas["id_status"]?>"/><input type="checkbox" name="ChAcS" id="ChAcS" onclick="checkActivar('ChAcS','<?=$nombreStatus1?>','0')"/><?=$rowMetricas["nom_status"]?><input type="hidden" name="<?=$nombreIdStatus;?>" id="<?=$nombreIdStatus;?>" value="<?=$rowMetricas["id_act_status"];?>"></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="text" name="<?=$nombreStatus;?>" id="<?=$nombreStatus;?>" value="1 PZ" style="text-align: center;width: 50px;" readonly></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="text" name="<?=$nombreStatus1;?>" id="<?=$nombreStatus1;?>" value="0" style="text-align: center;width: 50px;"readonly></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="button" id="<?=$nombreButt;?>" name="<?=$nombreButt;?>" value="+" style="text-align: center;width: 20px;" onclick="cambiaOpe('<?=$nombreButt;?>');"/></td>
						</tr><?
					}else{
?>
						<tr>
							<td width="150" style="text-align: left;border-bottom: 1px solid #CCC;"><input type="hidden" name="<?=$idSta?>" id="<?=$idSta?>" value="<?=$rowMetricas["id_status"]?>"/>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$rowMetricas["nom_status"]?><input type="hidden" name="<?=$nombreIdStatus;?>" id="<?=$nombreIdStatus;?>" value="<?=$rowMetricas["id_act_status"];?>"></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="text" name="<?=$nombreStatus;?>" id="<?=$nombreStatus;?>" value="1 PZ" style="text-align: center;width: 50px;" readonly></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="text" name="<?=$nombreStatus1;?>" id="<?=$nombreStatus1;?>" value="" style="text-align: center;width: 50px;"></td>
							<td width="100" style="text-align: center;border-bottom: 1px solid #CCC;"><input type="button" id="<?=$nombreButt;?>" name="<?=$nombreButt;?>" value="+" style="text-align: center;width: 20px;" onclick="cambiaOpe('<?=$nombreButt;?>');"/></td>
						</tr>
<?
					}
				}else{print("Aqui esta");}
				$i+=1;
			}
?>
				<tr>
					<td colspan="5"><hr style="background: #666;"><input type="hidden" id="hdnContadorResp" name="hdnContadorResp" value="<?=$i;?>"></td>
				</tr>
				<tr>
					<td colspan="5" style="text-align: right;"><input type="button" value="Guardar Datos" onclick="guardarDatosExtraActividad('<?=$id_proceso;?>','<?=$idUsuario;?>')"></td>
				</tr>
			</table>
<?
			}
		}
		
		public function actualizarStatus(){
			$sqlActStatus="SELECT * FROM SAT_STATUS WHERE status='Activo'";
			$resActstatus=mysql_query($sqlActStatus,$this->conectarBd());
			if(mysql_num_rows($resActstatus)==0){
				echo "No hay status Capturados";
			}else{
				$i=0;
				while($rowStatus=mysql_fetch_array($resActstatus)){
					$id="cboStatus".$i;
					if($rowStatus["nom_status"]=="SCRAP"){
								?><input type="checkbox" name="cboStatus" id="<?=$id;?>" value="<?=$rowStatus["id_status"];?>" checked="checked" readonly="readonly"><label for="<?=$id;?>"><?=$rowStatus["nom_status"];?></label><br>								<?
					}else{
?>
					<input type="checkbox" name="cboStatus" id="<?=$id;?>" value="<?=$rowStatus["id_status"];?>"><label for="<?=$id;?>"><?=$rowStatus["nom_status"];?></label><br>
<?					}
					$i+=1;
				}
			}
		}
		
		public function guardarNuevoStatus($status,$div){
			$sqlStatus="INSERT INTO SAT_STATUS (nom_status,status) VALUES ('".strtoupper($status)."','Activo')";
			$resStatus=mysql_query($sqlStatus,$this->conectarBd());
			if($resStatus){
				echo "<script type='text/javascript'> alert('Status Guardado'); actualizarStatus('$div'); </script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar el Status'); </script>";
			}
		}
		
		public function eliminarResponsable($no_empleado,$origen,$idOrigen,$idOrigen1,$idUsuario){
			if($origen=="proyecto"){
				$sqlElimina="DELETE FROM ASIG_PRO WHERE id_empleado='".$no_empleado."' AND id_proyecto='".$idOrigen."'";
				$resElimina=mysql_query($sqlElimina,$this->conectarBd());
				if($resElimina){
					echo "<script type='text/javascript'> alert('Registro Eliminado'); listarProyectos('".$idUsuario."','consulta'); </script>";
				}else{
					echo "<script type='text/javascript'> alert('Error al eliminar el Registro'); </script>";
				}
			}else if($origen=="proceso"){
				$sqlElimina="DELETE FROM ASIG_PROC WHERE id_empleado='".$no_empleado."' AND id_proceso='".$idOrigen1."'";
				$resElimina=mysql_query($sqlElimina,$this->conectarBd());
				if($resElimina){
					echo "<script type='text/javascript'> alert('Registro Eliminado'); listarProcesos('".$idOrigen."','".$idUsuario."','consulta'); </script>";
				}else{
					echo "<script type='text/javascript'> alert('Error al eliminar el Registro'); </script>";
				}
			}else if($origen=="actividad"){
				$sqlElimina="DELETE FROM ASIG_ACT WHERE id_empleado='".$no_empleado."' AND id_actividad='".$idOrigen1."'";
				$resElimina=mysql_query($sqlElimina,$this->conectarBd());
				if($resElimina){
					echo "<script type='text/javascript'> alert('Registro Eliminado'); listarActividades('".$idOrigen."','consulta','".$idUsuario."'); </script>";
				}else{
					echo "<script type='text/javascript'> alert('Error al eliminar el Registro'); </script>";
				}
			}
			
		}
		
		public function guardarAsignacion($tabla,$idEmpleado,$accionForm,$valorForm,$parametroOpcional,$idUsuario){
			//echo "<br>".$tabla;
			if($tabla=="ASIG_PROC"){
				 $sqlAsig="INSERT INTO ".$tabla."(id_empleado,status,fecha_asig,hora_asig,id_proceso) VALUES ('".$idEmpleado."','Activo','".date("Y-m-d")."','".date("H:i:s")."','".$valorForm."')";
				 $resAsig=mysql_query($sqlAsig,$this->conectarBd());
				 if($resAsig){
					echo "<br>Registro Guardado";
					echo "<script type='text/javascript'> alert('Asignacion guardada'); cerrarVentana('ventanaDialogo'); listarProcesos('".$parametroOpcional."','".$idUsuario."','consulta');</script>";
				 }else{
					    echo "<br>Error al Guardar la Asignacion";
				 }
			}else if($tabla=="ASIG_ACT"){
				 $sqlAsig="INSERT INTO ".$tabla."(id_empleado,status,fecha_asig,hora_asig,id_actividad) VALUES ('".$idEmpleado."','Activo','".date("Y-m-d")."','".date("H:i:s")."','".$valorForm."')";
				 $resAsig=mysql_query($sqlAsig,$this->conectarBd());
				 if($resAsig){
					echo "<br>Registro Guardado"; //listarActividades(idProceso)
					echo "<script type='text/javascript'> alert('Asignacion guardada'); cerrarVentana('ventanaDialogo'); listarActividades('".$parametroOpcional."','consulta','".$idUsuario."');</script>";
				 }else{
					    echo "<br>Error al Guardar la Asignacion";
				 }
			}else if($tabla=="ASIG_PRO"){
				 $sqlAsig="INSERT INTO ".$tabla."(id_empleado,status,fecha_asig,hora_asig,id_proyecto) VALUES ('".$idEmpleado."','Activo','".date("Y-m-d")."','".date("H:i:s")."','".$valorForm."')";
				 $resAsig=mysql_query($sqlAsig,$this->conectarBd());
				 if($resAsig){
					echo "<br>Registro Guardado";
					echo "<script type='text/javascript'> alert('Asignacion guardada'); cerrarVentana('ventanaDialogo'); listarProyectos('".$idUsuario."','consulta');</script>";
				 }else{
					    echo "<br>Error al Guardar la Asignacion";
				 }
			}
			echo "<br>".$sqlAsig;
		      //echo "<script type='text/javascript'> cerrarVentana('ventanaDialogo') </script>";
		}
		
		public function formAsignacion($accion,$idAccion,$valor,$parametroOpcional,$idUsuario){
			$origi=ucwords($idAccion);
			if($idAccion=="proyecto"){
				$sqlProyectos="SELECT * FROM SAT_PROYECTO WHERE status='Activo' AND id_proyecto='".$valor."'";
				$resProyectos=mysql_query($sqlProyectos,$this->conectarBd());
				$rowProyectos=mysql_fetch_array($resProyectos);
			}
?>
			<FORM id="asig" >
				<input type="hidden" name="hdnAccion" id="hdnAccion" value="<?=$idAccion;?>">
				<input type="hidden" name="hdnValor" id="hdnValor" value="<?=$valor;?>">
				<input type="hidden" name="hdnParametroOpcional" id="hdnParametroOpcional" value="<?=$parametroOpcional;?>">
				<div style="border: 1px solid #CCC;background: #f0f0f0;height: 15px;padding: 5px;font-size: 12px;font-weight: bold;">Asignar Responsable a <?=$origi." ".$rowProyectos["nom_proyecto"];?></div><br><br>
					<table align="center" style="font-size: 12px;">			     
						<tr>
							<td colspan="2">Responsable (s)</td>
							<td><!--<a href="#" onclick="anadeR()" style="color:blue;">A&ntilde;adir Responsable</a>--></td>
						</tr>
						<tr>
							<td colspan="3"><hr style="background: #999;"></td>
						</tr>
						<tr>
							<td>Id Empleado:</td>
							<td><input type="text" name="resP0" id="resP0" readonly="" class="<?=$clase_obligaria?>"></td>
							<td> <a href="#" onclick="abrir('buscar');" style="color:blue;"> Buscar</a></td>
						</tr>			 
						<tr>
							<td>Nombre:</td>
							<td><input type="text" style="size: auto;" name="nresP0" id="nresP0" readonly="" value="" class="<?=$clase_obligaria?>"></td>
							<td>&nbsp;</td>
						</tr>				 
						</tr>
							<td colspan="3"><div id="otroR_0"></div>				
						</tr>		 
						<tr>
							<td>&nbsp;</td>
							<td>&nbsp;</td>
						</tr>
						<tr>
							   <td colspan="3"><hr style="background: #999;"/></td>
						</tr>
						<tr>
							<td colspan="3" style="text-align: right;">
						       <input type="button" onclick="cerrarVentana('ventanaDialogo')" name="Cancelar" value="Cancelar" />
<?
			 $name=strtolower(str_replace("SAT_","",$contado));
			 $name1="id_".$name;
			 if($idAccion=="proyecto"){
				 $ti="ASIG_PRO";
?>
					    <input type="button" name="Guardar" value="Guardar" onclick="VALIDAR('<?=$ti?>','<?=$idUsuario?>')"> </h5>
<?
			 }
			 if($idAccion=="proceso"){
				 $ti="ASIG_PROC";
?>
					    <input type="button" name="Guardar" value="Guardar" onclick="VALIDAR('<?=$ti?>','<?=$idUsuario?>')"> </h5>
<?
			 }
			 if($idAccion=="actividad"){
				 $ti="ASIG_ACT";
			 //print_r($ti);
			 //exit;
?>
					    <input type="button" name="Guardar" value="Guardar" onclick="VALIDAR('<?=$ti?>','<?=$idUsuario?>');"> </h5>
<?
			 }
			 
?>      
					    </td>
				 </tr>
		    </table>	       
		    </FORM>
		<div id="resultadoGuardado"></div>
<?
	  
     }

		public function consultarempleado($tecla){
			$esta="SELECT * FROM cat_personal  WHERE nombres LIKE '".$tecla."%'  ";
			$estaeje=mysql_query($esta,$this->conectar()) or die(mysql_error());
?>
			<table align="center" BORDER="0" CELLPADDING="0" CELLSPACING="0" style="font-size: 12px;">
				<tr>
					<td colspan="15"><center><strong>EMPLEADOS</strong></center></td>
				</tr>
				<tr>
					<td class="cabeceraTitulosTabla">N° Empleado</td>
					<td class="cabeceraTitulosTabla">Nombre</td>
					<td class="cabeceraTitulosTabla">Apellido Paterno</td>
					<td class="cabeceraTitulosTabla">Apellido Materno</td>	 
			       </tr>
<?         
			while($fi=mysql_fetch_array($estaeje)){     
				$noempleado=$fi["no_empleado"];
				$nombres=$fi["nombres"];
				$apaterno=$fi["a_paterno"];
				$amaterno=$fi["a_materno"];
				$pais=$fi["pais"];
				//$mandar="controladorasig.php?action=recibodatos&no_empleado=".$noempleado."";
				$mandar="controladorasig.php?action=recibodatos&no_empleado=".$noempleado."";
?>
				<tr>  
					<td class="resultadosTablaBusqueda1"><a href="#" style="color: blue;" onclick="insertarEmpleado('<?=$noempleado;?>','<?=$nombres;?>','<?=$apaterno;?>','<?=$amaterno?>')" ><?=$noempleado;?></a></td>
					<td class="resultadosTablaBusqueda1"><?=$nombres;?></font></td>
					<td class="resultadosTablaBusqueda1"><?=$apaterno;?></td>
					<td class="resultadosTablaBusqueda1"><?=$amaterno;?></td>
				</tr>  
	  <script type="text/javascript">
		    function seguro(){
		    	
		    if(!confirm("esta seguro que desea eliminar el campo")){
			history.go(-1)
			return ""
		    }
		    }
		    
		    
		    
		  </script>
     <?
     }
     ?>
			</table>
     <?

		}
		
		public function guardarActividad($id_proceso,$nombre,$descripcion,$id_producto,$status,$idUsuario){
			$sql="INSERT INTO SAT_ACTIVIDAD (nom_actividad,id_proceso,id_producto,status,descripcion) VALUES ('".$nombre."','".$id_proceso."','".$id_producto."','Activo','".$descripcion."')";
			$res=mysql_query($sql,$this->conectarBd());
			$status=explode(",",$status);		
			if($res){
				//se recupera el ultimo id insertado en la actividad				
				$ultimoId=mysql_query("select last_insert_id() AS ultimoId",$this->conectarBd());
				$rowUltimoId=mysql_fetch_array($ultimoId);								
				for($i=0;$i<count($status);$i++){
					$sqlActStatus="INSERT INTO ACTIVIDAD_STATUS (id_actividad,id_status) VALUES ('".$rowUltimoId["ultimoId"]."','".$status[$i]."')";//se ejecuta la consulta sql
					$resActStatus=mysql_query($sqlActStatus,$this->conectarBd());
					echo "<script type='text/javascript'> mostrarFormMetrica('".$rowUltimoId["ultimoId"]."','".$id_proceso."','".$idUsuario."'); </script>";//se manda llamar al siguiente formulario
					if($resActStatus==false){
						echo "<script type='text/javascript'> alert('Ocurrio un error al guardar el status con la Actividad');</script>";	
					}
				}
				echo "<script type='text/javascript'> alert('Actividad Guardada'); listarActividades('".$id_proceso."','consulta','".$idUsuario."');</script>";	
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar al Proceso'); </script>";	
			}
			
		}
		
		public function nuevaActividad($id_proceso,$idUsuario){
			$sqlProducto="SELECT * FROM SAT_PRODUCTO";
			$resProducto=mysql_query($sqlProducto,$this->conectarBd());
			$sqlStatus="SELECT * FROM SAT_STATUS";
			$resStatus=mysql_query($sqlStatus,$this->conectarBd());
?>
				<form name="frmNuevaActividad" id="frmNuevaActividad">
				<input type="hidden" name="hdnProcesoActividad" id="hdnProcesoActividad" value="<?=$id_proceso?>">
				<table border="0" align="center" cellpadding="1" cellspacing="1" width="540" style="font-size: 12px;border: 1px solid #666;">
					<tr>
						<td colspan="2" style="height: 15px;padding: 5px;background: #666;color: #FFF;">Nueva Actividad</td>						
					</tr>
					<tr>
						<td>Nombre</td>
						<td><input type="text" name="txtNombreAct" id="txtNombreAct"></td>
					</tr>					
					<tr>						
						<td>Producto</td>
						<td><div id="divProductoS" style="float: left;">
<?
				 if(mysql_num_rows($resProducto)==0){
					    echo "No hay productos Capturados";
				 }else{
?>
					    <select name="cboProductoActividad" id="cboProductoActividad" style="width: 233px;">						       
						     <option value="">Selecciona</option>
<?
					    while($rowProducto=mysql_fetch_array($resProducto)){
?>
						     <option value="<?=$rowProducto["id_producto"];?>"><?=$rowProducto["nom_producto"]." ".$rowProducto["modelo"];?></option>  
<?
					    }
?>
					    </select>
<?
				 }
?>							
						</div>&nbsp;<div style="float: left;margin-top: 3px;margin-left: 5px;">[ <a href="#" onclick="agregaProducto()" title="Agregra Producto" style="color: blue;">Nuevo Producto</a> ]</div>
						</td>						
					</tr>					
					<tr>
						<td>Descripci&oacute;n</td>
						<td><textarea rows="3" cols="30" name="txtDescAct" id="txtDescAct"></textarea></td>
					</tr>					
					<tr>
						<td colspan="2"><hr style="background: #666;"</td>							
					</tr>
					<tr>
						<td colspan="2">Seleccione los status relacionados a la actividad&nbsp;[ <a href="#" onclick="agregarStatus('statusExistentes')" title="Agregar Status" style="color: blue;">Nuevo Status</a>]</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="statusExistentes" style="border: 1px solid #CCC;height: 100px;overflow: auto;background: #FFF;font-size: 10px;">
<?
					if(mysql_num_rows($resStatus)==0){
						echo "No hay status Capturados";
					}else{
						$i=0;
						while($rowStatus=mysql_fetch_array($resStatus)){
							$id="cboStatus".$i;
							if($rowStatus["nom_status"]=="SCRAP"){
								?><input type="checkbox" name="cboStatus" id="<?=$id;?>" value="<?=$rowStatus["id_status"];?>" checked="checked" readonly="readonly"><label for="<?=$id;?>"><?=$rowStatus["nom_status"];?></label><br>								<?
							}else{
?>
								<input type="checkbox" name="cboStatus" id="<?=$id;?>" value="<?=$rowStatus["id_status"];?>"><label for="<?=$id;?>"><?=$rowStatus["nom_status"];?></label><br>
<?
							}$i+=1;
						}
					}
?>
							</div>
						</td>
					</tr>
					<tr>
							<td colspan="2"><hr style="background: #666;"</td>
					</tr>
					<tr>
							<td colspan="2" style="text-align: right">
									<input type="button" onclick="cancelarCapturaActividad()" value="Cancelar">
									<input type="button" onclick="guardarActividad('<?=$idUsuario?>')" value="Siguiente">
							</td>
					</tr>
					<tr>
							<td>&nbsp;</td>
					</tr>
				</table></form>
<?
		}
		
		public function guardarProceso($id_proyecto,$nombre,$descripcion,$idUsuario){
			$sql="INSERT INTO SAT_PROCESO (nom_proceso,status,id_proyecto,descripcion) VALUES ('".$nombre."','Activo','".$id_proyecto."','".$descripcion."')";
			$res=mysql_query($sql,$this->conectarBd());
			if($res){
				echo "<script type='text/javascript'> alert('Proceso Guardado'); $('#formularioOpciones').hide(); listarProcesos('".$id_proyecto."','".$idUsuario."','consulta');</script>";	
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar al Proceso'); </script>";	
			}
		}
		
		public function nuevoProceso($id_proyecto,$idUsuario){
?>
			<br><br><input type="hidden" name="hdnProcesoProyecto" id="hdnProcesoProyecto" value="<?=$id_proyecto?>">
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="450" style="font-size: 12px;border: 1px solid #666;">
				<tr>
					<td style="height: 15px;padding: 5px;background: #666;color: #FFF;">Nuevo Proceso</td>
				</tr>
				<tr>
					<td>Nombre</td>
				</tr>
				<tr>
					<td><input type="text" name="txtNombreProc" id="txtNombreProc"></td>
				</tr>
				<tr>
					<td>Descripci&oacute;n</td>
				</tr>
				<tr>
					<td><textarea rows="3" cols="30" name="txtDescProc" id="txtDescProc"></textarea></td>
				</tr>
				<tr>
					<td><hr style="background: #666;"</td>
				</tr>
				<tr>
					<td style="text-align: right">
						<input type="button" onclick="cancelarCapturaProceso()" value="Cancelar">
						<input type="button" onclick="guardarProceso('<?=$idUsuario;?>')" value="Guardar Proceso">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
<?
		}
		
		private function dameNombreEmpleado($no_empleado,$origen,$idOrigen,$idOrigen1,$idUsuario){
			$sqlResp1="SELECT * FROM cat_personal WHERE no_empleado='".$no_empleado."'";//se construye la 2 consulta
			$resResp1=mysql_query($sqlResp1,$this->conectar());
			$rowResp1=mysql_fetch_array($resResp1);
			if($idUsuario==1||$idUsuario==0){
				echo "<div class='divNombre'><a href='#' onclick=\"eliminaResponsable('".$no_empleado."','".$origen."','".$idOrigen."','".$idOrigen1."','".$idUsuario."')\"><img src='../../img/icon_delete.gif' border='0' /></a>&nbsp;".$rowResp1["nombres"]." ".$rowResp1["a_paterno"]." ".$rowResp1["a_materno"]."</div>";
			}else{
				echo "<div class='divNombre'>&nbsp;".$rowResp1["nombres"]." ".$rowResp1["a_paterno"]." ".$rowResp1["a_materno"]."</div>";
			}
		}	
		
		public function listarActividades($id_proceso,$opt,$idUsuario){
			$sqlProc="SELECT * FROM SAT_PROCESO WHERE id_proceso='".$id_proceso."'";
			$resProc=mysql_query($sqlProc,$this->conectarBd());
			$rowProc=mysql_fetch_array($resProc);
			$status="Activo";

?>
		<?if($idUsuario==1 && $opt=="consulta"||$idUsuario==0 && $opt=="consulta"){?>
			<div id="barraA" style="height: 36px;background: #666;padding: 3px;">				
				<div class="opcionesEnsamble" onclick="nuevaActividad('<?=$id_proceso;?>','<?=$idUsuario?>');" title="Nuevo">Nueva Actividad</div>				
				<div class="opcionesEnsamble" onclick="listarActividades('<?=$id_proceso;?>','modifica','<?=$idUsuario?>')" title="Modifica Actividad">Modificar Actividad</div>				
			</div>
			<?}?>
			<input type="hidden" name="hdntxtAccion" id="hdntxtAccion" value="actividades">
			<input type="hidden" name="hdntxtValor" id="hdntxtValor" value="<?=$id_proceso;?>">
			<div style="clear: both;"></div>
			<div style="height: 15px;padding: 5px;font-size: 12px;text-align: left;margin-bottom: 5px;">Actividades del Proceso: <strong><?=$rowProc["nom_proceso"];?></strong></div>
			<div style="height: 15px;padding: 5px;font-size: 12px;text-align: center;margin-bottom: 5px;"><strong><?=strtoupper($opt);?> ACTIVIDADES</strong></div>
			<?if($opt=="modifica"){
			?><div id="barraTer" style="height: 36px;background: #fff;padding: 3px;clear:both;">
					<div class="opcionesEnsambleter" onclick="listarActividades('<?=$id_proceso;?>','consulta','<?=$idUsuario?>')" title="Consultar Actividad">Terminar Edición</div>	
			</div>
			<?}?>
			<div id="nuevaActividad" style="border: 1px solid #CCC;margin: 3px;background: #f0f0f0;margin-bottom: 10px;"></div>
			
<?
			//echo $sqlConsult="SELECT * FROM SAT_ACTIVIDAD where id_proceso='".$id_proceso."' AND status='".$status."'";
			if($opt=="consulta"){
				$sqlConsult="SELECT * FROM SAT_ACTIVIDAD INNER JOIN SAT_PRODUCTO ON SAT_ACTIVIDAD.id_producto = SAT_PRODUCTO.id_producto WHERE id_proceso = '".$id_proceso."' AND STATUS = '".$status."'";				
			}
			else{
				$sqlConsult="SELECT * FROM SAT_ACTIVIDAD INNER JOIN SAT_PRODUCTO ON SAT_ACTIVIDAD.id_producto = SAT_PRODUCTO.id_producto WHERE id_proceso = '".$id_proceso."'";
			}
			$resulta=@mysql_query($sqlConsult,$this->conectarBd()) or die(mysql_error());
			if(mysql_num_rows($resulta)==0){
				   echo "<br>( 0 ) Registros encontrados.<br>";
			}else{
				$color="#EEEEEE";
				while($row = mysql_fetch_array($resulta)){
					$sqlResp="SELECT * FROM ASIG_ACT WHERE id_actividad='".$row["id_actividad"]."' AND status='Activo'";					
					$resResp=mysql_query($sqlResp,$this->conectarBd());
					$sqlResp1="SELECT * FROM ACTIVIDAD_STATUS INNER JOIN SAT_STATUS ON ACTIVIDAD_STATUS.id_status = SAT_STATUS.id_status WHERE id_actividad='".$row["id_actividad"]."'";
					$resResp1=mysql_query($sqlResp1,$this->conectarBd());
					if($opt=="consulta"){
						$title="";
						$funOnC="";
					}else{
						$title="Da clic para modificar la actividad";
						$funOnC="modAct('".$row['id_actividad']."','".$id_proceso."','".$idUsuario."')";
					}
?>
					<div class="resultadosAvisos" style="margin: 3px;height: auto; background: <?=$color?>;" title="<?=$title?>" onclick="<?=$funOnC?>">
						<table border="0" cellpadding="1" cellspacing="1" width="98%" style="font-size: 10px;">
							<tr>
								<td width="10%">Actividad:</td>
								<td width="88%"><?=substr($row["nom_actividad"],0,30)."..."; ?></td>
								<?//}else{?>
								<!--<td width="88%"><input type="text" name="nomAc" id="nomAc" value="<?=$row["nom_actividad"]?>"/></td>-->
								<?//}?>
							</tr>
							<tr>
								<td>Descripcion:</td>
								<td><?=$row["descripcion"];?></td>
							</tr>
							<tr>
								<td>Producto</td>
								<td><?=$row["nom_producto"];?></td>
							</tr>
							<tr>
								<td>Status</td>
								<td>
<?
						if(mysql_num_rows($resResp1)==0){
							echo "<br>No existen Status Asociados";
						}else{
							while($rowResp1=mysql_fetch_array($resResp1)){
								if($rowResp1["id_status"]==1){
									if($rowResp1["tiempo"]!=0.000|| $rowResp1["tiempo"]!=0){
										?><strong><?=$rowResp1["operador"]?> <?=$rowResp1["nom_status"]?></strong><br><?
									}
									else{}
										
								}else{
									echo "<strong>".$rowResp1["operador"]." ".$rowResp1["nom_status"]."</strong><br>";
								}
							}
						}
?>
								</td>
							</tr>
							<?if($opt=="consulta"){?>
							<tr>
								<td colspan="2">Responsable(s):&nbsp;[ <a href="#" onclick="nuevaAsignacion('SAT_ACTIVIDAD','actividad','<?=$row["id_actividad"]?>','<?=$idUsuario?>','<?=$id_proceso;?>')" style="color:blue;text-decoration: none;" title="Responsable">Agregar Operario</a> ]</td>
							</tr>
							<tr>
								<td colspan="2">
<?
					if(mysql_num_rows($resResp)==0){
						echo "<span style='color:red;'>Personal no Asignado</span>";
					}else{
						echo "<div style='background:#FFF;height:100px;width:100%;border:1px solid #CCC;overflow-y: auto;'>";
						while($rowResp=mysql_fetch_array($resResp)){
							$this->dameNombreEmpleado($rowResp["id_empleado"],'actividad',$id_proceso,$row["id_actividad"],$idUsuario);						       
						}
						echo "</div>";
					}
?>  
								</td>
							</tr><?}else{}?>
						</table>
					</div>			   
<?php	
					($color=="#EEEEEE") ? $color="#FFFFFF" : $color="#EEEEEE";
				}
			}
		}
		
		public function listarProcesos($id_proyecto,$idUsuario,$optPc){
			$status="Activo";
			$sqlP="SELECT * FROM SAT_PROYECTO WHERE id_proyecto='".$id_proyecto."'";//se extrae el nombre del proyecto
			$resP=mysql_query($sqlP,$this->conectarBd());
			$rowP=mysql_fetch_array($resP);
			if($optPc=="consulta"){
				$sqlConsult="SELECT * FROM SAT_PROCESO where id_proyecto='".$id_proyecto."' AND status='".$status."'";
			}else{
				$sqlConsult="SELECT * FROM SAT_PROCESO where id_proyecto='".$id_proyecto."'";
			}
			$resulta=@mysql_query($sqlConsult,$this->conectarBd()) or die(mysql_error());
			if($idUsuario==1 && $optPc=="consulta" ||$idUsuario==0 && $optPc=="consulta" ){
?>
			<div id="barraAPc" style="height: 36px;background: #666;padding: 3px;display:block">
				<div class="opcionesEnsamble" onclick="limpiaconDiv('contenido13');nuevoProceso('<?=$id_proyecto;?>','<?=$idUsuario?>')" title="Nuevo">Nuevo Proceso</div>				
				<div class="opcionesEnsamble" onclick="limpiaconDiv('contenido13');listarProcesos('<?=$id_proyecto;?>','<?=$idUsuario?>','modifica')" title="Modificar">Modificar Proceso</div>	
			</div>
			<?}?>
			<div style="clear: both;"></div>
			<div style="height: 15px;padding: 5px;font-size: 12px;text-align: left;margin-bottom: 5px;">Procesos del proyecto: <strong><?=$rowP["nom_proyecto"];?></strong></div>
			<input type="hidden" name="hdntxtAccion" id="hdntxtAccion" value="procesos">
			<input type="hidden" name="hdntxtValor" id="hdntxtValor" value="<?=$id_proyecto;?>">
			<div style="clear: both;"></div>	
			<div id="tituloHacer" style="height:15px; width:98%;font-size:12px;text-align:center;margin-button:5px;clear:both;font-weight: bold;"><?=strtoupper($optPc);?> PROCESOS</div>			
			<?
			if($optPc=="modifica"){
				?><div id="barraTer" style="height: 36px;background: #fff;padding: 3px;clear:both;text-align:center;">
					<div class="opcionesEnsambleter" onclick="limpiaconDiv('contenido13');listarProcesos('<?=$id_proyecto;?>','<?=$idUsuario?>','consulta')" title="Consultar">Terminar Edición</div>	
			</div>
			<?}?>
			<div id="nuevoProceso" style="border: 1px solid #CCC;margin: 3px;background: #f0f0f0;margin-bottom: 10px;"></div>	
<?
			if(mysql_num_rows($resulta)==0){
				echo "<br>( 0 ) Registros encontrados.<br>";
			}else{
				$color="#FFF";
				while($row = mysql_fetch_array($resulta)){
					$sqlResp="SELECT * FROM ASIG_PROC WHERE id_proceso='".$row["id_proceso"]."' AND status='Activo'";
					$resResp=mysql_query($sqlResp,$this->conectarBd());
					if($optPc=="consulta"){
						$title="Ver Actividades del Proceso";
						$onclick="listarActividades('".$row['id_proceso']."','consulta','".$idUsuario."');";
					}else{
						$title="Modificar Datos del Proceso";
						$onclick="FormModificaProceso('".$id_proyecto."','".$row['id_proceso']."','".$idUsuario."');";
					}
?>
					<div class="resultadosAvisos" style="height: auto;margin: 3px; background: <?=$color?>;" title="<?=$title?>" onclick="<?=$onclick?>">
						<table border="0" cellpadding="1" cellspacing="1" width="98%" style="font-size: 10px;">
							<tr>
								<td width="10%">Proceso:</td>
								<td width="88%"><?=substr($row["nom_proceso"],0,30)."..."; ?></td>
							</tr>
							<tr>
								<td>Descripcion:</td>
								<td><?=substr($row["descripcion"],0,30)."..."; ?></td>
							</tr>
							<?if($optPc=="consulta"){?>
							<tr>
								<td colspan="2">Responsable(s):&nbsp;[ <a href="#" onclick="nuevaAsignacion('SAT_PROCESO','proceso','<?=$row["id_proceso"]?>','<?=$idUsuario?>','<?=$id_proyecto;?>')" style="color:blue;text-decoration: none;" title="Responsable">Agregar Suprevisor</a> ]</td>
							</tr>
							<tr>
								<td colspan="2">
<?
							  if(mysql_num_rows($resResp)==0){
							      echo "<span style='color:red;'>Responsable no Asignado</span>";
							  }else{
								  while($rowResp=mysql_fetch_array($resResp)){
									 $this->dameNombreEmpleado($rowResp["id_empleado"],'proceso',$id_proyecto,$row["id_proceso"],$idUsuario);						       
								  }
							  }
?>  
								</td>
							</tr><?}?>
						</table>
					</div>			   
<?php	
					($color=="#FFF") ? $color="#EEEEEE" : $color="#FFF";
				}	      
			}			
		}
		
		public function listarProyectos($idUsuario,$opProy){
			if($opProy=='consulta'){
				$sqlConsult="SELECT * FROM SAT_PROYECTO INNER JOIN SAT_PAIS ON SAT_PROYECTO.id_pais = SAT_PAIS.id_pais WHERE SAT_PROYECTO.status = 'Activo' ORDER BY id_proyecto DESC";	
			}else{
				$sqlConsult="SELECT * FROM SAT_PROYECTO INNER JOIN SAT_PAIS ON SAT_PROYECTO.id_pais = SAT_PAIS.id_pais ORDER BY id_proyecto DESC";
			}
			
			$resulta=@mysql_query($sqlConsult,$this->conectarBd()) or die(mysql_error());
			if($idUsuario==1 && $opProy=="consulta"||$idUsuario==0 && $opProy=="consulta"){
?>
			<div id="barraAPry" style="height: 36px;background: #666;padding: 3px;display:block;">
				<div class="opcionesEnsamble" onclick="limpiaDivs('contenido12','contenido13');nuevoProyecto('<?=$idUsuario?>')" title="Nuevo">Nuevo Proyecto</div>				
				<div class="opcionesEnsamble" onclick="limpiaDivs('contenido12','contenido13');listarProyectos('<?=$idUsuario?>','modifica');" title="Modificar">Modificar Proyecto</div>	
			</div><?}?>
			<div id="tituloHacer" style="height:15px; width:98%;font-size:12px;text-align:center;margin-button:5px;clear:both;font-weight: bold;"><?=strtoupper($opProy);?> PROYECTOS</div>			
			<div id="nuevoProyecto" style="border: 1px solid #CCC;margin: 3px;background: #f0f0f0;margin-bottom: 10px;"></div>	
			<?
			if($opProy=="modifica"){
				?><div id="barraTer" style="height: 36px;background: #fff;padding: 3px;clear:both;">
					<div class="opcionesEnsambleter" onclick="limpiaDivs('contenido12','contenido13');listarProyectos('<?=$idUsuario?>','consulta')" title="Consultar">Terminar Edición</div>	
			</div>
				<?
			}
			if(mysql_num_rows($resulta)==0){
				   echo "<br>( 0 ) Registros encontrados.<br>";
			}else{
				  $color="#FFF";
			
				while($row = mysql_fetch_array($resulta)){
					$sqlResp="SELECT * FROM ASIG_PRO WHERE id_proyecto='".$row["id_proyecto"]."' AND status='Activo'";
					$resResp=mysql_query($sqlResp,$this->conectarBd());		
					if($opProy=="consulta"){
						$titleP="Ver procesos del proyecto";
						$onclickP="listarProcesos('".$row["id_proyecto"]."','".$idUsuario."','consulta');";
					}else{
						$titleP="Modifica detalles del proceso";
						$onclickP="formActPry('".$row["id_proyecto"]."','".$idUsuario."')";
					}
?>

			      <div class="resultadosAvisos" style="height: auto;width: 98.5%;margin: 2px; background: <?=$color?>;" title="<?=$titleP?>" onclick="<?=$onclickP?>">
				 <table border="0" cellpadding="1" cellspacing="1" width="98%" style="font-size: 10px;">
					    <tr>
						       <td width="10%" style="font-size: 12px;font-weight: bold;">Proyecto:</td>
						       <td width="88%" style="font-size: 12px;font-weight: bold;"><?=$row["nom_proyecto"];?></td>
					    </tr>
					    <tr>
						       <td>Descripci&oacute;n:</td>
						       <td><?=substr($row["descripcion"],0,40)."..."; ?></td>
					    </tr>
					    <tr>
						       <td>Fecha:</td>
						       <td><?=$row["fecha_inicio"];?></td>
					    </tr>
					    <tr>
						       <td>Pa&iacute;s:</td>
						       <td><?=$row["nom_pais"];?></td>
					    </tr>
					    <?if($opProy=='consulta'){if($idUsuario==1||$idUsuario==0){?>
						    <tr>
							       <td colspan="2">Responsable(s):&nbsp;[ <a href="#" onclick="nuevaAsignacion('SAT_PROYECTO','proyecto','<?=$row["id_proyecto"]?>','<?=$idUsuario;?>')" style="color:blue;text-decoration: none;" title="Responsable">Agregar Lider de Proyecto</a> ]</td>
						    </tr>
					    <?}else{?>
							<tr>
							       <td colspan="2">Responsable(s):&nbsp;</td>
						    </tr>
					    <?}?>
					    <tr>
						       <td colspan="2">
<?
				if(mysql_num_rows($resResp)==0){
					echo "<span style='color:red;'>Responsable no Asignado</span>";
				}else{
					while($rowResp=mysql_fetch_array($resResp)){
						$this->dameNombreEmpleado($rowResp["id_empleado"],'proyecto',$row["id_proyecto"],'N/A',$idUsuario);						       
					}
				}
?>  
						       </td>
					    </tr><?}?>
				</table>
		      </div>			   
<?php	
			($color=="#FFF") ? $color="#EEEEEE" : $color="#FFF";}
		      
			} 
		}

	public function formActuaAct($idAct,$idProceso,$idUsuario){
				$sqlResp1="SELECT * FROM ACTIVIDAD_STATUS INNER JOIN SAT_STATUS ON ACTIVIDAD_STATUS.id_status = SAT_STATUS.id_status WHERE id_actividad='".$idAct."'";
				$resResp1E=mysql_query($sqlResp1,$this->conectarBd());
				$sqlSat="SELECT * FROM SAT_ACTIVIDAD WHERE id_actividad='".$idAct."'";
				$exeSat=mysql_query($sqlSat,$this->conectarBd());
				$row=mysql_fetch_array($exeSat);
				$idP=$row["id_producto"];
				$sqlProducto="SELECT * FROM SAT_PRODUCTO WHERE id_producto != $idP ";
				$resProducto=mysql_query($sqlProducto,$this->conectarBd());
				$sqlP="SELECT * FROM SAT_PRODUCTO WHERE id_producto = $idP ";
				$resP=mysql_query($sqlP,$this->conectarBd());
				$ROWp=mysql_fetch_array($resP);
				$sqlStatus="SELECT * FROM SAT_STATUS";
				$resStatus=mysql_query($sqlStatus,$this->conectarBd());
				$conStatusAc="SELECT * FROM ACTIVIDAD_STATUS WHERE id_actividad='".$idAct."'";
				?><form name="modActF" id="modActF"><table border="0" cellpadding="1" cellspacing="1" width="98%" style="font-size: 10px;">
					<input type="hidden" name="idA" id="idA" value="<?=$idAct?>"/><input type="hidden" name="idP" id="idP" value="<?=$idProceso?>"/>
					<input type="hidden" name="idUser" id="idUser" value="<?=$idUsuario?>" />
							<tr>
								<td width="10%">Actividad:</td>
								<td width="88%"><input type="text" name="actName" id="actName" value="<?=$row["nom_actividad"]?>" onchange="confGuarda('el nombre');guardaE('actName','nom_actividad');"/></td>
							</tr>
							<tr>
								<td>Descripcion:</td>
								<td><input type="text" name="desAct" id="desAct" value="<?=$row["descripcion"];?>" onchange="confGuarda('la descripción');guardaE('desAct','descripcion');"/></td>
							</tr>
							<tr>
								<td>Producto</td>
								<td><div id="divProductoS" style="float: left;">
<?
							 			if(mysql_num_rows($resProducto)==0){
								    		echo "No hay productos Capturados";
										 }else{
			?>
									    <select name="cboProductoActividad" id="cboProductoActividad" style="width: 233px;" onchange="confGuarda('el producto');guardaE('cboProductoActividad','id_producto');">						       
										     <option value="<?=$idP;?>"><?=$ROWp["nom_producto"]?></option>
				<?
									    while($rowProducto=mysql_fetch_array($resProducto)){
				?>
										     <option value="<?=$rowProducto["id_producto"];?>"><?=$rowProducto["nom_producto"]." ".$rowProducto["modelo"];?></option>  
				<?
									    }
				?>
									    </select>
				<?
								 }
?>							
							</div>&nbsp;<div style="float: left;margin-top: 3px;margin-left: 5px;">[ <a href="#" onclick="agregaProducto()" title="Agregra Producto" style="color: blue;">Nuevo Producto</a> ]</div>
							</td>		
						</tr>
							<tr>
						<td colspan="2">Seleccione, Modifique o deseleccione los status relacionados a la actividad&nbsp;<a href="#" onclick="agregarMS('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario;?>')" title="Agregar Status" style="color: blue;"><img src="../../img/add.png" border="0" /></a></td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="statusExistentesM" style="border: 1px solid #CCC;height: 230px;overflow: auto;background: #FFF;font-size: 10px;">
<?
					if(mysql_num_rows($resStatus)==0){
						echo "No hay status Capturados";
 					}else{
						$color="#EEE";
						while($rowStaSe=mysql_fetch_array($resResp1E)){
							if($color=="#FFF"){
								$color="#EEE";
							}else{
								$color="#FFF";
							}
							$cNom="CnomS".$rowStaSe["id_act_status"];
							$cTim="CtimS".$rowStaSe["id_act_status"];
							$bOpe="BopSta".$rowStaSe["id_act_status"];
							$bSaCa="opciones".$rowStaSe["id_act_status"];
							$divEdita="edita".$rowStaSe["id_act_status"];
							?>
							<div id="contenido" style="heigth:25px; width:90%;overflow:auto;clear:both;">
								<?if($rowStaSe["id_status"]==1){?>
									<?if($rowStaSe["tiempo"]!=0||$rowStaSe["tiempo"]!=0.000){?>
										<div id="des" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;"><input type="checkbox" name="ASt" id="ASt" onclick="cACS('Desactivar','<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');checkActivar('ASt','<?=$rowStaSe["id_act_status"]?>','<?=$rowStaSe["tiempo"]?>')" checked="checked"/></div>
										<div id="<?=$divEdita?>" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px; display:block"><a href="#" onclick="editaStatus('<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>')" title="Edita Status"><img src="../../img/icon_edit.png" border="0" /></a></div>								
									<?}else{
										?><div id="des" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;"><input type="checkbox" name="ASt" id="ASt" onclick="cACS('Activar','<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');checkActivar('ASt','<?=$rowStaSe["id_act_status"]?>','<?=$rowStaSe["tiempo"]?>')"/></div>								
										<div id="divVacio" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px; display:block">&nbsp;&nbsp;&nbsp;&nbsp;</div>	
										<div id="<?=$divEdita?>" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px; display:none"><a href="#" onclick="editaStatus('<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>')" title="Edita Status"><img src="../../img/icon_edit.png" border="0" /></a></div>								
									<?}
								}else{?>
									<div id="quita" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;"><a href="#" onclick="confDelSta('<?=$rowStaSe["nom_status"]?>');quitarStatus('<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>')" title="Eliminar Status"><img src="../../img/icon_delete.gif" border="0" /></a></div>		
									<div id="<?=$divEdita?>" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px; display:block"><a href="#" onclick="editaStatus('<?=$rowStaSe["id_act_status"]?>','<?=$idAct?>','<?=$idProceso?>')" title="Edita Status"><img src="../../img/icon_edit.png" border="0" /></a></div>													
								<?}?>
								
								<div id="nomS" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;"><input type="text" style="font-size: 10px;background-color:transparent;border:none;width:60px;" name="<?=$cNom;?>" id="<?=$cNom;?>" value="<?=$rowStaSe["nom_status"]?>" readonly/></div>
								<div id="timS" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;font-size: 10px;"><input type="text" style="font-size: 10px;background-color:transparent;border:none;width:80px;" name="<?=$cTim?>" id="<?=$cTim?>" value="<?=$rowStaSe["tiempo"]?>" readonly/>Min</div>
								<div id="opSta" style="heigth:20px; width:auto; background:<?=$color;?>;float:left;padding:5px;"><input type="button" style="background-color:transparent;border:none;width:30px; heigth:30px;" name="<?=$bOpe?>" id="<?=$bOpe?>" value="<?=$rowStaSe["operador"]?>" onclick="cambiaOpe('<?=$bOpe?>');" disabled="disabled"/></div>
								<?if($rowStaSe["id_status"]==1){
									if($rowStaSe["tiempo"]!=0 || $rowStaSe["tiempo"]!=0.000){
										?><div id="<?=$bSaCa;?>" style="heigth:auto; width:auto; background:<?=$color;?>;float:left;padding:5px;display:none;"><input type="button" name="acept" id="acept" value="Modificar" onclick="guardarMod('<?=$rowStaSe["nom_status"]?>','<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>')"/><input type="button" name="cancl" id="cancl" value="Cancelar" onclick="canclMo('<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>')"/></div><?
									}else{
										?><div id="<?=$bSaCa;?>" style="heigth:auto; width:auto; background:<?=$color;?>;float:left;padding:5px;display:none;"><input type="button" name="acept" id="acept" value="Modificar" onclick="guardarMod('<?=$rowStaSe["nom_status"]?>','<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>')"/><input type="button" name="cancl" id="cancl" value="Cancelar" onclick="canclMo('<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>');modAct('<?=$idAct?>','<?=$idProceso?>')"/></div><?
									}
								}else{
									?><div id="<?=$bSaCa;?>" style="heigth:auto; width:auto; background:<?=$color;?>;float:left;padding:5px;display:none;"><input type="button" name="acept" id="acept" value="Modificar" onclick="guardarMod('<?=$rowStaSe["nom_status"]?>','<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>')"/><input type="button" name="cancl" id="cancl" value="Cancelar" onclick="canclMo('<?=$rowStaSe['id_act_status']?>','<?=$idAct?>','<?=$idProceso?>')"/></div><?
								}?>
								

							</div><?
						}
					}
?>
							</div>
						</td>
					</tr>
						</table></form><?
		
		}
		public function guardaE($idAct,$campo,$valor,$idProceso,$idUsuario){
				$modA="UPDATE SAT_ACTIVIDAD SET $campo='".$valor."' where id_actividad='".$idAct."'";
				$exeModA=mysql_query($modA,$this->conectarBd());
				if(!$exeModA){
					?><script type="text/javascript">alert("No se puedo modificar");</script><?
				}else{
					?><script type="text/javascript">listarActividades('<?=$idProceso?>','modifica','<?=$idUsuario?>');modAct('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');</script><?
				}
		}
		public function FormStat($idAct,$idProceso,$idUsuario){
			$statusUsado= array();
			$acS="SELECT * FROM ACTIVIDAD_STATUS WHERE id_actividad='".$idAct."'";
			$exeAC=mysql_query($acS,$this->conectarBd());
			while($row=mysql_fetch_array($exeAC)){
				array_push($statusUsado,$row["id_status"]);
			}
			$statUsados=implode("','",$statusUsado);
			$bSta="SELECT * FROM SAT_STATUS WHERE status='Activo' AND id_status NOT IN ('".$statUsados."')";
			$exeB=mysql_query($bSta,$this->conectarBd());
?>
					<form id="mS" name="mS"><table><tr>
						<td colspan="2" style="font-size:10px;">Seleccione los status relacionados a la actividad&nbsp;[ <a href="#" onclick="agregaSBA('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>')" title="Agregar Status" style="color: blue;">Nuevo Status</a>]</td>
					</tr>
					<tr>
						<td colspan="2">
							<div id="statusExistentesA" style="border: 1px solid #CCC;height: 100px;overflow: auto;background: #FFF;font-size: 10px;">
<?
					if(mysql_num_rows($exeB)==0){
						echo "Actualmente tiene todos los Status en uso";
					}else{
						$i=0;
						while($rowStatus=mysql_fetch_array($exeB)){
							$id="cboStatus".$i;
							if($rowACS["id_status"]==$rowStatus["id_status"]){}else{
	?>								<input type="checkbox" name="cboStatus" id="<?=$id;?>" value="<?=$rowStatus["id_status"];?>"><label for="<?=$id;?>"><?=$rowStatus["nom_status"];?></label><br>
	<?
							$i+=1;
							}
						}
					}
?>
							</div>
						</td>
					</tr>
					<tr>
							<td colspan="2"><hr style="background: #666;"</td>
					</tr>
					<tr>
							<td colspan="2" style="text-align: right">
									<input type="button" onclick="cerrarVentana('transparenciaGeneralSt');" value="Cancelar">
									<input type="button" onclick="guardarNuevoStA('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>')" value="Siguiente">
							</td>
					</tr>
					<tr>
							<td>&nbsp;</td>
					</tr>
				</table></form><?
		
		}
		public function guardarNSA($idAct,$status,$idProceso,$idUsuario){
			$status=explode(",",$status);						
				//se recupera el ultimo id insertado en la actividad				
				for($i=0;$i<count($status);$i++){
					$sqlActStatus="INSERT INTO ACTIVIDAD_STATUS (id_actividad,id_status) VALUES ('".$idAct."','".$status[$i]."')";//se ejecuta la consulta sql
					$resActStatus=mysql_query($sqlActStatus,$this->conectarBd());
					if($resActStatus==true){
					echo "<script type='text/javascript'> mostrarFormMetrica('".$idAct."','".$idProceso."','".$idUsuario."'); </script>";//se manda llamar al siguiente formulario
					}
					if($resActStatus==false){
						echo "<script type='text/javascript'> alert('Ocurrio un error al guardar el status con la Actividad');</script>";	
					}
				}
				echo "<script type='text/javascript'> alert('status Agregado'); cerrarVentana('transparenciaGeneralSt');modAct('".$idAct."','".$idProceso."','".$idUsuario."');</script>";	
			
		}
		public function quitarStatus($idActSta,$idAct,$idProceso,$idUsuario){
			$eliAcSt="DELETE FROM ACTIVIDAD_STATUS WHERE id_act_status=$idActSta";
			$exeDel=mysql_query($eliAcSt,$this->conectarBd());
			if($exeDel==false){
				?><script type='text/javascript'> alert('No se pudo eliminar el Status');</script><?
			}else{
				?><script type='text/javascript'> alert('Status Eliminado');listarActividades('<?=$idProceso?>','modifica','<?=$idUsuario?>'); modAct('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');</script><?	
			}
		}
		public function actualizaDE($idActSta,$tiempo,$operador,$idAct,$idProceso,$idUsuario){
			if($operador=="mas"){
				$operador="+";
			}else{
				$operador="-";
			}
				$sqlActDE="UPDATE ACTIVIDAD_STATUS SET tiempo='".$tiempo."', operador='".$operador."' WHERE id_act_status='".$idActSta."'";
				$resActActDE=mysql_query($sqlActDE,$this->conectarBd());
				if($resActActDE){
					?><script type="text/javascript">alert('Actualizacion Realizada');listarActividades('<?=$idProceso?>','modifica','<?=$idUsuario?>');modAct('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');</script><?
				}else{
						?><script type="text/javascript">alert('Error al Actualizar el Registro');listarActividades('<?=$idProceso?>','modifica','<?=$idUsuario?>');modAct('<?=$idAct?>','<?=$idProceso?>','<?=$idUsuario?>');</script><?
				}
		}
		public function agregaSBA($status,$idAct,$idProceso,$idUsuario){
			$sqlStatus="INSERT INTO SAT_STATUS (nom_status,status) VALUES ('".strtoupper($status)."','Activo')";
			$resStatus=mysql_query($sqlStatus,$this->conectarBd());
			if($resStatus){
				echo "<script type='text/javascript'> alert('Status Guardado'); agregarMS('".$idAct."','".$idProceso."','".$idUsuario."')</script>";
			}else{
				echo "<script type='text/javascript'> alert('Error al Guardar el Status'); </script>";
			}
		}
		public function FormActualizaProceso($idProyecto,$idProceso,$idUsuario){
			$status="Activo";
			$querProc="SELECT * FROM SAT_PROCESO WHERE id_proceso=$idProceso AND status='".$status."'";
			$exeQueProc=mysql_query($querProc,$this->conectarBd());
			$arraQuer=mysql_fetch_array($exeQueProc);
			?>
			<br><br><input type="hidden" name="hdnProcesoProyectoA" id="hdnProcesoProyectoA" value="<?=$idProyecto?>"/>
			<input type="hidden" name="hdnProcesoAc" id="hdnProcesoAc" value="<?=$idProceso?>"/>
			<input type="hidden" name="hdnUsuarioAc" id="hdnUsuarioAc" value="<?=$idUsuario?>"/>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="450" style="font-size: 12px;border: 1px solid #666;">
				<tr>
					<td style="height: 15px;padding: 5px;background: #666;color: #FFF;">Modifica Proceso (<?=strtoupper($arraQuer["nom_proceso"])?>) </td>
				</tr>
				<tr>
					<td>Nombre</td>
				</tr>
				<tr>
					<td><input type="text" name="txtNombreProcAc" id="txtNombreProcAc" value="<?=$arraQuer["nom_proceso"]?>"></td>
				</tr>
				<tr>
					<td>Descripci&oacute;n</td>
				</tr>
				<tr>
					<td><textarea rows="3" cols="30" name="txtDescProcAc" id="txtDescProcAc"><?=$arraQuer["descripcion"]?></textarea></td>
				</tr>
				<tr>
					<td><hr style="background: #666;"</td>
				</tr>
				<tr>
					<td style="text-align: right">
						<input type="button" onclick="cancelarCapturaProceso()" value="Cancelar">
						<input type="button" onclick="actualizaProceso()" value="Modificar Proceso">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
<?
		}
		public function actualizaProceso($id_proyecto,$nombre,$descripcion,$id_proceso,$idUsuario){
			$acProc="UPDATE SAT_PROCESO SET nom_proceso='".$nombre."', descripcion='".$descripcion."' WHERE id_proceso='".$id_proceso."'";
			$exeAcPrc=mysql_query($acProc,$this->conectarBd());
			if($exeAcPrc==false){
				?><script type="text/javascript">alert("No se pudo actualizar el proceso intente mas tarde");cancelarCapturaProceso();listarProcesos('<?=$id_proyecto?>','<?=$idUsuario?>','modifica');</script><?
			}else{
				?><script type="text/javascript">alert("El proceso se ha actualizado con exito");cancelarCapturaProceso();listarProcesos('<?=$id_proyecto?>','<?=$idUsuario?>','modifica');</script><?
			}
		}
		public function nuevoProyecto($idUsuario){
			?>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="450" style="font-size: 12px;border: 1px solid #666;">
				<tr>
					<td style="height: 15px;padding: 5px;background: #666;color: #FFF;" colspan=2>Nuevo Proyecto</td>
				</tr>
				<tr>
					<td>Nombre</td>
					<td><input type="text" name="nomPry" id="nomPry" style="width:200px;"/></td>
				</tr>
				<tr>
					<td>Fecha Inicio</td>
					<td><input type="text" name="fechaInicio" id="fechaInicio" style="width:165px;" readonly/><input type="button" id="lanzador1" value="..." />
									    <!-- script que define y configura el calendario-->
									    <script type="text/javascript">
										    Calendar.setup({
											    inputField     :    "fechaInicio",      // id del campo de texto
											    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
											    button         :    "lanzador1"   // el id del botón que lanzará el calendario
										    });
									    </script>
						</td>
				</tr>
								<tr>
					<td>Fecha Fin</td>
					<td><input type="text" name="fechaFin" id="fechaFin" style="width:165px;" readonly/><input type="button" id="lanzador2" value="..." />
									    <!-- script que define y configura el calendario-->
									    <script type="text/javascript">
										    Calendar.setup({
											    inputField     :    "fechaFin",      // id del campo de texto
											    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
											    button         :    "lanzador2"   // el id del botón que lanzará el calendario
										    });
									    </script>
						</td>
				</tr>
				<tr>
					<td>Status:</td>
					<td><select name="stat" id="stat" style="width:200px; font-size:15px;">
						<option value="">Selecciona Status:</option>
						<option value="Activo">Activo</option>
						<option value="Inactivo">Inactivo</option>
					</select></td>
				</tr>
				<tr>
					<td>Pais</td>
					<?
					$buPai="SELECT * FROM SAT_PAIS WHERE status='Activo'";
					$exePais=mysql_query($buPai,$this->conectarBd());
					?>
					<td><select name="pais" id="pais" style="width:200px; font-size:15px;">
						<option value="">Selecciona Pais:</option>
						<?while($row=mysql_fetch_array($exePais)){?>
						<option value="<?=$row["id_pais"]?>"><?=$row["nom_pais"]?></option>
						<?}?>

					</select></td>
				</tr>
				<tr>
					<td>Descripci&oacute;n</td>
					<td><textarea rows="2" cols="26" name="descPry" id="descPry"></textarea></td>
				</tr>
				<tr>
					<td>Observaciones</td>
					<td><textarea rows="2" cols="26" name="obsPry" id="obsPry"></textarea></td>
				</tr>
				<tr>
					<td colspan=2><hr style="background: #666;"></td>
				</tr>
				<tr>
					<td style="text-align: right" colspan=2>
						<input type="button" onclick="cancelarCapturaProceso()" value="Cancelar"><input type="button" onclick="guardarProyecto('<?=$idUsuario;?>')" value="Guardar Proyecto">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
<?
		}

		public function guardarProyecto($nomPry,$descPry,$fechaIni,$fechaFin,$pais,$stat,$obsPry,$idUsuario){
			$newPry="INSERT INTO SAT_PROYECTO (nom_proyecto, descripcion, fecha_inicio, fecha_fin, status,id_pais,Observaciones) VALUES ('".$nomPry."','".$descPry."','".$fechaIni."','".$fechaFin."','".$stat."','".$pais."','".$obsPry."')";
			$exeNewP=mysql_query($newPry,$this->conectarBd());
			if($exeNewP==false){
				?><script type="text/javascript">alert("No se pudo agregar Nuevo Proyecto intente mas tarde");cancelarCapturaProceso();listarProyectos('<?=$idUsuario?>','consulta');</script><?
			}else{
				?><script type="text/javascript">alert("Proyecto Agregado con exito");cancelarCapturaProceso();listarProyectos('<?=$idUsuario?>','consulta');</script><?				
			}
		}
		public function formActPry($idProyecto,$idUsuario){
			$modPry="SELECT * FROM SAT_PROYECTO WHERE id_proyecto='".$idProyecto."'";
			$exePry=mysql_query($modPry,$this->conectarBd());
			$fetPry=mysql_fetch_array($exePry);
						?>
			<table border="0" align="center" cellpadding="1" cellspacing="1" width="450" style="font-size: 12px;border: 1px solid #666;">
				<tr>
					<td style="height: 15px;padding: 5px;background: #666;color: #FFF;" colspan=2>Actualiza Proyecto</td>
				</tr>
				<tr>
					<td>Nombre</td>
					<td><input type="text" name="nomPryA" id="nomPryA" style="width:200px;" value="<?=$fetPry["nom_proyecto"]?>"/></td>
				</tr>
				<tr>
					<td>Fecha Inicio</td>
					<td><input type="text" name="fechaInicioA" id="fechaInicioA" style="width:165px;" value="<?=$fetPry["fecha_inicio"]?>"readonly/><input type="button" id="lanzador1" value="..." />
									    <!-- script que define y configura el calendario-->
									    <script type="text/javascript">
										    Calendar.setup({
											    inputField     :    "fechaInicio",      // id del campo de texto
											    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
											    button         :    "lanzador1"   // el id del botón que lanzará el calendario
										    });
									    </script>
						</td>
				</tr>
								<tr>
					<td>Fecha Fin</td>
					<td><input type="text" name="fechaFinA" id="fechaFinA" style="width:165px;" value="<?=$fetPry["fecha_fin"]?>"readonly/><input type="button" id="lanzador2" value="..." />
									    <!-- script que define y configura el calendario-->
									    <script type="text/javascript">
										    Calendar.setup({
											    inputField     :    "fechaFin",      // id del campo de texto
											    ifFormat       :    "%Y-%m-%d",       // formato de la fecha, cuando se escriba en el campo de texto
											    button         :    "lanzador2"   // el id del botón que lanzará el calendario
										    });
									    </script>
						</td>
				</tr>
				<tr>
					<td>Status:</td>
					<td><select name="statA" id="statA" style="width:200px; font-size:15px;">
						<option value="<?=$fetPry["status"]?>"><?=$fetPry["status"]?></option>
						<option value="Activo">Activo</option>
						<option value="Inactivo">Inactivo</option>
					</select></td>
				</tr>
				<tr>
					<td>Pais</td>
					<?
					$paisAc=$fetPry["id_pais"];
					$nomP="SELECT * FROM SAT_PAIS WHERE id_pais='".$paisAc."'";
					$exeNomp=mysql_query($nomP,$this->conectarBd());
					$fetNom=mysql_fetch_array($exeNomp);
					$buPai="SELECT * FROM SAT_PAIS WHERE status='Activo' AND id_pais!='".$paisAc."'";
					$exePais=mysql_query($buPai,$this->conectarBd());
					?>
					<td><select name="paisAct" id="paisAct" style="width:200px; font-size:15px;">
						<option value="<?=$fetNom["id_pais"]?>"><?=$fetNom["nom_pais"]?></option>
						<?while($row=mysql_fetch_array($exePais)){?>
						<option value="<?=$row["id_pais"]?>"><?=$row["nom_pais"]?></option>
						<?}?>

					</select></td>
				</tr>
				<tr>
					<td>Descripci&oacute;n</td>
					<td><textarea rows="2" cols="26" name="descPryA" id="descPryA"><?=$fetPry["descripcion"]?></textarea></td>
				</tr>
				<tr>
					<td>Observaciones</td>
					<td><textarea rows="2" cols="26" name="obsPryA" id="obsPryA"><?=$fetPry["observaciones"]?></textarea></td>
				</tr>
				<tr>
					<td colspan=2><hr style="background: #666;"></td>
				</tr>
				<tr>
					<td style="text-align: right" colspan=2>
						<input type="button" onclick="cancelarCapturaProceso()" value="Cancelar"><input type="button" onclick="ActualizarProyecto('<?=$idProyecto?>','<?=$idUsuario;?>','<?=$fetPry['status']?>')" value="Actualizar Proyecto">
					</td>
				</tr>
				<tr>
					<td>&nbsp;</td>
				</tr>
			</table>
<?
		}

		public function ActualizarProyecto($nomPry,$descPry,$fechaIni,$fechaFin,$pais,$stat,$obsPry,$idUsuario,$idProyecto,$statActual){
			$actPry="UPDATE SAT_PROYECTO SET nom_proyecto='".$nomPry."', descripcion='".$descPry."',fecha_inicio='".$fechaIni."',fecha_fin='".$fechaFin."',status='".$stat."',id_pais='".$pais."',observaciones='".$obsPry."' WHERE id_proyecto='".$idProyecto."'";
			$exeActPry=mysql_query($actPry,$this->conectarBd());
			if($exeActPry==false){
				?><script type="text/javascript">alert("El proyecto no pudo ser actualizado");cancelarCapturaProceso();listarProyectos('<?=$idUsuario?>','consulta');</script><?
			}else{
				if($statActual!=$stat){
					if($stat=="Activo"){
						$msj="Activado";
					}else{$msj="Desactivado";}
						$consPrc="SELECT * FROM SAT_PROCESO WHERE id_proyecto='".$idProyecto."'";
						$exeConPrc=mysql_query($consPrc,$this->conectarBd());
						$cuentaProcesos=mysql_num_rows($exeConPrc);
						if($cuentaProcesos>0){
							$actuaStatProc="UPDATE SAT_PROCESO SET status='".$stat."' WHERE id_proyecto='".$idProyecto."'";
							print($actuaStatProc);
							$exeAcSPc=mysql_query($actuaStatProc,$this->conectarBd());
							if($exeAcSPc==true){
								$aPc=array();
								while($rowPc=mysql_fetch_array($exeConPrc)){
									array_push($aPc, $rowPc["id_proceso"]);
								}
								$idProcesos=implode(",", $aPc);
								$actStatAct="UPDATE SAT_ACTIVIDAD SET status='".$stat."' where id_proceso in ($idProcesos)";
								$exeASa=mysql_query($actStatAct,$this->conectarBd());
								if($exeASa==true){
									?><script type="text/javascript">alert("Se han <?=$msj?> los Procesos y Actividades derivados del Proyecto <?=$nomPry?>");</script><?
								}

							}
						}
					
				}
				?><script type="text/javascript">alert("Proyecto modificado con exito");cancelarCapturaProceso();listarProyectos('<?=$idUsuario?>','consulta');</script><?				
			}
		}
		
	}//fin de la clase
	//$objP=new modeloEnsamble();
	//$objP->prueba();
?>