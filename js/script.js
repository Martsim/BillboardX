$( document ).ready(function() {
	$('.fb-no-jump').prepend('<div class ="fb-login-button" data-scope = "public_profile,email" onlogin="checkLoginState();"></div>');

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
			url: "controller/postita.php",
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
			url: "controller/lae_kategooria.php",
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
    		$("#stats").load('../view/statistika.php');
    
   	});
	$('#stats_nupp').click(function(){
    		$('#stats').slideToggle();
    		$('body, html').animate({scrollTop:$('footer').offset().top},500)
	});
	
	$('#postitakommentaar').click(function(event){
		var button = $(this);
    	var nupuid = $(this).attr('data-postid');
		var sisu =  $(this).prev().children("textarea").val();

		$.post( "controller/kommenteeri.php", { id: nupuid, kom_sisu: sisu }, function( data ) {
			alert( data );
			button.parent().prev().children("#modal-kommentaarid").append("<p class =modal-kommentaarid2>"+"Mina : " +sisu+"</p>");
		});

	});
	
	
	$('#viewModal').on('show.bs.modal', function (event) {

		var button = $(event.relatedTarget); // Button that triggered the modal
		
		var infoinModal = button.data('postituseid'); // Extract info from data-* attributes
		var infoinModal2 = button.data('kategid');
		var modal = $(this)
		$sisud = $sisudM[infoinModal2];	
		
		$.each($sisud, function(k, v) {
	
	
			var p_id = v['id'];
			
			if(p_id == infoinModal){
				var pealki = v['pealkiri'];
				var sisus = v['sisu'];
				var autor = v['autor'];

				modal.find('.modal-title').text(pealki);
				modal.find('.modal-sisu').text(sisus);
				modal.find('#postitakommentaar').attr('data-postid', p_id);
			}
			
			
		});
	
		$.post( "controller/lae_kommentaar.php", { id: infoinModal}, function( data ) {
			console.log(data);
			var seediv = modal.find('#modal-kommentaarid');
			if (data.length > 2){
				
				seediv.html("");
				$.each(JSON.parse(data), function(k, v) {
					var autor = v['autor'];
					seediv.append("<p class =modal-kommentaarid2>"+v['autor']+" : " +v['sisu']+"</p>");
						
				});
			} else {
				seediv.html("<p class='modal-kommentaarid2'>Kommentaare pole</p>");
			}
		});
	});
});
