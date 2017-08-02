Array.prototype.isEmpty = function(){
	if ( this.length == 0 ){
		return true
	}
	return false
}
Array.prototype.lengthIs = function(length){
	if ( this.length == length ){
		return true
	}
	return false
}
String.prototype.set = function(key,value){
	var reg = new RegExp(key,"g")
	return this.replace(reg,value)
}
String.prototype.truncate = function(len,end){
	if ( len > this.length ){
		return this
	}else{
		var substr = this.substr(0,len)
		end = end ? end : "..."
		var trunc = substr + end
		return trunc
	}
	return this.replace(reg,value)
}

function callbackSuccessAjax(response){
		alertify.success("Listo.")
}
function callbackErrorAjax(response){
	responseJSON = response.responseJSON
	if ( responseJSON.errors_form ){
		alertify.error("Ha Ocurrido un Error")
	}
}

var DTspanish = {
	"sProcessing":     "Procesando...",
	"sLengthMenu":     "Mostrar _MENU_ registros",
	"sZeroRecords":    "No se encontraron resultados",
	"sEmptyTable":     "Ningún dato disponible en esta tabla",
	"sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
	"sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
	"sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
	"sInfoPostFix":    "",
	"sSearch":         "Buscar:",
	"sUrl":            "",
	"sInfoThousands":  ",",
	"sLoadingRecords": "Cargando...",
	"oPaginate": {
		"sFirst":    "Primero",
		"sLast":     "Último",
		"sNext":     "Siguiente",
		"sPrevious": "Anterior"
	},
	"oAria": {
		"sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
		"sSortDescending": ": Activar para ordenar la columna de manera descendente"
	}
}

alertify.defaults.glossary.acccpt = "Aceptar"
alertify.defaults.glossary.cancel = "Cancelar"
alertify.defaults.glossary.close = "Cerrar"
alertify.defaults.glossary.confirm = "Confirmar"
alertify.defaults.glossary.decline = "Rechazar"
alertify.defaults.glossary.maximize = "Maximizar"
alertify.defaults.glossary.ok = "Si"
alertify.defaults.glossary.restore = "Restaurar"
alertify.defaults.glossary.title = "ASIES"

function serializeForm(form){
	var formData = new FormData(form);
	$(form).find('input[type=file]').each(function(i, file) {
		$.each(file.files, function(n, file) {
			formData.append('file-'+i, file);
		})
	})
	$(form).find('input[type=checkbox]').each(function(i, input) {
		var value = input.checked ? 1 : 0
		formData.append(input.name,value);
	})
	return formData
}

var spanishMessagesJTable = {
	serverCommunicationError: 'Se ha producido un error al comunicarse con el servidor.',
	loadingMessage: 'Cargando...',
	noDataAvailable: '¡Datos no disponibles!',
	addNewRecord: 'Agregar',
	editRecord: 'Editar',
	areYouSure: '¿Estás seguro?',
	deleteConfirmation: 'Este registro se eliminará. ¿Estás seguro?',
	save: 'Guardar',
	saving: 'Guardando',
	cancel: 'Cancelar',
	deleteText: 'Borado',
	deleting: 'Borrando',
	error: 'Error',
	close: 'Cerrar',
	cannotLoadOptionsFor: 'No se pueden cargar las opciones para el campo {0}',
	pagingInfo: 'Mostrando {0}-{1} de {2}',
	pageSizeChangeLabel: 'Numero de filas',
	gotoPageLabel: 'Ir a la Pagina',
	canNotDeletedRecords: 'No se pueden eliminar {0} de {1} registros!',
	deleteProggress: 'Eliminando {0} de {1} registros, Procesando...'
}

//$("[data-find-task]").click(function(event){
$(document).ready(function(){

	$('select').select2({ width: '100%' });

	$("[data-find-treetask]").change(function(event){
		if ( eval($(this).data("find-task")) ){
			if ( ! Models.Tareas.exists(this.value) ) this.value = ""
		}else if (eval($(this).data("find-plan"))){
			if ( ! Models.Planes.exists(this.value) ) this.value = ""
		}
	})

	$("[data-find-treetask]").click(function(event){
		var selector = $(this).data("input-reference")
		var data = {
			find_task : eval($(this).data("find-task")) || false,
			find_plan : eval($(this).data("find-plan")) || false,
			type_plan : $(this).data("type-plan") || null,
		}
		openNewWindow("/planes/treeview",selector,data)
		//openNewWindow("/utilities/tasktree",selector,data)
	})

	$(".btn-logout").click(function(event){
		event.preventDefault()
		alertify.confirm("Desea cerrar Sesión",function(bool){
			if(bool){
				window.location.replace("/logout")
			}
		})
	})
})

function openNewWindow(href,input_reference,data){
	if(window.location.href == href) return
	var h = (window.innerHeight > 0) ? window.innerHeight : screen.height,
		w = (window.innerWidth > 0) ? window.innerWidth : screen.width,
		x = screen.width/2 - w/2,
		y = screen.height/2 - h/2;
	var win = window.open(href,"", "height="+h+",width="+w+",left="+x+",top="+y);
	win.ASIES_IS_WIN_POPUOT = true
	win.FIND_TASK = data.find_task || false
	win.FIND_PLAN = data.find_plan || true
	win.TYPE_PLAN = data.type_plan || null
	win.INPUT_REFERENCE = input_reference
}



Models = {
	"Utils" : {
		"dataToTreeview" : function(planes){
			function recursive(subplanes){
				var subplan = subplanes.map(function(subplan){
					if ( subplan.subplanes && subplan.subplanes.length > 0 ){
						subplan.subplanes = recursive(subplan.subplanes)
					}
					if ( subplan.ctarea ){
						var valor = subplan.ifhecha == "1" ? subplan.valor_tarea : 0
						return {
							text : subplan.ntarea + "(" + valor + ")",
							li_attr : {
								ctarea : subplan.ctarea,
								valor : valor,
							},
							type:"tareas"
						}
					}else if ( subplan.cplan ){
						var porcentaje = Models.Planes.calcular_porcentaje(subplan)
						var rel = "("+subplan.valor_plan + "/" + subplan.valor_total+")"
						return {
							text : subplan.nplan + rel + "(" + porcentaje +"%)",
							//icon : subplan.icono,
							li_attr : {
								cplan : subplan.cplan,
								valor : subplan.valor_plan,
								ctiplan : subplan.ctiplan,
								cpuntuacion : subplan.cpuntuacion,
								"data-state" : subplan.puntuacion.sigla,
								"data-color" : subplan.puntuacion.color,
							},
							type:subplan.tiplan.slug,
							children:subplan.subplanes
						}
					}
				})
				return subplan
			}
			planes = planes.map(function(plan){
				if ( plan.subplanes.length > 0 ){
					plan.subplanes = recursive(plan.subplanes)
				}
				var porcentaje = Models.Planes.calcular_porcentaje(plan)
				var rel = "("+plan.valor_plan + "/" + plan.valor_total+")"
				return {
					text : plan.nplan + rel + "("+porcentaje+"%)",
					//icon : plan.icono,
					state : {
						opened : true,
					},
					li_attr : {
						cplan : plan.cplan,
						valor : plan.valor_plan,
						cpuntuacion : plan.cpuntuacion,
						"data-state" : plan.puntuacion.sigla,
						"data-color" : plan.puntuacion.color,
						"select_treeview":"treeview___cplan__".set("__cplan__",plan.cplan)
					},
					type : plan.tiplan.slug,
					children:plan.subplanes
				}
			})
			return planes
		}
	},
	"Evidencias" : {
		"set" : function(key,data,cb){
			$.ajax({
				type : "PUT",
				url : "/api/evidencias/"+key+"/set",
				contentType:"application/json",
				success : cb,
				data : data,
				error : function(){}
			})
		}
	},
	"Tareas" : {
		"findOne" : function(key,cb){
			$.ajax({
				type : "GET",
				url : "/api/tareas/"+key,
				success : cb,
				error : cb
			})
		},
		"exists" : function(key,cb){
			Models.Tareas.findOne(key,function(response){
				if ( response.status == 404 ){
					cb(false)
				}if ( response.cplan) {
					cb(true)
				}
			})
		},
		cambiarEstado : function(cactividad,ctarea){
			var base_url_cambio_estado_tarea = "/api/actividades/__cactividad__/tareas/__ctarea__/do"
			$.ajax({
				url : base_url_cambio_estado_tarea.set("__ctarea__",ctarea).set("__cactividad__",cactividad),
				type : "POST",
				success : function(response){
					if ( response.ok ){
						alertify.success( response.msg )
					}else{
						alertify.warning( response.msg )
					}
				},
				error : function(response){},
			})
		}
	},
	"Actividades" : {
		"sendReminders" : function(){
			$.ajax({
				url : "/actividades/checkDates",
				type : "POST",
				success : function (response) {
					var status = response.status.map(state => { return {emails:state.emails,failures:state.failures} })
					var msg = "Se han enviado recordatorios a : "
					status.forEach( o => { msg += o.emails.join(",<br>") } )
					msg += "<br> Han Ocurrido los errores : "
					status.forEach( o => { msg += o.failures.join(",<br>") } )
					alertify.success("Los Recordatorios se han enviado.")
					alertify.alert(msg)
				},
				error : function (response) {
					alertify.error("Ha ocurrido un error al enviar los Recordatorios.")
				},
			})
		},
		"changeState" : function(id,newcstate){
			var url = "/api/Actividades/__id__/property?property=cestado",
			url = url.replace("__id__",id)
			if( typeof newcstate != "undefined" ){
				url = url + "&value=__cestado__"
				url = url.replace("__cestado__",newcstate)
			}
			$.ajax({
				url : url,
				type : "POST",
				dataType : "json",
				success : function(response){
					console.log(response)
				},
				error : function(response){

				}
			})
		},

		"asignarTarea" : function(cactividad,data,cb){
			if ( typeof data != 'object') return false
			var base_url_asignar_tarea = "/api/actividades/"+cactividad+"/assign"
			$.ajax({
				url : base_url_asignar_tarea,
				type : "POST",
				data : data,
				success : function(response){
					if ( response.ok ){
						alertify.success( response.msg )
						if ( typeof cb == "function" ) cb( response )
					}else{
						alertify.warning( response.msg )
						if ( typeof cb == "function" ) cb( response )
					}
				},
				error : function(response){},
			})
		},
		"removerTarea" : function(cactividad,ctarea,cb){
			var base_url_asignar_tarea = "/api/actividades/"+cactividad+"/assign"
			$.ajax({
				url : base_url_asignar_tarea,
				type : "DELETE",
				data : { ctarea: ctarea },
				success : function(response){
					if ( response.ok ){
						alertify.success( response.msg )
						if ( typeof cb == "function" ) cb( response )
					}else{
						alertify.warning( response.msg )
						if ( typeof cb == "function" ) cb( response )
					}
				},
				error : function(response){},
			})
		}

	},
	"Actas" : {
		"compromisos" :{
			agregar : function(idacta,compromisos){
			},
		},
	},
	"Planes" : {
		"messages" : {
			"create" : {
				"success" : "El Plan se ha creado Exitosamente",
				"error" : "Ops. Algo no ha salido bien.",
			},
			"validation" : {
				"multipleSelection" : "Solo debe Seleccionar un plan",
				"notSelection" : "Debe Seleccionar al menos un plan",
				"notSelectCorrectParent" : "Debe Seleccionar el plan correcto",
			}
		},
		"exists" : function(key,cb){
			Models.Planes.findOne(key,function(response){
				if ( response.status == 404 ){
					cb(false)
				}if ( response.cplan) {
					cb(true)
				}
			})
		},
		"findOne" : function(key,cb){
			$.ajax({
				type : "GET",
				url : "/api/planes/"+key,
				success : cb,
				error : cb
			})
		},
		"all" : function(cb){
			$.ajax({
				type : "GET",
				url : "/api/planes",
				success : cb,
				error : function(){}
			})
		},
		"calcular_porcentaje" : function(plan){
			var porcentaje =  parseInt( ( 100 * plan.valor_plan) / plan.valor_total );
			if ( isNaN(porcentaje) ){
				porcentaje = 0
			}
			return porcentaje
		},
		"recalcular" : function(cb){
			alertify.confirm("Desea recalcular los puntos de los Planes, recuerde que este proceso puede tardar varios minutos, en los cuales no se podrán realizar ninguna otra acción.",function(){
				waitingDialog.show('Recalculando Puntos',{onHide: function () {}});
				$.ajax({
					type:"POST",
					url:"/planes/recalcular",
					success:function(response){
						waitingDialog.hide();
						alertify.success('Puntos Recalculados')
						if ( cb ) cb(response)
					},
					error : function(res){
						alertify.error(res.responseJSON.message)
						waitingDialog.hide();
					}
				})
			})
		},
		"treeview" : function(cb,cplan){
			if ( typeof cplan == "undefined" ){
				$.ajax({
					type : "GET",
					url : "/api/planes",
					success : function(response){
						var data = Models.Utils.dataToTreeview(response)
						return cb(data);
					},
					error : function(){}
				})
			}else{
				$.ajax({
					type : "GET",
					url : "/api/planes/"+cplan,
					success : function(response){
						var data = Models.Utils.dataToTreeview([response])
						return cb(data);
					},
					error : function(){}
				})
			}
		}
	}
}

/**
 * Module for displaying "Waiting for..." dialog using Bootstrap
 *
 * @author Eugene Maslovich <ehpc@em42.ru>
 */

/*
 * Usage
	waitingDialog.show('Dialog with callback on hidden',{onHide: function () {alert('Callback!');}});
*/
var waitingDialog = waitingDialog || (function ($) {
    'use strict';

	// Creating modal dialog's DOM
	var $dialog = $(
		'<div class="modal fade" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog" aria-hidden="true" style="padding-top:15%; overflow-y:visible;">' +
		'<div class="modal-dialog modal-m">' +
		'<div class="modal-content">' +
			'<div class="modal-header"><h3 style="margin:0;"></h3></div>' +
			'<div class="modal-body">' +
				'<div class="progress progress-striped active" style="margin-bottom:0;"><div class="progress-bar" style="width: 100%"></div></div>' +
			'</div>' +
		'</div></div></div>');

	return {
		/**
		 * Opens our dialog
		 * @param message Custom message
		 * @param options Custom options:
		 * 				  options.dialogSize - bootstrap postfix for dialog size, e.g. "sm", "m";
		 * 				  options.progressType - bootstrap postfix for progress bar type, e.g. "success", "warning".
		 */
		show: function (message, options) {
			// Assigning defaults
			if (typeof options === 'undefined') {
				options = {};
			}
			if (typeof message === 'undefined') {
				message = 'Loading';
			}
			var settings = $.extend({
				dialogSize: 'm',
				progressType: '',
				onHide: null // This callback runs after the dialog was hidden
			}, options);

			// Configuring dialog
			$dialog.find('.modal-dialog').attr('class', 'modal-dialog').addClass('modal-' + settings.dialogSize);
			$dialog.find('.progress-bar').attr('class', 'progress-bar');
			if (settings.progressType) {
				$dialog.find('.progress-bar').addClass('progress-bar-' + settings.progressType);
			}
			$dialog.find('h3').text(message);
			// Adding callbacks
			if (typeof settings.onHide === 'function') {
				$dialog.off('hidden.bs.modal').on('hidden.bs.modal', function (e) {
					settings.onHide.call($dialog);
				});
			}
			// Opening dialog
			$dialog.modal();
		},
		/**
		 * Closes dialog
		 */
		hide: function () {
			$dialog.modal('hide');
		}
	};

})(jQuery);
