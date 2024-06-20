<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
		<title></title>
		<link href="https://cdn.bootcss.com/milligram/1.3.0/milligram.min.css" rel="stylesheet">
		<style>
			html,body{
				background:#000;
			}
		</style>
	</head>
	<body>
		<?php if(empty($_COOKIE["sbbbs"])||$_COOKIE["sbbbs"]=="null"){echo "<script>alert('查看用户信息请先登录!');window.history.go(-1);</script>";echo "</body></html>";exit;} ?>
		<div id="app" class="container">
			<div class="row">
				<div class="column">
					<h3>
						SB-BBS,The Best BBS.
					</h3>
				</div>
			</div>
			<div class="row">
				<div class="column column-35">
					<h4 style="color:#528B8B">
						用户信息
					</h4>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="column">
					<span id="uname" style="color:lightgreen"><font style="color:red">用户名：</font><?php parse_str($_SERVER['QUERY_STRING'], $uname);echo $uname["uname"]; ?></span>
				</div>
			</div>
			<div class="row">
				<div class="column">
					<span id="udepict" style="color:lightblue"><font style="color:red">个人简介：</font></span>
				</div>
			</div>
			<hr />
			<div class="row">
				<div class="column">
					<a href="../">
						返回主页
					</a>
				</div>
			</div>
		</div>
		<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"
		type="text/javascript">
		</script>
		<script>
			window.onload = function(){
				axios.get("./function.php?loadinfo=<?php echo !empty($_GET["uname"])?$_GET["uname"]:""; ?>").then(function(response){
					document.querySelector("#udepict").innerHTML+=response.data[0].udepict;
				}).catch(error =>console.log(error));
			}
		</script>
	</body>

</html>
