$(document).ready(function($){
	function removeAlert(){
		window.setTimeout(function(){
			$('.alert').fadeTo(500,0).slideUp(500,function(){
				$this.remove();
			});
		},5000);
	}
		$('.form-control').removeAttr("disabled");
		$('#extract').removeAttr("disabled");

//affichage bootbox

// do something in the background

	$("#extract").click(function(){
		
		
		function nbre_jours(dateDebut,dateFin)
		{
			var dateD = new Date(dateDebut);
			var dateF = new Date(dateFin);

		          // différence des heures
		    var diff_temps = dateF.getTime() - dateD.getTime();
		          // différence de jours
		    var diff_jours = diff_temps / (1000 * 3600 * 24);
		         // afficher la différence
		        return(diff_jours);
		}
		var accord;
		

		var dateDebut = $("#date-debut").val();
		var dateFin = $("#date-fin").val();
		/*	function loader(){
		$('p').append('<div class="loader" style="text-align: center;"></div>');
	}*/

		if( ((dateDebut == "") || (dateFin == "")) ||((dateDebut == "") && (dateFin == "" ) ))
			{
				accord = false;
				//alert(" Il faut remplir les dates");
				
				/*$('span').append('<div class="alert alert-info" role="alert">Il faut remplir les dates</div>');
				$('span').delay(3000).hide("slow");*/
				//removeAlert()
				bootbox.alert('Il faut remplir les dates');

			}
		
		if( dateDebut != "" && dateFin != "")
		{
			if(dateDebut <= dateFin)
			{
				var DD = nbre_jours(dateDebut,dateFin);
				if(DD <= 15) // condtion de minimun de jour 
				{
					accord=true;
					//alert("tout est bon");
					//$('span').append('<div class="alert alert-success" role="alert">Ok (Veuillez patientez)</div>');
					//$('span').delay(2000).hide("slow");
					//removeAlert()
					var dialog = bootbox.dialog({
   					 message: '<p class="text-center mb-0"><i class="fa fa-spin fa-cog"></i> Ok, Veuillez patientez...</p>',
    				closeButton: true
					});
					dialog.modal('hide');

				}
				else
				{
					accord = false;
					//alert("Veuillez réessayer la limite est de 15 jours");
					//$('span').append('<div class="alert alert-info" role="alert">Veuillez réessayer la limite est de 15 jours</div>');
					//$('span').delay(2000).hide("slow");
					//removeAlert()
					bootbox.alert('Veuillez réessayer la limite est de 15 jours');
				}

			}
			else
			{
				accord = false;
				//$('span').append('<div class="alert alert-danger" role="alert">les dates que vous avez entrer ne sont pas valides</div>');
				//$('span').delay(2000).hide("slow");
				//removeAlert()
				bootbox.alert('Les dates que vous avez entrer ne sont pas valides');
			}

		}
			if(accord){

				//alert("eto mande le ajax");
				$.ajax({
					type :"POST",
					url:"BO/back.php",
					data: {dateDebut_x : dateDebut , dateFin_x : dateFin},
					success : function(result){
						if (result !="") 
						{
							$('.loader').addClass('d-none');

							window.open(result, '_blank');
							bootbox.alert({
						    message: "L'extraction est terminé",
						    className: 'rubberBand animated'
						});
							$('.form-control').removeAttr("disabled");
							$('#extract').removeAttr("disabled");

							//mverina azo kitiana doly ny  btn sy ny input  ***************************************************
						}else{
							//mverina azo kitiana doly ny  btn sy ny input  ***************************************************
							//alert("inona le erreur");
							$('form-control').removeAttr("disabled");
							$('#extract').removeAttr("disabled");
						}
					
					},
					error: function(error){
						$('.loader').addClass('d-none')
					},
					load: function(){
						$('.loader').removeClass('d-none')

					},
					beforeSend: function(){
					
					$('.loader').removeClass('d-none')	
					//atao tsy afaka kitiana ny btn sy ny input	
					$('.form-control').attr("disabled","disabled");
					$('#extract').attr("disabled","disabled");			
							
				} 

			});

		}



	});

});

