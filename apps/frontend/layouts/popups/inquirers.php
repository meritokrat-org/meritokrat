<style type="text/css">
	#popup_content {
		position: absolute;
		color: rgb(0, 0, 0);
		width: 500px;
		height: 455px;
		top: 280px;
		left: 45%;
		margin-left: -200px;
		background-color: white;
		border: 10px solid #660000;
		text-align: center;
		z-index: 10001;
		font-family: Arial, Sans-serif, Serif;
		-moz-border-radius: 10px 10px 10px 10px;
		display:block;
	}
	
	#target_loading {
	}
	
	#popup_content_target {
		height: 350px;
	}
	
	#inquirer-question {
		text-align: left;
		padding: 10px;
		border: 1px solid #660000;
	}
</style>

<a id="close_popup" class="hide" href="javascript:;">Close</a>

<div id="popup_content" class="hide" style="" >
	<div class="p10" style="background: #EEE">
		<div class="left aleft" id="inquirer-title" style="width: 400px;">
			<span style="color: #660000; font-size: 14px; font-weight: bold">&nbsp;</span>
			<span style="color: #888; font-size: 14px;">&nbsp;</span>
		</div>
		<div class="left aright" style="width: 80px">
			<!--<a id="close_popup" href="javascript:;">Close</a>-->
			<a id="b_skip" href="javascript:;" style="font-size: 10px; color: #888;"><?=t('Пропустить')?></a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="m10" id="popup_content_target">
		<div id="target_loading">
			<img src="/static/images/common/loaging.gif" class="acenter"/>
		</div>
		<div id="inquirer-question" class="hide">
		</div>
		<div id="inquirer-answers" class="hide aleft p10">
		</div>
	</div>
	<div class="p10" style="background: #EEE">
		<div class="left aleft" id="inquirer-title-steps" style="width: 164px">
			&nbsp;
		</div>
		<div class="left aright" style="width: 316px">
			<input type="button" id="b_left" value=" <?=t('Назад')?> " class="button button_gray hide" disabled />
			<input type="button" id="b_right" value=" <?=t('Далее')?> " class="button button_gray" disabled />
			<input type="button" id="b_done" value=" <?=t('Готово')?> " class="button button_gray hide" disabled />
		</div>
		<div class="clear">
		</div>
	</div>
</div>

<script type="text/javascript">
	
	$(document).ready(function(){
		
		var inquirer_id = <?=$inquirer_popup?>;
		
		var name = '';
		var frame = 0;
		
		var questions = new Array();
		var answers = new Array();
		
		$.post("/inquirer/edit",
			{
				"act": "get",
				"id": inquirer_id
			},
			function(data){
				if(data.status == 0){
					name = data.name;
					questions = data.questions;
					$.each(questions, function(){
						answers.push(new Array());
						$.each(this.variants, function(){
							answers
						})
					})
					$("#target_loading").hide();
					set_inquirer_title();
					show_frame(frame);
				} else {
					$('#close_popup').click();
				}
			},
			"json"
		);
			
		var show_frame = function(frame){
			if(frame > 0)
				$("#b_left").attr("disabled", false).attr("class", "button");
			else
				$("#b_left").attr("disabled", true).attr("class", "button button_gray hide");
			if(frame < questions.length-1){
				$("#b_right").attr("disabled", false).attr("class", "button");
				$("#b_done").attr("disabled", true).attr("class", "button button_gray hide");
			} else {
				$("#b_right").attr("disabled", true).attr("class", "button button_gray hide");
				$("#b_done").attr("disabled", false).attr("class", "button");
			}
			$("#inquirer-question").show();
			$("#inquirer-question").html(questions[frame].question);
			$("#inquirer-answers").show();
			$("#inquirer-answers").html("");
			var pl = 0;
			if(questions[frame].myanswer == 1)
				pl = 1;
			for(var i = 0; i < questions[frame].variants.length+pl; i++){
				var caption = "";
				if( ! questions[frame].variants[i]){
					caption = $("<span />").append(
											$("<span />").html(questions[frame].myanswerprefix)
												.css("padding-right", "8px")
										).append(
											$("<input />").attr("type", "text")
												.attr("id", "answer-"+i+"-text")
										);
				} else {
					caption = $("<label />").attr("for", "answer-"+i)
											.html(questions[frame].variants[i]);
				}
				$("#inquirer-answers").append(
					$("<div />").append(
						$("<input />").attr("type", (questions[frame].manyvariants == 1 ? "checkbox" : "radio"))
							.attr("id", "answer-"+i)
							.click(function(){
								$.each($("#inquirer-answers input"), function(){
									if($(this).attr("type") == "radio")
										$(this).attr("checked", false);
								});
								if($(this).attr("type") == "radio")
									$(this).attr("checked", true);
							})
					).append(caption)
				);
			}
			$.each(answers[frame], function(index, value){
				if((typeof this.value === 'boolean'))
					$("#"+this.name).attr("checked", true);
				else {
					$("#"+this.name).attr("checked", true);
					$("#"+this.name+"-text").val(this.value);
				}
			});
			set_inquirer_title();
		}
		
		var set_inquirer_title = function(){
			$("#inquirer-title").html("");
			$("#inquirer-title-steps").html("");
			$("#inquirer-title").append(
				$("<span />").css("color", "#660000")
					.css("font-size", "14px")
					.css("font-weight", "bold")
					.html(name)
			);
			$("#inquirer-title-steps").append(
				$("<span />").css("color", "#888")
					.css("font-size", "14px")
					.html((frame+1)+"<?=t('-й ворос из')?> "+questions.length)
			);
		}
		
		$("#b_left").click(function(){
			if(validate_answers()){
				frame--;
				show_frame(frame);
			} else {
				alert("<?=t("Выберите свой вариант ответа")?>");
			}
		});
		
		$("#b_right").click(function(){
			if(validate_answers()){
				frame++;
				show_frame(frame);
			} else {
				alert("<?=t("Выберите свой вариант ответа")?>");
			}
		});
		
		$("#b_done").click(function(){
			if(validate_answers()){
				$.post("/inquirer/edit",
					{
						"act": "reply",
						"id": inquirer_id,
						"answers": answers
					},
					function(data){
						$("#close_popup").click();
					},
					"json"
				);
			} else {
				alert("Select answer");
			}
		});
		
		$("#b_skip").click(function(){
			$("#close_popup").click();
		});
		
		var validate_answers = function(){
			answers[frame] = new Array();
			$.each($("#inquirer-answers input"), function(){
				if(($(this).attr("type") == "radio" || $(this).attr("type") == "checkbox") && $(this).attr("checked")){
					var val = questions[frame].variants[$(this).attr("id").split("-")[1]];
					if(typeof val == "undefined")
						val = questions[frame].myanswerprefix+" "+$("#"+$(this).attr("id")+"-text").val();
					answers[frame].push({
						"name": $(this).attr("id"),
						"value": val
					});
				}
			});
			if(answers[frame].length > 0)
				return true;
			else
				return false;
		}
		
	});
	
</script>