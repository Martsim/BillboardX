$( document ).ready(function() {
	$('#postita_nupp').on('click', function(event){
		document.getElementById('pop_up').style.display='block';
		document.getElementById('tume_taust').style.display='block';
		event.preventDefault();
	});
	$('#sule_aken').on('click', function(){
		document.getElementById('pop_up').style.display='none';
		document.getElementById('tume_taust').style.display='none';
	});

	$("#pop_up").submit(function(event){
		var values = $(this).serialize();
		$.ajax({
			url: "postita.php",
			type: "post",
			data: values ,
			async: false,

			success: function (vastus) {
				// you will get response from your php page (what you echo or print)
				alert(vastus);
				event.preventDefault();
			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}

		});
		//Peale postitamist, sulen akna:
		document.getElementById('pop_up').style.display='none';
		document.getElementById('tume_taust').style.display='none';

		//jätan meelde id, mille vana sisu kustutan ning kuhu uue sisu lisan
		var kategooria_id = $('select[name="kat_id"]').val();

		//Tühjendan vormi väljad:
		$('#pop_up')[0].reset();

		$.ajax({
			url: "lae_kategooria.php",
			type: "post",
			data: {id: kategooria_id} ,
			async: false,

			success: function (vastus) {
				var $json= JSON.parse(vastus);
				console.log($json);
				//tühjenda konteiner
				var $konteiner = $("#"+kategooria_id);

				$konteiner.empty();

				$.each($json, function(k, v) {
					var autor = v['autor'];

					$konteiner.append('<div class="sisu"></div>');
					var $sisu = $konteiner.children().last();

					$sisu.append("<p class =sisu_pealkiri>"+v['pealkiri']+"</p>");
					$sisu.append("<p class =sisu_autor>"+autor+"</p>");
					$sisu.append("<p class =sisu_tekst>"+v['sisu']+"</p>");
					if(autor == $("#kasutaja").text()){
						$sisu.append("<a class='kustuta_nupp' href='kustuta.php?id="+v['id']+"'>Kustuta postitus</a>");
					}
				});

			},
			error: function(jqXHR, textStatus, errorThrown) {
				console.log(textStatus, errorThrown);
			}
		});
	});


	$('#sisu_lisamine').keyup(function(){
		$("#counter").text($(this).val().length);
	});

	$('.kustuta_nupp').on('click', function(e){
		if(!confirm("Tahad oma postitust kustutada?")){
			e.preventDefault();
		}
	});
    	$(window).load(function(){
    		console.log("Leht laetud, laen nüüd statistika");
    		$("#stats").load('/statistika.php');
    
   	});
	$('#stats_nupp').click(function(){
    		$('#stats').slideToggle();
    		$('body, html').animate({scrollTop:$('footer').offset().top},500)
	});
});