// Copia el contenido de los Tags al portapapeles
function copyToClipboard(elemento) {
	var $temp = $("<input>")
	$("body").append($temp);
	$temp.val(document.getElementById(elemento).textContent).select();
	document.execCommand("copy");
	$temp.remove();
	alert("Copiado al Portapapeles "+document.getElementById(elemento).textContent)
}

var cssoriginal = "";
cssoriginal = cssoriginal + " .link {";
cssoriginal = cssoriginal + "     color: rgb(217, 131, 166);";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .row{";
cssoriginal = cssoriginal + "     vertical-align:top;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .card{";
cssoriginal = cssoriginal + "     min-height:150px;";
cssoriginal = cssoriginal + "     padding: 5px;";
cssoriginal = cssoriginal + "     margin-bottom:20px;";
cssoriginal = cssoriginal + "     height:0px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .card-cell{";
cssoriginal = cssoriginal + "     background-color:rgb(255, 255, 255);";
cssoriginal = cssoriginal + "     overflow:hidden;";
cssoriginal = cssoriginal + "     border-radius: 3px;";
cssoriginal = cssoriginal + "     padding: 0;";
cssoriginal = cssoriginal + "     text-align:center;";
cssoriginal = cssoriginal + "   }";

cssoriginal = cssoriginal + "   .centrar-caja{";
cssoriginal = cssoriginal + "   display: inline-block;";
cssoriginal = cssoriginal + "   justify-content: center;";
cssoriginal = cssoriginal + "   align-items: center;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .boton-personalizado {";
cssoriginal = cssoriginal + "   margin: 5px;";
cssoriginal = cssoriginal + "   width: 200px;";
cssoriginal = cssoriginal + "   max-width: 200px;";
cssoriginal = cssoriginal + "   padding: 20px;";
cssoriginal = cssoriginal + "   text-align: center;";
cssoriginal = cssoriginal + "   background-color:#7892c2;";
cssoriginal = cssoriginal + "   border-radius:5px;";
cssoriginal = cssoriginal + "   display:inline-block;";
cssoriginal = cssoriginal + "   cursor:pointer;";
cssoriginal = cssoriginal + "   color:#ffffff;";
cssoriginal = cssoriginal + "   font-family:Arial;";
cssoriginal = cssoriginal + "   font-size:19px;";
cssoriginal = cssoriginal + "   text-decoration:none;";
cssoriginal = cssoriginal + "   }";

cssoriginal = cssoriginal + "   .button{";
cssoriginal = cssoriginal + "     font-size:12px;";
cssoriginal = cssoriginal + "     padding: 10px 20px;";
cssoriginal = cssoriginal + "     background-color:rgb(217, 131, 166);";
cssoriginal = cssoriginal + "     color:rgb(255, 255, 255);";
cssoriginal = cssoriginal + "     text-align:center;";
cssoriginal = cssoriginal + "     margin-right:auto;";
cssoriginal = cssoriginal + "     margin-left:auto;";
cssoriginal = cssoriginal + "     width:350px;";
cssoriginal = cssoriginal + "     border-radius: 3px;";
cssoriginal = cssoriginal + "     font-weight:300;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .card-title{";
cssoriginal = cssoriginal + "     font-size:25px;";
cssoriginal = cssoriginal + "     font-weight:300;";
cssoriginal = cssoriginal + "     color:rgb(68, 68, 68);";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .card-content{";
cssoriginal = cssoriginal + "     font-size:13px;";
cssoriginal = cssoriginal + "     line-height:20px;";
cssoriginal = cssoriginal + "     color:rgb(111, 119, 125);";
cssoriginal = cssoriginal + "     padding: 10px 20px 0 20px;";
cssoriginal = cssoriginal + "     vertical-align:top;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .container{";
cssoriginal = cssoriginal + "     font-family: Helvetica, serif;";
cssoriginal = cssoriginal + "     min-height:150px;";
cssoriginal = cssoriginal + "     padding: 5px;";
cssoriginal = cssoriginal + "     margin:auto;";
cssoriginal = cssoriginal + "     height:0px;";
cssoriginal = cssoriginal + "     width:90%;";
cssoriginal = cssoriginal + "     max-width:550px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .footer{";
cssoriginal = cssoriginal + "     margin-top: 50px;";
cssoriginal = cssoriginal + "     color:rgb(152, 156, 165);";
cssoriginal = cssoriginal + "     text-align:center;";
cssoriginal = cssoriginal + "     font-size:11px;";
cssoriginal = cssoriginal + "     padding: 5px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .quote {";
cssoriginal = cssoriginal + "     font-style: italic;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + " ";
cssoriginal = cssoriginal + "   .list-item{";
cssoriginal = cssoriginal + "     height:auto;";
cssoriginal = cssoriginal + "     width:100%;";
cssoriginal = cssoriginal + "     margin: 0 auto 10px auto;";
cssoriginal = cssoriginal + "    padding: 5px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .list-item-cell{";
cssoriginal = cssoriginal + "     background-color:rgb(255, 255, 255);";
cssoriginal = cssoriginal + "     border-radius: 3px;";
cssoriginal = cssoriginal + "     overflow: hidden;";
cssoriginal = cssoriginal + "     padding: 0;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .list-cell-left{";
cssoriginal = cssoriginal + "     width:30%;";
cssoriginal = cssoriginal + "     padding: 0;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .list-cell-right{";
cssoriginal = cssoriginal + "     width:70%;";
cssoriginal = cssoriginal + "     color:rgb(111, 119, 125);";
cssoriginal = cssoriginal + "     font-size:13px;";
cssoriginal = cssoriginal + "     line-height:20px;";
cssoriginal = cssoriginal + "     padding: 10px 20px 0px 20px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .list-item-content{";
cssoriginal = cssoriginal + "     border-collapse: collapse;";
cssoriginal = cssoriginal + "     margin: 0 auto;";
cssoriginal = cssoriginal + "     padding: 5px;";
cssoriginal = cssoriginal + "     height:150px;";
cssoriginal = cssoriginal + "     width:100%;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .list-item-image{";
cssoriginal = cssoriginal + "     color:rgb(217, 131, 166);";
cssoriginal = cssoriginal + "     font-size:45px;";
cssoriginal = cssoriginal + "     width: 100%;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + " ";
cssoriginal = cssoriginal + "   .grid-item-image{";
cssoriginal = cssoriginal + "     line-height:150px;";
cssoriginal = cssoriginal + "     font-size:50px;";
cssoriginal = cssoriginal + "     color:rgb(120, 197, 214);";
cssoriginal = cssoriginal + "     margin-bottom:15px;";
cssoriginal = cssoriginal + "     width:100%;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-row {";
cssoriginal = cssoriginal + "     margin: 0 auto 10px;";
cssoriginal = cssoriginal + "     padding: 5px 0;";
cssoriginal = cssoriginal + "     width: 100%;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-card {";
cssoriginal = cssoriginal + "     width:100%;";
cssoriginal = cssoriginal + "     padding: 5px 0;";
cssoriginal = cssoriginal + "     margin-bottom: 10px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-card-cell{";
cssoriginal = cssoriginal + "     background-color:rgb(255, 255, 255);";
cssoriginal = cssoriginal + "     overflow: hidden;";
cssoriginal = cssoriginal + "     border-radius: 3px;";
cssoriginal = cssoriginal + "     text-align:center;";
cssoriginal = cssoriginal + "     padding: 0;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-card-content{";
cssoriginal = cssoriginal + "     font-size:13px;";
cssoriginal = cssoriginal + "     color:rgb(111, 119, 125);";
cssoriginal = cssoriginal + "     padding: 0 10px 20px 10px;";
cssoriginal = cssoriginal + "     width:100%;";
cssoriginal = cssoriginal + "     line-height:20px;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-cell2-l{";
cssoriginal = cssoriginal + "     vertical-align:top;";
cssoriginal = cssoriginal + "     padding-right:10px;";
cssoriginal = cssoriginal + "     width:50%;";
cssoriginal = cssoriginal + "   }";
cssoriginal = cssoriginal + "   .grid-item-cell2-r{";
cssoriginal = cssoriginal + "     vertical-align:top;";
cssoriginal = cssoriginal + "     padding-left:10px;";
cssoriginal = cssoriginal + "     width:50%;";
cssoriginal = cssoriginal + "   }";



var arrDataTpl;
var tplselected;
var cssselected;
var tplselectedID="";
var tpleditable=1;
var tlpnombre = "";
function chgtpl(prnval)
{
	$fila = arrDataTpl[prnval].split("|");
	$('#lblFecha').text($fila[1]);
	$('#lblTipo').text($fila[4]);
	tpleditable = $fila[3];
	
	$.ajax({
		type: "GET",
		async: false, 
		data:{'qry':'gettplbyid','prnIdtpl':$fila[0]},
		url: "services/templates.php",
		success: function(r)
		{
			$('#tplpreview').html(r.tpl + "<style>"+r.css+"</style>");	
			tplselectedID = $fila[0];
			tplselected=r.tpl;
			cssselected=r.css;
			tlpnombre = $('select[name="cbtpl"] option:selected').text();
			$('#tpl_new_name').val(tlpnombre);
			if (tpleditable==1)
			{
				opt_guardar.style.display = 'block';
				btnguardar.style.display = 'block';
			}
			else
			{
				opt_guardar.style.display = 'none';
				btnguardar.style.display = 'none';
			}
			opt_guardarcomo.style.display = 'block';
		btnguardarcomo.style.display = 'block';
		}
	});
}

function savetplfree(prnaction, prncss, prnhtml)
{
	localid = tplselectedID;
	if (prnaction) localid='';
	
	$.ajax({
		type: "POST",
		async: false, 
		data:{'qry':'saveas','html':prnhtml,'css':prncss,'rnd':Math.random(),'eventoID':eventoID,'name':$('#tpl_new_name').val(),'id':localid},
		url: "services/templates.php",
		success: function(r)
		{
			if (r.id!='')
			{
				tplselectedID = r.id;
				tpleditable=1;
				console.log("tplselectedID->" + tplselectedID);
				console.log("tpleditable->" + tpleditable);
			}
			alert( "Template guardado satisfactoriamente" );
			opt_guardar.style.display = 'none';
			opt_guardarcomo.style.display = 'none';
			opt_guardarnuevo.style.display = 'none';
			btnguardar.style.display = 'none';
			btnguardarcomo.style.display = 'none';
		}
	});
}

$( document ).ready(function() {
	// Setea bot?n de env?o de correos
	
	$('#tpl_new_name').val("");	
	$( "#btnloadtpl" ).click(function( event ) {
		localStorage.setItem("content", tplselected);
		localStorage.setItem("style", cssoriginal + cssselected);
		md2.close();
		$('body').loading({message:'Cargando Template'});
		
		window.location = window.location;
	});
	
	$( "#btnguardar" ).click(function( event ) {
		if ($('#tpl_new_name').val()=='') 
		{
			alert("Debe especificar un nombre para el template");
			return;
		}
		if (tplselectedID=='')
		{
			savetplfree(true,editor.getCss(),editor.getHtml());
		}
		else
		{
			savetplfree(false,editor.getCss(),editor.getHtml());
		}
		md3.close();
	});
	
	$( "#btnguardarcomo" ).click(function( event ) {
		if ($('#tpl_new_name').val()=='') 
		{
			alert("Debe especificar un nombre para el template");
			return;
		}
		savetplfree(true,editor.getCss(),editor.getHtml());
		md3.close();
	});
	
	
	$( "#target" ).submit(function( event ) {
		event.preventDefault();
		$.ajax({
			type: "POST",
			async: false, 
			data:{'html':editor.getHtml(),'css':editor.getCss(),'mail':ticketmail.value,'rnd':Math.random(),'eventoID':eventoID},
			url: "services/mail.php",
			success: function(r)
			{
				alert( "Correo enviado a " + ticketmail.value + " satisfactoriamente." );
				md.close();
			}
		});
	});
	
	
	
	var prvhtml="";
	var prvcss=cssoriginal;
	if (typeof(localStorage.getItem("content")) != null) 
	{
		prvhtml = localStorage.getItem("content");
		prvcss = cssoriginal + localStorage.getItem("style");
		localStorage.removeItem("content")
		localStorage.removeItem("style")
	}
	

	// Inicializaci?n del editor
	var editor = grapesjs.init({
        clearOnRender: false,
        height: '100%',
      	components: prvhtml,
      	style: prvcss,
		//fromElement: true,
        storageManager: {
          id: 'gjs-nl-' + Math.random(),
        },
        assetManager: {
          upload: 1,
		  //upload: 'http://venoot-stage.us-west-2.elasticbeanstalk.com/api/uploadExtraImages',
		  upload: 'uploads/index.php',
		  //'uploads/index.php',        //for temporary storage
          uploadText: 'Puede cargar directamente una imagen aqu&iacute;',
		  uploadFile: function(e) {
			var files = e.dataTransfer ? e.dataTransfer.files : e.target.files;
			var formData = new FormData();
			for(var i in files){
				//alert(files[i]);
				//formData.append('file-'+i, files[i]) //containing all the selected images from local
				formData.append('image', files[i]) //containing all the selected images from local
			}
			$.ajax({
				url: 'http://venoot-stage.us-west-2.elasticbeanstalk.com/api/uploadExtraImages',
				//url: 'uploads/index.php',
				type: 'POST',
							data: formData,
							contentType:false,
					crossDomain: true,
					dataType: 'json',
					mimeType: "multipart/form-data",
					processData:false,
					success: function(result){
									var myJSON = [];
									$.each( result['data'], function( key, value ) {
											myJSON[key] = value;    
											//alert(value["name"]);
											
											$.ajax({
												type: "GET",
												async: false, 
												data:{'image_new':value["name"],'event':eventoID},
												url: "services/extra_images.php"
											});
											
											
									});
									var images = myJSON;    
							editor.AssetManager.add(images); //adding images to asset manager of GrapesJS
								}
				});
		  },
		},
        container : '#gjs',
		
        plugins: ['gjs-preset-newsletter', 'gjs-plugin-ckeditor'],
        pluginsOpts: {
          'gjs-preset-newsletter': {
		  	modalTitleImport: 'Importar HTML',
			modalTitleExport: 'Exportar HTML',
			modalBtnImport :'Importar',
            modalLabelImport: 'Copie su c&oacute;digo HTML personalizado aqu&iacute;',
            modalLabelExport: 'Copie &eacute;ste codigo HTML y utilizelo donde desee',
            codeViewerTheme: 'material',
            importPlaceholder: '',
            cellStyle: {
              'font-size': '12px',
              'font-weight': 300,
              'vertical-align': 'top',
              color: 'rgb(111, 119, 125)',
              margin: 0,
              padding: 0,
            }
          },
          'gjs-plugin-ckeditor': {
            position: 'center',
            options: {
              startupFocus: true,
              extraAllowedContent: '*(*);*{*}', 
              allowedContent: true, 
              enterMode: CKEDITOR.ENTER_BR,
              extraPlugins: 'sharedspace,justify,colorbutton,panelbutton,font',
              toolbar: [
                { name: 'styles', items: ['Font', 'FontSize' ] },
                ['Bold', 'Italic', 'Underline', 'Strike'],
                {name: 'paragraph', items : [ 'NumberedList', 'BulletedList']},
                {name: 'links', items: ['Link', 'Unlink']},
                {name: 'colors', items: [ 'TextColor', 'BGColor' ]},
              ],
            }
          }
        }
    });

	// Carga Imagenes disponibles para el evento.
	am = editor.AssetManager;
	$.ajax({
		type: "POST",
		async: false, 
		data:{'event':eventoID},
		url: "services/images.php",
		success: function(r)
		{
			list_images = r.images.split(",");
			list_images.forEach(function(element) {
				am.remove(element);
				am.add([element])
			});
		}
	});
	
	var mdlClass = 'gjs-mdl-dialog-sm';
	var mdlClass2 = 'gjs-mdl-dialog2-sm';
	var mdlClass3 = 'gjs-mdl-dialog-sm';
	var pnm = editor.Panels;
	var cmdm = editor.Commands;
	var md = editor.Modal;
	var md2 = editor.Modal;
	var md3 = editor.Modal;
	
	// Agrega nuevos botones con funcionalidades
	pnm.addButton('options', [{
		id: 'undo', // Deshacer
		className: 'fa fa-undo',
		attributes: {title: 'Deshacer'},
		command: function(){ editor.runCommand('core:undo') }
		},
	{
		id: 'redo', // Rehacer
		className: 'fa fa-repeat',
		attributes: {title: 'Rehacer'},
		command: function(){ editor.runCommand('core:redo') }
	},
	{
		id: 'clear-all', // Limpia todo el contenido del editor
		className: 'fa fa-power-off icon-blank',
		attributes: {title: 'Limpiar Todo'},
		command: {
			run: function(editor, sender) {
				sender && sender.set('active', false);
				if(confirm('Desea eliminar todo?')){
					editor.DomComponents.clear();
					setTimeout(function(){localStorage.clear();},0);
				}
			}
		}
	},
	{
		id: 'view-info', // Enviar prueba de correo
		className: 'fa fa-paper-plane',
		command: 'open-info',
		attributes: {
			'title': 'Enviar correo de Prueba',
			'data-tooltip-pos': 'bottom',
		}
	},
	{
		id: 'view-tpl', // Listado de Templates 
		className: 'fa fa-sticky-note',
		command: 'open-tpl',
		attributes: {
			'title': 'Templates',
			'data-tooltip-pos': 'bottom',
		}
	},
	{
		id: 'save-tpl', // Guarda Templates 
		className: 'fa fa-save',
		command: 'save-tpl',
		attributes: {
			'title': 'Guardar',
			'data-tooltip-pos': 'bottom',
		}
	}
	]);
	
	// Agrega funcionalidad de Bot?n de Correo
	var infoContainer = document.getElementById("info-panel");
	cmdm.add('open-info', {
		run: function(editor, sender) {
			
			sender.set('active', 0);
			var mdlDialog = document.querySelector('.gjs-mdl-dialog');
			mdlDialog.className += ' ' + mdlClass;
			infoContainer.style.display = 'block';
			md.setTitle('Env&iacute;o de correo de prueba');
			md.setContent('Env&iacute;e una prueba real de &eacute;ste correo para verificar su correcto funcionamiento.');
			md.setContent(infoContainer);
			md.open();
			md.getModel().once('change:open', function() {
				mdlDialog.className = mdlDialog.className.replace(mdlClass, '');
			})
			document.getElementById("info-panel").style.height="200px";
		}
	});
	
	//Agrega funcionalidad de Cargar Template
	var tplContainer = document.getElementById("tpl-panel");
	cmdm.add('open-tpl', {
		run: function(editor, sender) {
			sender.set('active', 0);
			var mdlDialog2 = document.querySelector('.gjs-mdl-dialog');
			mdlDialog2.className += ' ' + mdlClass2;
			tplContainer.style.display = 'block';
			md2.setTitle('Selecci&oacute;n de Templates');
			md2.setContent(tplContainer);
			md2.open();
			md2.getModel().once('change:open', function() {
				mdlDialog2.className = mdlDialog2.className.replace(mdlClass2, '');
			})
			//Carga Templates
			$.ajax({
				type: "GET",
				async: false, 
				data:{'qry':'gettpl','prnIdEvent':eventoID},
				url: "services/templates.php",
				success: function(r)
				{
					$('#cbtpl').empty();
					values=0;
					arrDataTpl = r.data.split("#")
					arrDataTpl.forEach(function(element) {
					  $fila = element.split("|");
					  $('#cbtpl').append("<option value='"+values+"'>"+$fila[2]+"</option>");
					  values++;
					});
					$('#cbtpl').val(0);
					chgtpl(0);
				}
			});
		}
	});
	
	// Agrega funcionalidad de guardar
	var saveContainer = document.getElementById("save-panel");
	cmdm.add('save-tpl', {
		run: function(editor, sender) {
			sender.set('active', 0);
			var mdlDialog3 = document.querySelector('.gjs-mdl-dialog');
			mdlDialog3.className += ' ' + mdlClass3;
			saveContainer.style.display = 'block';
			md3.setTitle('Guardar Template');
			md3.setContent(saveContainer);
			md3.open();
			md3.getModel().once('change:open', function() {
				mdlDialog3.className = mdlDialog3.className.replace(mdlClass3, '');
			})
			//Opciones
			
			if (tpleditable==1 && tplselectedID!='')
			{
				opt_guardarnuevo.style.display = 'none';
				opt_guardar.style.display = 'block';	
				opt_guardarcomo.style.display = 'block';
				btnguardar.style.display = 'block';
				btnguardarcomo.style.display = 'block';
			}
			
			if (tplselectedID=='')
			{
				opt_guardarnuevo.style.display = 'block';
				btnguardar.style.display = 'block';
			}

		}
	});
	
	 // Simple warn notifier
	var origWarn = console.warn;
	toastr.options = {
		closeButton: true,
		preventDuplicates: true,
		showDuration: 250,
		hideDuration: 150
	};
	console.warn = function (msg) {
		toastr.warning(msg);
		origWarn(msg);
	};
	
	// Beautify tooltips
	var titles = document.querySelectorAll('*[title]');
	for (var i = 0; i < titles.length; i++) {
		var el = titles[i];
		var title = el.getAttribute('title');
		title = title ? title.trim(): '';
		if(!title) break;
		if (title=='Open Style Manager') title=='Manejador de Estilos';
		el.setAttribute('data-tooltip', title);
		el.setAttribute('title', '');
		el.setAttribute('data-tooltip-pos', "bottom");
	}

	editor.on('load', function() {
		var $ = grapesjs.$;
		var logoCont = document.querySelector('.gjs-logo-cont');
		var logoPanel = document.querySelector('.gjs-pn-commands');
		
		logoPanel.appendChild(logoCont);
		$('#gjs').append($('.ad-cont'));
		$("#gjs-am-title").css("display","none"); 
	});
	
});


	


	