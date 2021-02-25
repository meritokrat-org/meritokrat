<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
<script src="/static/javascript/jquery/facedetection/ccv.js"></script>
<script src="/static/javascript/jquery/facedetection/face.js"></script>
<script src="/static/javascript/jquery/jquery.facedetection.js"></script>

<script>
	var jquery_1_11_1 = jQuery;

	(function(){
		var $ = jQuery = jquery_1_11_1 ;

		$(function () {
			$('#try').click(function () {
				$('#photo').faceDetection({
					complete: function (faces) {
						console.log(faces);
					}});
			});
		});
	})();
</script>

<style>
	.face {
		border: 2px solid #FFF;
	}

	a:link, a:visited, a:active {
		color:#33AACC;
		text-decoration:none;
	}
	img {
		border:1px solid #FFF;
	}
	h1, h2 {
		font: Arial;
		letter-spacing:-3px;
		margin:0;
		padding:0;
	}
	h2 {
		font: Arial;
		letter-spacing:0px;
		margin-bottom:5px;
	}
	ul {
		margin:10px 0;
	}
	ul, li {
		list-style:none;
		margin:0;
		padding:0;
	}
	pre {
		background:#333;
		margin:10px 0;
		padding:20px;
	}
	#container {
		margin:5px auto;
		text-align:center;
	}
	#try {
		display:block;
		font: Arial;
		margin:10px;
		outline:none;
	}
	#footer {
		background:#333;
		margin:10px 0;
		padding:20px;
	}

</style>

<div id="container">
	<div id="header">
		<h1>Распознаём лица вместе с jQuery</h1>
	</div>

	<div id="content">
		<pre>var coords = $('#myPicture').faceDetection();</pre>
		<a href="#" id="try">ЖМИ!</a>
		<img id="photo" src="https://pp.vk.me/c622030/v622030083/2a82e/g7kkY8Qqml0.jpg" />
	</div>
</div>