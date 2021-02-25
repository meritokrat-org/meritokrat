<style type="text/css">
	.question-list {
		border-left: 1px solid #AAA;
		border-right: 1px solid #AAA;
		border-top: 1px solid #AAA;
		background: #FFF;
		width: 164px;
		height: 256px;
		overflow: auto;
	}
	
	.question-list-nav {
		width: 164px;
		height: 24px;
		border-left: 1px solid #AAA;
		border-right: 1px solid #AAA;
		border-bottom: 1px solid #AAA;
	}
	
	.input-button {
		border-top: 1px solid #AAA;
		border-right: 1px solid #AAA;
		width: 23px;
		height: 23px;
		background: #eee;
		text-align: center;
		float: left;
		font-weight: bold;
	}
	
	.shadow {
		-webkit-box-shadow: 1px 1px 0px #fff;  
    -moz-box-shadow: 1px 1px 0px #fff;  
    box-shadow:  1px 1px 0px #fff;
	}
	
	.shadow-text {
		text-shadow: 0px 1px 0px #fff;
	}
	
	.input-text {
		-moz-border-radius: 4px;
		border-radius: 4px;
		padding: 4px;
		text-shadow: 0px 1px 0px #fff;
		outline: none;
		background: -webkit-gradient(linear, left top, left bottombottom, from(#e4e4e4), to(#fff));  
    background: -moz-linear-gradient(top,  #e4e4e4,  #fff);
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#E4E4E4E4, endColorstr=#FFFFFFFF);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#E4E4E4E4, endColorstr=#FFFFFFFF)";
		border: 1px solid #aaa;
	}
	
	.input-text:focus {  
		-webkit-box-shadow: 0px 0px 5px #007eff;  
		-moz-box-shadow: 0px 0px 5px #007eff;  
		box-shadow: 0px 0px 5px #007eff;  
	} 
	
	.answer {
		height: 27px;
	}
	
	.answer-nav {
		height: 25px;
		padding-top: 1px;
		border-bottom: 1px solid #AAA;
	}
	
	.gradient {
		background: -moz-linear-gradient(top, #ccc, #fff);
		background: -webkit-gradient(linear, left top, left bottom, from(#ccc), to(#fff));
		filter: progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCCCCCCC, endColorstr=#FFFFFFFF);
		-ms-filter: "progid:DXImageTransform.Microsoft.gradient(startColorstr=#CCCCCCCC, endColorstr=#FFFFFFFF)";
	}
	
	.spliter {
		height: 8px;
	}
	
	#question-list div {
		padding: 4px;
	}
	
	#message-requester {
		background: #CFC;
		border: 1px solid #8F8;
		padding: 4px;
		margin-bottom: 8px;
	}
</style>

<div class="column_head">
	Новый опрос
</div>

<div class="box_content p10" style="background: #eee; height: 480px">
	
	<div class="hide shadow-text" id="message-requester">
		message-requesters
	</div>
	
	<div>
		<div class="shadow-text" style="width: 164px; height: 24px; float: left; padding-top: 4px">
			Наименование опроса:
		</div>
		<div>
			<input type="text" class="input-text shadow shadow-text" id="name" style="width: 512px" value="Untitled" />
		</div>
	</div>
	
	<div class="clear"></div>
	
	<div>
		<div class="left">
			<div class="shadow-text" style="height: 24px">Вопросы:</div>
			<div class="question-list shadow" id="question-list">
			</div>
			<div class="question-list-nav gradient shadow">
				<div class="input-button shadow-text" id="question-add">+</div>
				<div class="input-button shadow-text" id="question-remove">-</div>
			</div>
		</div>
		<div class="left">
			<div style="height: 24px">&nbsp;</div>
			
			<div class="left shadow-text" style="width: 100px; text-align: right; margin-right: 8px">Вопрос:</div>
			<div class="left">
				<textarea class="input-text shadow shadow-text" id="question-editor" style="font-family: inherit; font-size: 12px"></textarea>
			</div>
			<div class="clear"></div>
			
			<div class="left shadow-text" style="width: 100px; text-align: right; padding-top: 12px; margin-right: 8px">Варианты:</div>
			<div class="left" id="variants">
			</div>
			<div class="clear"></div>
			
			<div style="height: 24px">&nbsp;</div>
			
			<div class="left" style="width: 100px; text-align: right; padding-top: 12px; margin-right: 8px"></div>
			<div class="left shadow-text">
				<input type="checkbox" id="many-variants" />
				<label for="many-variants">Несколько вариантов ответа</label><br />
				<span style="font-size: 10px; color: #888; padding-left: 26px">
					Пользователь может выбирать несколько вариантов ответа
				</span>
			</div>
			<div class="clear"></div>
			
			<div style="height: 24px">&nbsp;</div>
			
			<div class="left" style="width: 100px; text-align: right; padding-top: 12px; margin-right: 8px"></div>
			<div class="left shadow-text">
				<input type="checkbox" id="my-answer" />
				<label for="my-answer">Свой вариант</label><br />
				<span style="font-size: 10px; color: #888; padding-left: 26px">
					Приставка: <input type="text" id="my-answer-prefix" />
				</span><br />
				<span style="font-size: 10px; color: #888; padding-left: 26px">
					Пользователь может вписать свой вариант ответа
				</span>
			</div>
			<div class="clear"></div>
		</div>
		<div class="clear"></div>
	</div>

</div>

<div class="box_content p10" style="background: #eee; text-align: right">
	<input type="button" class="button" id="submit" value=" Зберегти " />
	<input type="button" class="button_gray" id="cancel" value=" Відміна "  />
</div>

<script type="text/javascript">
	$(document).ready(function(){
		
		var id = <?=$inquirer_id?>;
		
		if(id > 0){
			$.post("/inquirer/edit",
				{
					"act" : "get",
					"id" : id
				},
				function(data){
					switch(data.status){
						case 0:
							$("#name").val(data.name);
							questions = data.questions;
							showQuestions();
							break;
					}
				},
				"json"
			);
		}
		
		var variants = new Array();
		variants.push("");
		variants.push("");
		
		var qIndex = 0;
		var questions = new Array();
		questions.push({
			'question'			: 'Empty',
			'variants'			: ['', '', ''],
			'manyvariants'	: 0,
			'myanswer'			: 0,
			'myanswerprefix': ''
		});
		
		var setQuestionActive = function(){
			qIndex = ! this.window ? $(this).attr("id").split("-")[1] : 0;
			$.each($("#question-list div"), function(index, div){
				$(div).css("background", "#fff");
				if(index == qIndex)
					$(div).css("background", "#eee");
			});
			$("#question-editor").val(questions[qIndex].question);
			showVariants(questions[qIndex].variants);
			
			var flag = questions[qIndex].manyvariants == 1 ? true : false;
			$("#many-variants").attr('checked', flag);
			
			flag = questions[qIndex].myanswer == 1 ? true : false;
			$("#my-answer").attr('checked', flag);
			
			$("#my-answer-prefix").val(questions[qIndex].myanswerprefix);
		}
		
		var showQuestions = function(){
			$("#question-list").html("");
			$.each(questions, function(index, value){
				var html = value.question == "" ? "&nbsp;" : value.question;
				if(html.length > 16){
					html = html.substr(0, 16) + "...";
				}
				$("#question-list").append(
					$("<div />").attr("id", "question-"+index)
						.bind("click", setQuestionActive)
						.html(html)
				);
			})
			setQuestionActive();
		}
		
		var showVariants = function(array){
			$("#variants").html("");
			$.each(array, function(index, value){
				$("#variants").append(
					$("<div />").attr("class", "spliter")
				);
				$("#variants").append(
					$("<div />").attr("class", "answer shadow")
						.attr("id", "variant-box-"+index.toString())
						.append(
							$("<div />").attr("class", "left")
								.append(
									$("<input />").attr("type", "text")
										.attr("class", "input-text shadow shadow-text")
										.css("width", "334px")
										.val(value)
										.bind("change", function(){
											var key = $(this).parent().parent().attr("id").split("-")[2];
											questions[qIndex].variants[key] = $(this).val();
										})
								)
						)
						.append(
							$("<div />").attr("class", "left answer-nav")
								.append(
									$("<div />").attr("class", "input-button shadow-text")
										.attr("id", "variant-add")
										.bind("click", addVariant)
										.html("+")
								)
								.append(
									$("<div />").attr("class", "input-button shadow-text")
										.attr("id", "variant-remove")
										.bind("click", removeVariant)
										.html("-")
								)
						)
				);
			})
		}
		
		var addVariant = function(){
			var key = $(this).parent().parent().attr("id").split("-")[2];
			key++;
			questions[qIndex].variants.push("");
			for(var i = questions[qIndex].variants.length; i > 0; i--){
				if(key <= i && i+1 < questions[qIndex].variants.length){
					questions[qIndex].variants[i+1] = questions[qIndex].variants[i];
				}
			}
			questions[qIndex].variants[key] = "";
			showVariants(questions[qIndex].variants);
		}
		
		var removeVariant = function(){
			if(questions[qIndex].variants.length > 1){
				var key = $(this).parent().parent().attr("id").split("-")[2];
				questions[qIndex].variants.splice(key, 1);
				showVariants(questions[qIndex].variants);
			}
		}
		
		$("#question-add").click(function(){
			questions.push({
				'question'			: 'Empty',
				'variants'			: ['', '', ''],
				'manyvariants'	: 0,
				'myanswer'			: 0,
				'myanswerprefix': ''
			});
			showQuestions();
		});
		
		$("#question-remove").click(function(){
			if(questions.length > 1){
				var key = qIndex;
				questions.splice(key, 1);
				showQuestions();
			}
		});
		
		$("#question-editor").change(function(){
			questions[qIndex].question = $(this).val();
			var html = questions[qIndex].question == "" ? "&nbsp;" : questions[qIndex].question;
			if(html.length > 16){
				html = html.substr(0, 16) + "...";
			}
			$("#question-list div:eq("+qIndex+")").html(html);
		});
		
		$("#many-variants").click(function(){
			questions[qIndex].manyvariants = $(this).attr("checked") ? 1 : 0;
		});
		
		$("#my-answer").click(function(){
			questions[qIndex].myanswer = $(this).attr("checked") ? 1 : 0;
		});
		
		$("#submit").click(function(){
			$.post("/inquirer/edit", 
				{
					"act" : "save",
					"id" : id,
					"name" : $("#name").val(),
					"questions" : questions
				},
				function(data){
					switch(data.status){
						case 1:
							break;
							
						default:
							id = data.id;
							$("#message-requester").html("Данные успешно сохранены");
							$("#message-requester").show();
							window.location = "/inquirer";
							break;
					}
				},
				"json"
			);
		});
		
		$("#cancel").click(function(){
			window.location = "/inquirer";
		});
		
		$("#my-answer-prefix").bind("change", function(){
			questions[qIndex].myanswerprefix = $(this).val();
		});
		
		showQuestions();
		
	});
</script>
