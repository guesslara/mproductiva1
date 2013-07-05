function ajaxApp(divDestino,url,parametros,metodo){	
	$.ajax({
	async:true,
	type: metodo,
	dataType: "html",
	contentType: "application/x-www-form-urlencoded",
	url:url,
	data:parametros,
	beforeSend:function(){ 
		$("#cargadorGeneral").show(); 
	},
	success:function(datos){ 
		$("#cargadorGeneral").hide();
		$("#"+divDestino).show().html(datos);		
	},
	timeout:90000000,
	error:function() { $("#"+divDestino).show().html('<center>Error: El servidor no responde. <br>Por favor intente mas tarde. </center>'); }
	});
}

function abrir(div){
	//alert(div);
	//exit;
	$("#"+div).show("fast");
}

function cerrarVentana(div){
	$("#"+div).hide();
}

function nuevoregis(){
	ajaxApp("muestraasignaciones","controladormes.php","action=formregis","POST");
}

function buscarEmpleado(){
	emp=document.getElementById("si").value;
	ajaxApp("ListarEmpleados","controladormes.php","action=consultarempleado&emp="+emp,"POST");
}

function insertarEmpleado(idEmpleado,nombres,apellidoP,apellidoM){
	//alert(idEmpleado);
	$("#no_empleado").attr("value",idEmpleado);
	$("#nombres").attr("value",nombres);
	$("#a_paterno").attr("value",apellidoP);
	$("#a_materno").attr("value",apellidoM);
	cerrarVentana('buscarEmpleado');
}

function VALIDAR(tabla){
	var campos=new Array();
	var valores=new Array();
	var sql_valores="";
	var ubicacion;
	var caracteres = "abcdefghijklmnopqrstuvwxyzñ1234567890ü ABCDEFGHIJKLMNOPQRSTUVWXYZÑáéíóúÁÉÍÓÚÜ/-()&.:-,_";
	
	for (var i=0;i<$("#frmAsignacionMes  input:text").length;i++){
		campos.push($("#frmAsignacionMes  input:text")[i].id);
		//alert(campos[i=7]);
		//exit;
		valores.push($("#frmAsignacionMes input:text")[i].value);
	}
	for(var e=0;e<$("#frmAsignacionMes select").length;e++){
		campos.push($("#frmAsignacionMes select")[e].id);
		//alert(otrosarreglo[e]);
		valores.push($("#frmAsignacionMes select")[e].value);
		//alert(valorselect[e]);
	}
	campos.splice(1,1);
	campos.splice(2,1);
	campos.splice(1,1);
	
	valores.splice(1,1);
	valores.splice(2,1);
	valores.splice(1,1);
	for (var i2=0;i2<campos.length;i2++){22
		if ($("#"+campos[i2]).attr("class")=="campo_obligatorio"&&(valores[i2]==""||valores[i2]==undefined||valores[i2]==null||valores[i2]=="undefined")){
			alert("Error: El campo ("+campos[i2]+") es obligatorio.");
			return;
		}
		if(valores[1]==0 || valores[2]==0 || valores[6]==0){
			alert("Error: Verifique los campos Dias laborales, Jornada laboral, Meta Productiva; deben ser mayores a 0");
			return;
		}
		
	
		for (var j=0;j<valores[i2].length;j++){  // recorrido de string para buscar caracteres no validos en la cadena  
			ubicacion = valores[i2].substring(j, j + 1)
			//alert(valores[j]);		
			//alert(ubicacion);
			//exit;
			if (caracteres.indexOf(ubicacion) != -1) {  
				   
			}else{  
				alert("ERROR: No se acepta el caracter '" + ubicacion + "'.")  
				return;
			}
		}
		
		//acaba for valores
		if (sql_valores==""){
			sql_valores=campos[i2]+"|||"+valores[i2];
			//alert(sql_valores); exit;
		} else {
			sql_valores+="@@@"+campos[i2]+"|||"+valores[i2];
			//alert(sql_valores); exit;	
		}
	
		
	}
		
                     
	
	//alert(sql_valores);
	//exit;
	//se recupera los numeros de los dias
	//var numerosDias=$("#txtDiasSeleccionados").val();
	//sql_valores=sql_valores+"@@@numerosDias|0||"+numerosDias;
	
        if (confirm("¿Desea guardar el registro?")){
		ajaxApp("muestraasignaciones","controladormes.php","action=insertarregistro&tabla="+tabla+"&valores="+sql_valores,"POST");
	}
}

function calcular(){
	//alert("aki");
	a=document.getElementById("dias_lab").value;
	//alert(a);
	b=document.getElementById("jorna_lab").value;
	c=document.getElementById("dias_li").value;
	d=document.getElementById("tiem_ex").value;

	if(isNaN(a)||isNaN(b)||isNaN(c)||isNaN(d)){
		alert("Error: El campo Dias Laborables solo admite numeros");
		return false;
	}else{
		aa=parseInt(a);//dias laborables
		bb=parseInt(b);//jornada laboral
		cc=parseInt(c);//dias licencia
		
		dd=parseFloat(d);//tiempo extra
		diasli=cc*bb;
		resu=aa*bb; //calculo para dias totales laborables
		otro=resu-diasli;//calculo si hay dias de vacaciones
		////otromas=float
		otromas= otro+dd;// resultado total de horas laborables
		//alert(otromas);
		
		
		$("#horas_la").attr("value",otromas);

	}
	f=document.getElementById("meta_pro").value;
	if(f>100){
		alert(" En el campo Meta Productiva debe ingresar un porcentaje entre 1 y 100 ");
		$("#meta_pro").val('');
	}
}

function consultaregis(){	
	ajaxApp("muestraasignaciones","controladormes.php","action=consultar","POST");
}

function modificar(){
	ajaxApp("muestraasignaciones","controladormes.php","action=modificar","post");
}

function formmodi(idcap){
	//alert(idcap);
	//exit;
	ajaxApp("muestraasignaciones","controladormes.php","action=formmodi&idcap="+idcap,"post");
}

function ACTUALIZAR(tac,id){
	//alert("aqui llega");exit;
	var campos=new Array();
	var valores=new Array();
	var sql_valores="";
	var ubicacion;
	var caracteres = "abcdefghijklmnopqrstuvwxyzñ1234567890ü ABCDEFGHIJKLMNOPQRSTUVWXYZÑáéíóúÁÉÍÓÚÜ/-()&.:-,_";
	
       for (var i=0;i<$("#asig_mes_modi  input:text").length;i++){

	campos.push($("#asig_mes_modi  input:text")[i].id);
	//alert(campos[i=7]);
	//exit;
	valores.push($("#asig_mes_modi input:text")[i].value);
	
	}
	for(var e=0;e<$("#asig_mes_modi select").length;e++){
	campos.push($("#asig_mes_modi select")[e].id);
	//alert(otrosarreglo[e]);
	      
	valores.push($("#asig_mes_modi select")[e].value);
	//alert(valorselect[e]);

	}
	campos.splice(1,1);
	campos.splice(2,1);
	campos.splice(1,1);
	
	valores.splice(1,1);
	valores.splice(2,1);
	valores.splice(1,1);
	
	//alert(valores);
	//exit;
	
	
	
	for (var i2=0;i2<campos.length;i2++){
	//pagina de jquery  http://visualjquery.com/
        if ($("#"+campos[i2]).attr("class")=="campo_obligatorio"&&(valores[i2]==""||valores[i2]==undefined||valores[i2]==null||valores[i2]=="undefined")){
        alert("Error: El campo ("+campos[i2]+") es obligatorio.");
        return;
	}
	
	if(valores[1]==0 || valores[2]==0 || valores[6]==0){
			alert("Error: Verifique los campos Dias laborales, Jornada laboral, Meta Productiva; deben ser mayores a 0");
			return;
	}
	//alert(campos[i2]);		     
        for (var j=0;j<valores[i2].length;j++){  // recorrido de string para buscar caracteres no validos en la cadena  
        ubicacion = valores[i2].substring(j, j + 1)
        //alert(valores[j]);		
	//alert(ubicacion);
	//exit;
        if (caracteres.indexOf(ubicacion) != -1) {  
                           
        }
        else {  
        alert("ERROR: No se acepta el caracter '" + ubicacion + "'.")  
        return; 
        }  
        }
        if (sql_valores==""){
         sql_valores=campos[i2]+"|||"+valores[i2];
	 } else {
        sql_valores+="@@@"+campos[i2]+"|||"+valores[i2];
		
	}
                     
	}
	//alert(sql_valores);
	//exit;
        if (confirm("¿Desea actualizar el registro?")){
                 ajaxApp("muestraasignaciones","controladormes.php","action=actualizar&tac="+tac+"&valores="+sql_valores+"&id="+id,"POST");
	      }

	
}
function verificaMes(){
	$("#divVerificacion").html("");
	noEmpleado=$("#no_empleado").val();//se recupera el numero del empleado
	//mesVerificar=$("#mes").val()//se recupera el mes
	//ajaxApp("divVerificacion","controladormes.php","action=verificarEmpleado&noEmpleado="+noEmpleado+"&mes="+mesVerificar,"POST");
	//ajaxApp("calendarioDiasSeleccionados","controladormes.php","action=verMesConfiguracion&mes="+mesVerificar,"POST");
}
function agregarDiasSeleccionados(){
	var diasSel=""; var cuant0osDias=0;
	for (var i=0;i<document.frmAsignacionMes.elements.length;i++){
		if (document.frmAsignacionMes.elements[i].type=="checkbox"){
			if (document.frmAsignacionMes.elements[i].checked){
				cuantosDias+=1;
				if (diasSel=="")
					diasSel=diasSel+document.frmAsignacionMes.elements[i].value;
				else
					diasSel=diasSel+","+document.frmAsignacionMes.elements[i].value;
			}	
		}
	}
	
	if(diasSel==""){
		alert('Seleccione por lo menos un dia para realizar la operacion');
	}else{
		//alert(diasSel);
		$("#txtDiasSeleccionados").attr("value",diasSel);
		$("#dias_lab").attr("value",cuantosDias);
	}
}22
function muestraCalendarioMod(anio,mes,dia,diasSeleccionados){
	ajaxApp("calendarioDiasSeleccionadosMods","controladormes.php","action=verMesConfiguracion&mes="+mes,"POST");
}
function campoValor0(valor){
	if(valor.value==""){
	valor.value=0;	
	}
}

function campovaloractu(valact){
	
	if(valact.value==""){
		valact.value=0;
	}
}

function buscaPorParametro(){
	numEmpl=document.getElementById("noempleado").value;
	nombreEmpl=document.getElementById("nombres").value;
	mesSelect=$("#mes").val();
	
	if(numEmpl=="" && nombreEmpl=="" && mesSelect=="undefined"){
		alert("Error: Debe llenar algún campo para la busqueda");
	ajaxApp("muestraasignaciones","controladormes.php","action=consultar","POST");
	//alert(mesSelect);
	//exit;
	}else{
	ajaxApp("muestraasignaciones","controladormes.php","action=busqueda&numEmpl="+numEmpl+"&nombreEmpl="+nombreEmpl+"&mesSelect="+mesSelect,"POST");
	}
}

function buscaParaModi(){
	nuE=document.getElementById("noem").value;
	nomEm=document.getElementById("noms").value;
	mesRegis=$("#mon").val();
	if(nuE=="" && nomEm=="" && mesRegis=="undefined"){
	       alert("Error: Debe llenar algún campo para la busqueda");
	ajaxApp("muestraasignaciones","controladormes.php","action=modificar","post");       
	}else{
	ajaxApp("muestraasignaciones","controladormes.php","action=busqueda2&nuE="+nuE+"&nomEm="+nomEm+"&mesRegis="+mesRegis,"POST");	
	}
	
}

