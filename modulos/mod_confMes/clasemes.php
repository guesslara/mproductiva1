<?php
    
class mes{

   
    public function conectarBd(){
		$link=mysql_connect('localhost','root','xampp');
		if($link==false){
			echo "Error en la conexion a la base de datos";
		}else{
			mysql_select_db('2013_matriz_productiva');
			return $link;
		}
            
	}
	
	   
    public function conectar(){
            $conexion=@mysql_connect('localhost','root','xampp') or die ("no se pudo conectar al servidor<br>".mysql_error());
		if(!$conexion){
                     echo "Error al conectarse al servidor";	
    }
		else{
                     @mysql_select_db('iqe_rrhh_2010') or die ("No se puede conectar a la base de datos<br>".mysql_error());
    }
    			return $conexion;
    }
    
    public function verificaEmpleado($noEmpleado,$mes){
	$sqlBusca="SELECT * FROM CAP_MES WHERE no_empleado='".$noEmpleado."' AND mes='".$mes."'";
	$resBusca=mysql_query($sqlBusca,$this->conectarBd());
	if(mysql_num_rows($resBusca)!=0){
	    echo "<div style='height:15px;padding:5px;color:red;'>Este usuario ya esta registrado en su configuracion Mensual.</div>";
	    echo "<script type='text/javascript'> $('#RegistrarConf').hide(); </script>";
	}else{
	    echo "<script type='text/javascript'> $('#RegistrarConf').show(); </script>";
	}
    }
    
    public function formregis(){
        // $hoy = date('D-M-Y-h-i - g:i:s');
	date_default_timezone_set("Mexico/General");
	$hoy = date('Y-m-d H:i:s ',time());
	$cap_mes="CAP_MES";
	$clase_obligaria="campo_obligatorio";
  
?>
    <form id="frmAsignacionMes" name="frmAsignacionMes" action="" method="post" >
   <!--<div id="delimitador" style="border: 0px solid #ff0000;height: 99%;position: relative;overflow: auto;">	-->
	<table border="0" width="900" cellpadding="1" cellspacing="1" style="margin: 5px;font-size: 12px;">
	    <tr>
		<td><div id="divVerificacion"></div></td>
	    </tr>
	    <tr>
		<td colspan="2" style="border: 1px solid #CCC;background: #f0f0f0;">Informaci&oacute;n:</td>		
	    </tr>
	    <tr>
		<td width="450" style="text-align: left;">
		    <table align="left" border="0" cellspacing="5" style="font-size: 12px;">
			 <tr>
			    <td>No Empleado:</td>
			    <td><input type="text" name="id_empleado" id="no_empleado" class="<?=$clase_obligaria?>" readonly></td>
			 </tr>			 
			 <tr>
			    <td>Nombre:</td>
			    <td><input type="text" name="nombres" id="nombres" value="" class="<?=$clase_obligaria?>" readonly></td>
			    <td> <a href="#" onclick="abrir('buscarEmpleado');"> Buscar</a></td>			 
			 </tr>
			 <tr>
			    <td>Apellido Paterno:</td>
			    <td><input type="text" name="a_paterno" id="a_paterno" value="" class="<?=$clase_obligaria?>" readonly></td>
			 </tr>
			 <tr>
			    <td>Apellido Materno:</td>
			    <td><input type="text" name="a_materno" id="a_materno" value="" class="<?=$clase_obligaria?>" readonly></td>     
			 </tr>
		    </table>
		</td>
		<td width="450" rowspan="3" valign="top" style="text-align: center;"><div style="border: 1px solid #CCC;height: 350px;width: 300px;"></div></td>		
	    </tr>
	    <tr>
		<td style="border: 1px solid #CCC;background: #f0f0f0;">Informaci&oacute;n adicional:</td>		
	    </tr>
	    <tr>
		<td>
		    <table style="font-size: 12px;" border="0" width="450">
                        <tr>
			    <td>Mes:</td>
			    <td><select  name="mes" id="mes" onchange="verificaMes()" class="<?=$clase_obligaria?>" <?=$sol?>>
                    <option value="undefined">Seleccione un Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                    </select>
			    </td>
			</tr>			
			<!--<tr>
			    <td colspan="2">				
				<div id="calendarioDiasSeleccionados" style="height: 250px;width: 430px;border: 1px solid #CCC;overflow: auto;"></div>
				<div title="Agregar Dias Seleccionados" onclick="agregarDiasSeleccionados()" style="float: right;width: 110px;border: 1px solid #CCC;background: #e1e1e1;height: 15px;padding: 5px;text-align: center;margin: 3px;">Agregar D&iacute;as</div>				
				<div style="clear: both;">&nbsp;</div>
			    </td>
			</tr>
			<tr>
			    <td>
				Dias Seleccionados:
			    </td>
			    <td>
				<textarea name="txtDiasSeleccionados" id="txtDiasSeleccionados" rows="3" cols="30"></textarea>								
			    </td>    
			</tr>-->
			<tr>
			    <td>
				Dias laborables:
			    </td>
			    <td>
				<input type="text" name="dias_lab" id="dias_lab" value="0"  onkeyup="calcular();" onblur="campoValor0(this);" class="<?=$clase_obligaria?>" style="width: 50px;">				
			    </td>    
			</tr>
			<tr>
			    <td>
			    Jornada Laboral:</td><td> <input type="text" name="jorna_lab" id="jorna_lab" value="0"  onkeyup="calcular();"  onblur="campoValor0(this);"  class="<?=$clase_obligaria?>" style="width: 50px;">    
			    </td>    
			</tr>
			<tr>
			    <td>
			    Dias Licencia:</td> <td><input type="text" name="dias_li" id="dias_li" value="0" onkeyup="calcular();"  onblur="campoValor0(this);"  class="<?=$clase_obligaria?>" style="width: 50px;"></td>    
			</tr>
			<tr>
			    <td>
			    Tiempo Extra: </td><td><input type="text" name="tiem_ex" id="tiem_ex"  onkeyup="calcular();" onblur="campoValor0(this);" value="0"  class="<?=$clase_obligaria?>" style="width: 50px;">    
			    </td>
			</tr>
			<tr>
			    <td>Horas Laborables:</td><td><input type="text" name="horas_la" id="horas_la" value="0" onkeyup="calcular();" class="<?=$clase_obligaria?>" readonly="readonly" style="width: 50px;"></td>
			</tr>
			<tr>
			    <td>
			    Meta Productiva:</td><td><input type="text" name="meta_pro" id="meta_pro" value="" onkeyup="calcular();" class="<?=$clase_obligaria?>" style="width: 50px;"><label>%</label>
			    </td>    
			</tr>
                    </table>
		</td>			
	    </tr>
	    <tr>
		<td colspan="2"><hr style="background: #999;"></td>
	    </tr>
	    <tr>
		<td colspan="2" style="text-align: right;"><input type="button" name="Registrar" id="RegistrarConf" value="Registrar" onclick="VALIDAR('<?=$cap_mes;?>');" style="height: 35px;padding: 5px;display: block;"/></td>
	    </tr>
	</table>	
        <!--</div>-->
	</form>
<?  
    
                }
		
    public function consultarempleado($emp){
	$eee="SELECT * FROM cat_personal  WHERE nombres LIKE '".mysql_real_escape_string(strip_tags($emp))."%' AND activo=1";
	$estaseje=@mysql_query($eee,$this->conectar()) or die(mysql_error());
?>
	<table align="center" BORDER="0" CELLPADDING="0" width="700" CELLSPACING="0" style="font-size: 12px;">
	    <tr>
		<td colspan="5"><center><strong>Listado de Personal</strong></center></td>
	    </tr>
	    <tr>
		<td colspan="5">Resultados <?=mysql_num_rows($estaseje);?></td>
	    </tr>
	    <tr>
		<td class="cabeceraTitulosTabla">N° Empleado</td>
		<td class="cabeceraTitulosTabla">Nombre</td>
		<td class="cabeceraTitulosTabla">Apellido Paterno</td>
		<td class="cabeceraTitulosTabla">Apellido Materno</td>		
	    </tr>
<?
	while($fi=mysql_fetch_array($estaseje)){
	    $noempleado=$fi["no_empleado"];
	    $nombres=$fi["nombres"];
	    $apaterno=$fi["a_paterno"];
	    $amaterno=$fi["a_materno"];
	    $pais=$fi["pais"];
?>
	    <tr class="resultadosTablaBusqueda">  
		<td class="resultadosTablaBusqueda1"><a href="#" onclick="insertarEmpleado('<?=$noempleado;?>','<?=$nombres;?>','<?=$apaterno;?>','<?=$amaterno?>')" ><?=$noempleado;?></a></td>
		<td class="resultadosTablaBusqueda1"><?=$nombres;?></font></td>
		<td class="resultadosTablaBusqueda1"><?=$apaterno;?></td>
		<td class="resultadosTablaBusqueda1"><?=$amaterno;?></td>		
	    </tr>  
<?
	}
?>
	    <tr>
		<td colspan="5">&nbsp;</td>
	    </tr>
	</table><br><br><br>
<?
    }
     
    public function insertarasignacion($tab,$camvolor){
     	        //print_r($camvolor);
		//exit;
		$sql_campos="";
		$sql_valores="";
		$prefijo2='SAT_';
		
		$separar_campos=explode("@@@",trim($camvolor));
		//print_r($separar_campos);
		//exit;
		
		foreach ($separar_campos as $cam){
			$separar_campos2=explode("|||",trim($cam));
			//print_r($separar_campos);
			//exit;
			$campoX=str_replace("txt_","",trim($separar_campos2[0]));
			//print_r($campoX);
			//exit;
			$valorX=trim($separar_campos2[1]);
			//print_r($valorX);
			($sql_campos=="")? $sql_campos=$campoX : $sql_campos.=",".$campoX;
			($sql_valores=="")? $sql_valores=$valorX : $sql_valores.=",'".$valorX."'";
		}
		//print_r($valorX); 
		//print_r($sql_valores); exit;
		$sql_insertar="INSERT INTO $tab($sql_campos) VALUES ($sql_valores);";
		//print_r($sql_insertar); exit;
		$consulta=mysql_query($sql_insertar,$this->conectarBd());
		if ($consulta){
			echo "<br><b>&nbsp;Registro Insertado Correctamente.</b>";
			//$idL=mysql_insert_id($this->conectarBd());
			//print($idL);
			//exit;	 
		}
		else {
			echo "<br>&nbsp; Error SQL (".mysql_error($link).")<br><br><b>&nbsp;El Registro NO se Inserto.</b>";
		}
		//}//fin try
     
     }
     
    public function consultar_mes(){    
	$seleccion="select * from CAP_MES";
	$ejesele=mysql_query($seleccion,$this->conectarBd()) or die(mysql_error());	
?>	<div id="Buscar" style="border:1px solid #000000; width: 800px; height: 50px;margin: 10px auto 10px auto;"><table><tr>
	<td>Nº Empleado:<input type="text" name="noempleado" id="noempleado" size="5" /></td><td><label>Nombres</label><input type="text" name="nombres" size="15" id="nombres";/></td><td><label>Mes:</label> <select  name="mes" id="mes" >
                    <option value="undefined">Seleccione un Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                    </select></td>
	<td><input type="button" name="buscar" id="buscar"value="buscar" onclick="buscaPorParametro();"></td>
	</tr></table></div>
	
	<table align="center" width="90%" BORDER="1" CELLPADDING="0" CELLSPACING="0" style="font-size: 12px;">
	     <tr>
	     <td colspan="15"><center><strong>CAP_MES</strong></center></td>
	     </tr>
	     <tr>
	     <td class="cabeceraTitulosTabla">No_Captura</td>
	     <td class="cabeceraTitulosTabla">N°_empleado</td>
	     <td class="cabeceraTitulosTabla">Nombre Empleado</td>
	     <td class="cabeceraTitulosTabla">D&iacute;as Laborables</td>
	     <td class="cabeceraTitulosTabla">Jornada Laboral</td>
	     <td class="cabeceraTitulosTabla">D&iacute;as Vacaciones</td>
	     <td class="cabeceraTitulosTabla">Tiempo Extra</td>
	     <td class="cabeceraTitulosTabla">Horas Laborables</td>
	     <td class="cabeceraTitulosTabla">Meta Productiva</td>
	     <td class="cabeceraTitulosTabla">Mes</td>
	    </tr>
<?
	
	
	while($li=mysql_fetch_array($ejesele)){
	    $idcap=$li["id_cap"];
	    $idemp=$li["no_empleado"];
	    $dias_lab=$li["dias_lab"];
	    $jornada=$li["jorna_lab"];
	    $vacaciones=$li["dias_li"];
	    $tiempo_extra=$li["tiem_ex"];
	    $horas_lab=$li["horas_la"];
	    $meta=$li["meta_pro"];
	    $mes=$li["mes"];
	
	$consulEmpl="select nombres,a_paterno,a_materno from cat_personal where no_empleado='".$idemp."'";
	$consulEmplEjec=mysql_query($consulEmpl,$this->conectar()) or die(mysql_error());
	$regis=mysql_fetch_array($consulEmplEjec);
	    $nombres=$regis["nombres"];
	    $a_paterno=$regis["a_paterno"];
	    $a_materno=$regis["a_materno"];
	    
	    $mese=array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
	//print_r($mese); exit; 

	    
?>
	    <tr>
		<td class="resultadosTablaBusqueda1"><?=$idcap;?></td>
		<td class="resultadosTablaBusqueda1"><?=$idemp;?></td>
		<td class="resultadosTablaBusqueda1"><?=$nombres;?> <?=$a_paterno;?> <?=$a_materno;?></td>
		<td class="resultadosTablaBusqueda1"><?=$dias_lab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$jornada;?></td>
		<td class="resultadosTablaBusqueda1"><?=$vacaciones;?></td>
		<td class="resultadosTablaBusqueda1"><?=$tiempo_extra;?></td>
		<td class="resultadosTablaBusqueda1"><?=$horas_lab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$meta;?></td>
		<td class="resultadosTablaBusqueda1"><?if(array_key_exists($mes,$mese)){
		echo $mese[$mes];}?></td>
	    </tr>
<?
	
	}
?>
	</table>            
<?             
    }
    
    public function modifica_mes(){
	$i="select * from CAP_MES";
	$ieje=mysql_query($i,$this->conectarBd()) or die(mysql_error());
?>
	<div id="Buscar2" style="border:1px solid #000000; width: 800px; height: 50px;margin: 10px auto 10px auto;"><table><tr>
	<td>Nº Empleado:<input type="text" name="noem" id="noem" size="5" /></td><td><label>Nombres</label><input type="text" name="noms" size="15" id="noms";/></td><td><label>Mes:</label> <select  name="mon" id="mon" >
                    <option value="undefined">Seleccione un Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                    </select></td>
	<td><input type="button" name="buscar" id="buscar"value="buscar" onclick="buscaParaModi();"></td>
	</tr></table></div>
	
	<table align="center" width="90%" BORDER="1" CELLPADDING="0" CELLSPACING="0" style="font-size: 12px;">
	    <tr>
		<td colspan="15"><center><strong>CAP_MES</strong></center></td>
	    </tr>
	    <tr>
		<td class="cabeceraTitulosTabla">No_Captura</td>
		<td class="cabeceraTitulosTabla">N°_empleado</td>
		<td class="cabeceraTitulosTabla">Dias Laborables</td>
		<td class="cabeceraTitulosTabla">Jornada Laboral</td>
		<td class="cabeceraTitulosTabla">Dias Vacaciones</td>
		<td class="cabeceraTitulosTabla">Tiempo Extra</td>
		<td class="cabeceraTitulosTabla">Horas Laborables</td>
		<td class="cabeceraTitulosTabla">Meta_productiva</td>
		<td class="cabeceraTitulosTabla">Mes</td>
	    </tr>
<?
	while($lista=mysql_fetch_array($ieje)){
	    $id_cap=$lista["id_cap"];
	    $id_emp=$lista["no_empleado"];
	    $diaslab=$lista["dias_lab"];
	    $jornada_lab=$lista["jorna_lab"];
	    $vacaciones_li=$lista["dias_li"];
	    $tiempo_extra_li=$lista["tiem_ex"];
	    $horas_lab_li=$lista["horas_la"];
	    $meta_pro=$lista["meta_pro"];
	    $mes_pro=$lista["mes"];
	    $mese=array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
?>
	    <tr>
		<td class="resultadosTablaBusqueda1"><a href="#" style="color: blue;font-size: 14px;" onclick="formmodi('<?=$id_cap;?>');"><?=$id_cap;?></a></td>
		<td class="resultadosTablaBusqueda1"><?=$id_emp;?></td>
		<td class="resultadosTablaBusqueda1"><?=$diaslab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$jornada_lab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$vacaciones_li;?></td>
		<td class="resultadosTablaBusqueda1"><?=$tiempo_extra_li;?></td>
		<td class="resultadosTablaBusqueda1"><?=$horas_lab_li;?></td>
		<td class="resultadosTablaBusqueda1"><?=$meta_pro;?></td>
		<td class="resultadosTablaBusqueda1"><?if(array_key_exists($mes_pro,$mese)){
		echo $mese[$mes_pro];}?></td>
	    </tr>
<?    
	}
    }
    
    public function formmodi($id){
	$consultita="select cat_personal.no_empleado,nombres, a_paterno, a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,horas_la,meta_pro,mes,numerosDias from iqe_rrhh_2010.cat_personal,2013_matriz_productiva.CAP_MES where iqe_rrhh_2010.cat_personal.no_empleado=2013_matriz_productiva.CAP_MES.no_empleado and  id_cap='$id' order by id_cap";
	$ejeconsultita=mysql_query($consultita,$this->conectarBd()) or die(mysql_error());
	$clase_obligaria="campo_obligatorio";
	$capmes="CAP_MES";
        $todas=mysql_fetch_array($ejeconsultita);
	$idcapp=$todas["id_cap"];
	$idem=$todas["no_empleado"];
	$nom=$todas["nombres"];
	$apaterno=$todas["a_paterno"];
	$amaterno=$todas["a_materno"];
	$diaslab=$todas["dias_lab"];
	$jornadalab=$todas["jorna_lab"];
	$vacacion=$todas["dias_li"];
	$tiempo=$todas["tiem_ex"];
	$horas=$todas["horas_la"];
	$metaal=$todas["meta_pro"];
	$meses=$todas["mes"];
	//$numerosDias=$todas["numerosDias"];
	$mese=array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");

?>
	<script type="text/javascript">
	    muestraCalendarioMod('<?=date("Y");?>','<?=$meses;?>','<?=date("d");?>');
	</script>
	<div id="modi" style="border: 0px solid #ff0000;">
	    <FORM id="asig_mes_modi" >
		<br> 
		 <!--<input type="hidden" name="actionm" id="action" value="insertar">-->	
		    <fieldset style="width: 700px; height: 150px; " >
		    <table border="0" align="left" cellspacing="5" style="margin: 10px; font-size: 12px;">
			<legend>Datos Personales</legend>     
			 <tr>
			    <td>Id Empleado:</td>
			    <td><input type="text" name="id_empleado" id="no_empleado"  value="<?=$idem;?>"class="<?=$clase_obligaria?>" readonly></td>
			 </tr>
			 <tr>
			    <td>Nombre:</td>
			    <td><input type="text" name="nombres" id="nombres" value="<?=$nom;?>" class="<?=$clase_obligaria?>" readonly></td>			 
			 </tr>
			 <tr>
			 <td>Apellido Paterno:</td>
			 <td><input type="text" name="a_paterno" id="a_paterno" value="<?=$apaterno;?>" class="<?=$clase_obligaria?>" readonly></td>
			 </tr>
			 <tr>
			 <td>Apellido Materno:</td>
			 <td><input type="text" name="a_materno" id="a_materno" value="<?=$amaterno;?>" class="<?=$clase_obligaria?>" readonly></td>     
			 </tr>
		    </table>
		    </fieldset>
		    <br>
                        <br>
                    <table border="0" cellpadding="1" cellspacing="1" width="700" style="margin: 10px; font-size: 12px;">
                        <tr>
                    <td width="120">Mes:
                    </td><td><select  name="mes" id="mes" onchange="muestraCalendarioMod()" class="<?=$clase_obligaria?>" <?=$sol?>>
                    <option value="<?=$meses;?>"><?if(array_key_exists($meses,$mese)){
		    echo $mese[$meses];};?></option>
		    <option value="undefined">Seleccione un Mes</option>
                    <option value="01">Enero</option>
                    <option value="02">Febrero</option>
                    <option value="03">Marzo</option>
                    <option value="04">Abril</option>
                    <option value="05">Mayo</option>
                    <option value="06">Junio</option>
                    <option value="07">Julio</option>
                    <option value="08">Agosto</option>
                    <option value="09">Septiembre</option>
                    <option value="10">Octubre</option>
                    <option value="11">Noviembre</option>
                    <option value="12">Diciembre</option>
                    </select>
                    </td></tr>
		    <!--<tr>
			    <td colspan="2">				
				<div id="calendarioDiasSeleccionadosMods" style="height: 250px;width: 430px;border: 1px solid #CCC;overflow: auto;"></div>
				<div title="Agregar Dias Seleccionados" onclick="agregarDiasSeleccionados()" style="float: right;width: 110px;border: 1px solid #CCC;background: #e1e1e1;height: 15px;padding: 5px;text-align: center;margin: 3px;">Agregar D&iacute;as</div>				
				<div style="clear: both;">&nbsp;</div>
			    </td>
			</tr>
                    <tr>
                    <td>
                    Dias Seleccionados:</td><td><textarea name="txtDiasSeleccionados" id="txtDiasSeleccionados" rows="3" cols="30"><?=$numerosDias;?></textarea>    
                    </td>    
                    </tr>-->
		    <tr>
                    <td>
                    Dias laborables:</td><td><input type="text" name="dias_lab" id="dias_lab"  class="<?=$clase_obligaria?>"  value="<?=$diaslab;?>"onkeyup="calcular();" onblur=" campovaloractu(this);" >    
                    </td>    
                    </tr>
                    <tr>
                    <td>
                    Jornada Laboral:</td><td> <input type="text" name="jorna_lab" id="jorna_lab"  class="<?=$clase_obligaria?>" value="<?=$jornadalab;?>" onkeyup="calcular();" onblur=" campovaloractu(this);" >    
                    </td>    
                    </tr>
                    <tr>
                    <td>
                    Dias Licencia:</td> <td><input type="text" name="dias_li" id="dias_li"  class="<?=$clase_obligaria?>" value="<?=$vacacion;?>" onkeyup="calcular();" onblur=" campovaloractu(this);"></td>    
                    </tr>
                    <tr>
                    <td>
                    Tiempo Extra: </td><td><input type="text" name="tiem_ex" id="tiem_ex"  class="<?=$clase_obligaria?>" value="<?=$tiempo;?>"onkeyup="calcular();" onblur=" campovaloractu(this);" >    
                    </td>
                    </tr>
                    <tr>
                    <td>Horas Laborables:</td><td><input type="text" name="horas_la" id="horas_la"  value="<?=$horas;?>" class="<?=$clase_obligaria?>" readonly></td>
                    </tr>
                    <tr>
                    <td>
                    Meta Productiva:</td><td><input type="text" name="meta_pro" id="meta_pro"  value="<?=$metaal;?>"  onkeyup="calcular();" class="<?=$clase_obligaria?>" ><label>%</label>
                    </td>    
                    </tr>
		    <tr>
			<td colspan="2"><hr align="center" width="99%"  size="3"/></td>
		    </tr>
		    <tr>
			<td colspan="2" style="text-align: right;"><input type="button" name="Registrar" value="Registrar" style="height: 40px;" onclick="ACTUALIZAR('<?=$capmes;?>','<?=$id;?>');"/></td>
		    </tr>
                    </table>                    			       
		     </FORM>
                </div>
                         <?  
    
            
    }
    
    public function actualiza($tac,$arreglo,$ids){
		$sql_campos="";
		$sql_valores="";
		//$prefijo2='SAT_';
		//$tres=str_replace($prefijo2,"",$t);
		$cuatro="id_cap";

		$separar_campos=explode("@@@",trim($arreglo));
		foreach ($separar_campos as $cam){
			$separar_campos2=explode("|||",trim($cam));
			$campoX=str_replace("txt_","",trim($separar_campos2[0]));
			$valorX=trim($separar_campos2[1]);			
			
			($sql_campos=="")? $sql_campos=$campoX : $sql_campos.=",".$campoX;
			($sql_valores=="")? $sql_valores=$valorX : $sql_valores.=",'".$valorX."'";
		}
		//print_r($sql_valores); exit;
		//print_r($sql_campos); exit;
		$pruba=explode(',',$sql_campos);
		$pruba2=explode(',',$sql_valores);
		$cuenta=count($pruba);
		$b= array ();
		$a=0;
		for($j=0;$j<$cuenta;$j++){
		
		/*if($pruba[$a]==""){
			unset($b[$i]);
		}
		else{*/
		$b[$j]=$pruba[$a]."=".$pruba2[$a];
		//}
		$a++;
		}
		
		$dos=implode(",",$b);
		//print_r($dos); exit;
		
		
		$sql_actualizar="UPDATE $tac SET $dos  where $cuatro=$ids";
		//print_r($sql_actualizar); exit;
		if (mysql_query($sql_actualizar,$this->conectarBd())){
			echo "<font style='font-size:14px;'><br><b>&nbsp;Registro Actualizado Correctamente.</b></font>";
		} else {
			echo "<font style='font-size:14px;'><br>&nbsp;Error SQL <br><br><b>&nbsp;El Registro NO se Actualizo.</b></font>";
		}

    
    
    }
    
    private function UltimoDia($anho,$mes){ 
	   if (((fmod($anho,4)==0) and (fmod($anho,100)!=0)) or (fmod($anho,400)==0)) { 
		$dias_febrero = 29; 
	   } else { 
		   $dias_febrero = 28; 
	   } 
	   if(($mes==1) || ($mes==3) || ($mes==5) || ($mes==7) || ($mes==8) || ($mes==10) || ($mes==12)){
		   $dias_mes="31";
	   }else if(($mes==4) ||($mes==6) ||($mes==9) ||($mes==11)){
		   $dias_mes="30";
	   }else if($mes==2){
		   $dias_mes=$dias_febrero;
	   }
	   return $dias_mes;
	}

	public function calendarizacion($mes,$anio,$diaActual,$diasSeleccionados){		
	    $mes=$mes;//date("m");
	    //año de la fecha
	    $anio=$anio;
	    //total de dias en el mes
	    $totalDias=$this->UltimoDia($anio,$mes);
	    $numeroDia=date("w", mktime (0,0,0,$mes,1,$anio));//mes dia año
	    $diaFecha=date("j", mktime (0,0,0,$mes,1,$anio));//mes dia año
	    $dia=1;
	    /*for($i=0;$i<6;$i++){
		for($j=0;$j<7;$j++){
                    if($numeroDia==$j){
                        echo date("j", mktime (0,0,0,$mes,$dia,$anio));
			$numeroDia+=1;
			$dia+=1;
                    }else{
			echo "x";m
                    }
		}
		$numeroDia=0;
		echo "<br>";
            }
            $dia=1;*/
	    //se extraen y se configura el calendario con los dias seleccionados
	    if($diasSeleccionados != "N/A"){
		//$diasSeleccionados=explode()
	    }
	    $meses=array("Enero","Febrero","Marzo","Abril","Mayo","Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre");
?>
		<table width="95%" border="0" cellspacing="0" cellpadding="1" style="font-size:10px; margin-left:5px; margin-top:5px; margin-right:5px;">                    
                    <tr>
                        <td colspan="7">Seleccione los dias laborables</td>
                    </tr>
                    <tr>
                        <td colspan="7" style="font-size:16px; text-align:center;"><?=$meses[$mes-1]." ".date("Y");?></td>
                    </tr>m
                    <tr>
	  		<td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Domingo</td>
			<td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Lunes</td>
                        <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Martes</td>
                        <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Miercoles</td>
                        <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Jueves</td>
                        <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">Viernes</td>
                        <td width="14%" style="border:1px solid #999; background:#ccc; text-align:center; height:40px;">S&aacute;bado</td>
	  	    </tr>
<?
			//se hace el recorrido por las semanas
			for($i=0;$i<6;$i++){
?>
			<tr>
<?				
				//se hace el recorrido por los dias de la semana
				for($j=0;$j<7;$j++){
				    if($numeroDia==$j){
					$diaMes=date("j", mktime (0,0,0,$mes,$dia,$anio));
					($diaMes==$diaActual) ? $clase="diaCalendarioActual" : $clase="diaCalendario";
						
?>						
			    <td valign="middle" style="height:20px; text-align:center;border: 1px solid #CCC;">
                        	<div class="<?=$clase;?>"><?=$diaMes;?><input type="checkbox" value="<?=$diaMes;?>"></div>
                            </td>
<?
						$numeroDia+=1;
						$dia+=1;
					}else{
?>
					<td><div class="diaCalendario">&nbsp;</div></td>
<?						
					}
					//se detiene el proceso en caso que sea igual al numero de dias
					if($diaMes==$totalDias)
						break;
				}
				$numeroDia=0;                                
?>
			 </tr>
<?
			//se detiene el proceso en caso que sea igual al numero de dias
						if($diaMes==$totalDias)
							break;
                        }
			$dia=1;			
			
?>
        	</tr>
        </table>    
<?
	}
	
	public function consultaPorParametro($numEmpl,$nombreEmpl,$mesSelect){
	    
	    
	    if( !empty( $numEmpl) || !empty($nombreEmpl) || $mesSelect != "undefined"){
		
		if( ! empty($numEmpl) && ! empty($nombreEmpl) && $mesSelect !="undefined") {
		$consBusc="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and nombres='".$nombreEmpl."' and mes='".$mesSelect."' and CAP_MES.no_empleado='".$numEmpl."' order by id_cap";    
		}else if( ! empty( $numEmpl ) && empty( $nombreEmpl ) && $mesSelect=="undefined") {
		$consBusc="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and CAP_MES.no_empleado='".$numEmpl."' order by id_cap";    
		}else if( empty( $numEmpl ) && ! empty( $nombreEmpl ) && $mesSelect=="undefined") {
		$consBusc="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and  nombres like '".$nombreEmpl."%' order by id_cap";    
		}else if( empty( $numEmpl ) && empty( $nombreEmpl ) && $mesSelect != "undefined") {
		$consBusc="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and mes='".$mesSelect."' order by id_cap";    
		}
	    
	    $consBuscEjec=mysql_query($consBusc,$this->conectarBd()) or die(mysql_error());
	    if(mysql_num_rows($consBuscEjec)==0){
?>
	    <script type="text/javascript">
	    alert("Error: No se encontraron conincidencias");
	    </script>
<?
	    }
	else{
	    
	    
	     
?>
		<table align="center" width="90%" BORDER="1" CELLPADDING="0" CELLSPACING="0" style="font-size: 12px;">
		<tr>
		<td colspan="15"><center><strong>CAP_MES</strong></center></td>
		</tr>
	    	<tr>
		<td class="cabeceraTitulosTabla">No_Captura</td>
		<td class="cabeceraTitulosTabla">N°_empleado</td>
		<td class="cabeceraTitulosTabla">Nombre Empleado</td>
		<td class="cabeceraTitulosTabla">D&iacute;as Laborables</td>
		<td class="cabeceraTitulosTabla">Jornada Laboral</td>
		<td class="cabeceraTitulosTabla">D&iacute;as Vacaciones</td>
		<td class="cabeceraTitulosTabla">Tiempo Extra</td>
		<td class="cabeceraTitulosTabla">Horas Laborables</td>
		<td class="cabeceraTitulosTabla">Meta Productiva</td>
		<td class="cabeceraTitulosTabla">Mes</td>
		</tr>
<?
	    while($filas=mysql_fetch_array($consBuscEjec)){
	    $nomEm=$filas["nombres"];
	    $apat=$filas["a_paterno"];
	    $ama=$filas["a_materno"];
	    $idCap=$filas["id_cap"];
	    $nuEm=$filas["no_empleado"];
	    $diLab=$filas["dias_lab"];
	    $joLab=$filas["jorna_lab"];
	    $diLi=$filas["dias_li"];
	    $tiEx=$filas["tiem_ex"];
	    $hoLab=$filas["horas_la"];
	    $mePro=$filas["meta_pro"];
	    $mesRe=$filas["mes"];
	    
	    $mese=array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
	//print_r($mese); exit; 

	    
?>
	    <tr>
		<td class="resultadosTablaBusqueda1"><?=$idCap;?></td>
		<td class="resultadosTablaBusqueda1"><?=$nuEm;?></td>
		<td class="resultadosTablaBusqueda1"><?=$nomEm;?> <?=$apat;?> <?=$ama;?></td>
		<td class="resultadosTablaBusqueda1"><?=$diLab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$joLab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$diLi;?></td>
		<td class="resultadosTablaBusqueda1"><?=$tiEx;?></td>
		<td class="resultadosTablaBusqueda1"><?=$hoLab;?></td>
		<td class="resultadosTablaBusqueda1"><?=$mePro;?></td>
		<td class="resultadosTablaBusqueda1"><?if(array_key_exists($mesRe,$mese)){
		echo $mese[$mesRe];}?></td>
	    </tr>
<?
	
	}
?>
	</table>            
	    
<?		
		
	}
	    
	    
	    
	}
	}

	public function ModiPorParametro($nuE,$nomEm,$mesRegis){
	    //echo"si llega aqui"; exit;
	if( !empty( $nuE) || !empty($nomEm) || $mesRegis != "undefined"){
		
		if( ! empty($nuE) && ! empty($nomEm) && $mesRegis !="undefined") {
		$consBusc2="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and nombres='".$nomEm."' and mes='".$mesRegis."' and CAP_MES.no_empleado='".$nuE."' order by id_cap";    
		}else if( ! empty( $nuE ) && empty( $nomEm ) && $mesRegis=="undefined") {
		$consBusc2="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and CAP_MES.no_empleado='".$nuE."' order by id_cap";    
		}else if( empty( $nuE ) && ! empty( $nomEm ) && $mesRegis=="undefined") {
		$consBusc2="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and  nombres like '".$nomEm."%' order by id_cap";    
		}else if( empty( $nuE ) && empty( $nomEm ) && $mesRegis!= "undefined") {
		$consBusc2="select nombres,a_paterno,a_materno,id_cap,CAP_MES.no_empleado,dias_lab,jorna_lab,dias_li,tiem_ex,
		horas_la,meta_pro,mes from CAP_MES,  iqe_rrhh_2010.cat_personal where iqe_rrhh_2010.cat_personal.no_empleado=CAP_MES.no_empleado
		and mes='".$mesRegis."' order by id_cap";    
		}
	    
	    $consBuscEjec2=mysql_query($consBusc2,$this->conectarBd()) or die(mysql_error());
	    if(mysql_num_rows($consBuscEjec2)==0){
?>
	    <script type="text/javascript">
	    alert("Error: No se encontraron conincidencias");
	    </script>
<?
	    }
	else{
	    
	    
	     
?>
		<table align="center" width="90%" BORDER="1" CELLPADDING="0" CELLSPACING="0" style="font-size: 12px;">
		<tr>
		<td colspan="15"><center><strong>CAP_MES</strong></center></td>
		</tr>
	    	<tr>
		<td class="cabeceraTitulosTabla">No_Captura</td>
		<td class="cabeceraTitulosTabla">N°_empleado</td>
		<td class="cabeceraTitulosTabla">Nombre Empleado</td>
		<td class="cabeceraTitulosTabla">D&iacute;as Laborables</td>
		<td class="cabeceraTitulosTabla">Jornada Laboral</td>
		<td class="cabeceraTitulosTabla">D&iacute;as Vacaciones</td>
		<td class="cabeceraTitulosTabla">Tiempo Extra</td>
		<td class="cabeceraTitulosTabla">Horas Laborables</td>
		<td class="cabeceraTitulosTabla">Meta Productiva</td>
		<td class="cabeceraTitulosTabla">Mes</td>
		</tr>
<?
	    while($line=mysql_fetch_array($consBuscEjec2)){
	    $nomEm2=$line["nombres"];
	    $apat2=$line["a_paterno"];
	    $ama2=$line["a_materno"];
	    $idCap2=$line["id_cap"];
	    $nuEm2=$line["no_empleado"];
	    $diLab2=$line["dias_lab"];
	    $joLab2=$line["jorna_lab"];
	    $diLi2=$line["dias_li"];
	    $tiEx2=$line["tiem_ex"];
	    $hoLab2=$line["horas_la"];
	    $mePro2=$line["meta_pro"];
	    $mesRe2=$line["mes"];
	    
	    $mese=array('01'=>"Enero",'02'=>"Febrero",'03'=>"Marzo",'04'=>"Abril",'05'=>"Mayo",'06'=>"Junio",'07'=>"Julio",'08'=>"Agosto",'09'=>"Septiembre",'10'=>"Octubre",'11'=>"Noviembre",'12'=>"Diciembre");
	//print_r($mese); exit; 

	    
?>
	    <tr>
		<td class="resultadosTablaBusqueda1"><a href="#" style="color: blue;font-size: 14px;" onclick="formmodi('<?=$idCap2;?>');"><?=$idCap2;?></a></td>
		<td class="resultadosTablaBusqueda1"><?=$nuEm2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$nomEm2;?> <?=$apat2;?> <?=$ama2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$diLab2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$joLab2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$diLi2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$tiEx2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$hoLab2;?></td>
		<td class="resultadosTablaBusqueda1"><?=$mePro2;?></td>
		<td class="resultadosTablaBusqueda1"><?if(array_key_exists($mesRe2,$mese)){
		echo $mese[$mesRe2];}?></td>
	    </tr>
<?
	
	}
?>
	</table>            
	    
<?		
		
	}
	    
	    
	    
	}    
	    
	    
	    
	}
	
	
	
	
	
	
	



}
    
    
    
    




?>