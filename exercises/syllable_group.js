(function ($) {
	
	function CheckResult(word_id)
	{
		// ключ, с которым сравнивается тип выбранного слова
		var u = $("input#cue").val();
	
		if (typeof u != 'undefined') {
			// скрипт проверки результата одинаковый для нескольких упражнений!
			var server_url = "./syllable_choose.php";
			
			// запрос результата
			$.ajax({
				url: server_url,
				type: "POST",
				data : {"action" : "check", "word" : word_id, "pattern" : u},
				dataType: "json",
				success: function(data) {				

					console.log(data);
				
					$("#answer-feedback").html(data["cheers"])
						.show(100);
					
					if (data["success"]) {
						$("#answer-feedback").removeClass("ui-state-error")
											.addClass("ui-state-highlight");
					}
					else {
						$("#answer-feedback").removeClass("ui-state-highlight")
											.addClass("ui-state-error");
					}
				
				}
			});
		}		
		else {
			alert('Make your choice!');
		}	
	}
	
	function GetContent() {
	
		$("#exercise-content").empty();
		$("#exercise-task").empty();
		$("#answer-feedback").hide(0);
		
		var server_url = "./syllable_odd.php";
		
		$.ajax({
			url: server_url,
			type: "POST",
			dataType: "json",
			success: function(data) {
			
				console.log(data);			

				$.each( data, function( key, val ) {

					if (key == "task")
						$("#exercise-task").html(val);
					else if (key == "cue")
						$("#exercise-content").append("<input type=\"hidden\" id=\"cue\" value=\""+val+"\"/>");
					else {
						$("#exercise-content").append("<p id=\""+key+"\">"+val+"</p>")
						$("#"+key).draggable({ containment : "#exercise-body", revert: true })
							.addClass("draggable_word");				
					}
				});
				
				$("#exercise-content").append("<div id=\"trashbin\"><img src=\"./img/bin.gif\"></div>");
				$( "#trashbin" ).droppable({
					accept : ".draggable_word",
					drop : function(event, ui) {
						var drag = ui.draggable;
						//ui.draggable( "option", "disabled", true );
						drag.hide();
						//drag.appendTo(this);
						CheckResult(drag.attr("id"))						;
					}
				});				
			}
		});
	}
	
    var parent = $("#exercise-body");
	if (parent) {
		parent.empty();	
		
		parent.append("<p id=\"exercise-task\"></p>");		
		parent.append("<div id=\"answer-feedback\"></div>");
		parent.append("<div id=\"exercise-content\"></div>");		
		
		parent.append("<button id=\"btn_check\">Check</button>");
		parent.append("<button id=\"btn_procceed\">Next</button>");
		parent.append("<button id=\"btn_showrule\">Help</button>");

		GetContent();

		$( "#btn_check" ).button()
			.click( CheckResult );
		
		$( "#btn_procceed" ).button()
			.click( GetContent );
		
		$( "#btn_showrule" ).button()
		.click(function() {
			alert('Rule!');
		});					
	}

})(jQuery);