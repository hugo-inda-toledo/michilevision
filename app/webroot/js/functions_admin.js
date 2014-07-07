var raiz = "/michilevision";

function getSistemas()
{
		var usuarioId = document.getElementById('ProfilePermisoSistemaId').value;
		$("#segundoSelect").load(raiz+"/external_functions/ProfileAndPermisoSelect/" + usuarioId);
		
}

//Funcion usada en asignacion centro de costos
function getUsuarios()
{
		var usuarioId = document.getElementById('SelectUsuario').value;
		$("#sistemas").load(raiz+"/external_functions/SystemsAndCostCentersSelectForId/" + usuarioId);
		
}


function getSistemasPermisos(){
		var usuarioId = document.getElementById('select_usuarios').value;
		$("#permisos").load(raiz+"/external_functions/SistemasForPermisos/" + usuarioId);
}


function getPermisos2(){
		var sistemaId = document.getElementById('SelectSistema').value;
		$("#permisos2").load(raiz+"/external_functions/PermisosForPermisos/" + sistemaId);
	
}

function getSistemas()
{
		var usuarioId = document.getElementById('SelectUsuario').value;
		$("#sistemas").load(raiz+"/external_functions/SistemasSelectForId/" + usuarioId);
}

// primera funcion para modulo usuario_profiles
function getSistemasProfile()
{
			var usuarioId = document.getElementById('SelectSistema').value;
			$("#segundoSelect").load(raiz+"/external_functions/SistemasSelectForId/" + usuarioId);
}


function getProfilesAndPermisos()
{
		var usuarioId = document.getElementById('SelectSistema').value;
		$("#segundoSelect").load(raiz+"/external_functions/PermisosAndProfilesSelectForId/" + usuarioId);

}

// primera funcion para modulo usuario_profiles
function getSistemasProfile2()
{
			var usuarioId = document.getElementById('SelectSistema').value;
			$("#segundoSelect").load(raiz+"/external_functions/SistemasSelectForId/" + usuarioId);
}



/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////////funcion usada para asignar perfiles a usuarios///////////////////////////////////////
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getSistemasForProfiles()
{
		var usuarioId = document.getElementById('SelectUsuario').value;
		$("#sistemas").load(raiz+"/external_functions/getSistemasForProfiles/" + usuarioId);
}


function getProfiles()
{
		var sistemaId = document.getElementById('SelectSistema').value;
		$("#profiles").load(raiz+"/external_functions/ProfilesSelectForId/" + sistemaId);
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Funciones para reemplazos//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

//Funcion para llamar a funcion del controlador y retornar usuarios distintos a este
function getOtherUsuarios()
{
	var usuarioId = document.getElementById('SelectUsuarioReemplazado').value;
	$("#segundoSelect").load(raiz+"/external_functions/ReemplazoDiv/" + usuarioId);
}





function validateEndDate(id)
{
	var calendar = document.getElementById('datepickerUsuario').value;
	document.location.href = raiz+'/render_funds/setEndRenderDate/'+id+'/'+calendar;
}


///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Funcion para agregar comunas//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function getProvinces()
{
		var regionId = document.getElementById('region').value;
		$("#provinces").load(raiz + "/communes/gettingProvinces/" + regionId);
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Funcion para agregar direcciones//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

function validateAddressForUser()
{
	var forUser = document.getElementById('for_user').value;
	
	if(forUser == 1)
	{
		$('#users').show('fast');
	}
	else
	{
		$('#users').hide('fast');
	}
}

function getProvincesForAddress()
{
		var regionId = document.getElementById('region').value;
		
		$('#provinces').hide('fast');
		
		$("#loadingProvinces").html("<img src='"+ raiz +"/img/loading.gif' />");
		$("#provinces").load(raiz +"/addresses/gettingProvinces/" + regionId);
		$('#provinces').show('fast');
}

function getCommunesForAddress()
{
		var provinceId = document.getElementById('province').value;
		
		$('#communes').hide('fast');
		
		$("#loadingCommunes").html("<img src='"+ raiz +"/img/loading.gif' />");
		$("#communes").load(raiz + "/addresses/gettingCommunes/" + provinceId);
		$('#communes').show('fast');
}

///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////Funcion para Sistema Movilización//////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
function getCompanies()
{
		var transportId = document.getElementById('transport').value;
		
		$('#companies').hide('slow');
		
		$("#loadingCompanies").html("<img src='"+ raiz +"/img/loading.gif' />");
		$("#companies").load(raiz +"/transport_requests/gettingCompanies/" + transportId);
		$('#companies').show('slow');
}

function detectOption()
{
	if($("#TransportRequestAddForm input[name='data[Option][type_direction]']:radio").is(':checked')) 
	{
		$("#ticketSubmit").show("fast");
		
		if($("#TransportRequestAddForm input[name='data[Option][type_direction]']:checked").val() == 'me') 
		{
			$("#external").hide("fast");
			$("#management_users").hide("fast");
			$("#me").show("fast");
		}
		
		if($("#TransportRequestAddForm input[name='data[Option][type_direction]']:checked").val() == 'management_users') 
		{
			$("#external").hide("fast");
			$("#me").hide("fast");
			$("#management_users").show("fast");
		}
		
		if($("#TransportRequestAddForm input[name='data[Option][type_direction]']:checked").val() == 'external')
		{
			$("#me").hide("fast");
			$("#management_users").hide("fast");
			$("#external").show("fast");
		}
	}
}

function getDestination()
{

}