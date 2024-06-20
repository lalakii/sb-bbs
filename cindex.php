<!doctype html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, minimal-ui">
		<title></title>
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+SC:400">
		<link href="https://cdn.bootcss.com/normalize/8.0.1/normalize.css" rel="stylesheet">
		<link href="https://cdn.bootcss.com/milligram/1.3.0/milligram.min.css" rel="stylesheet">
		<style>
			html,body{
				background:#000;
				font-family:"Noto Sans SC";
			}

			.row{
				word-wrap:break-word;
			}

			#rebox{
				display:none;
			}

			[v-cloak]{
				display: none;
			}
		</style>
	</head>
	<body>
		<div id="app" v-cloak class="container">
			<div class="row">
				<div class="column">
					<h3>
						SB-BBS,The Best BBS.
					</h3>
				</div>
			</div>
			<div class="row">
				<div id="isnotlogin" class="column column-35">
					<h4 style="color:#528B8B">
						主题详情
					</h4>
				</div>
			</div>
			<div v-for="(value,key) in data" v-if="key ==  0" class="row">
				<div class="column">
					<div class="column" style="color:yellow">
						<span>
							标题:
						</span>
						<span>
							{{value.ctitle}}
						</span>
					</div>
					<div class="column" style="color:red">
						<span>
							作者:
						</span>
						<span>
							<a :href="'./userinfo.php?uname='+value.uname">{{value.uname}}</a>[点击用户名查看作者信息]
						</span>
					</div>
					<div class="column" style="color:blue">
						<span>
							时间:
						</span>
						<span>
							{{value.cdate}}
						</span>
					</div>
					<div class="column" style="color:green">
						<span>
							内容:
						</span>
						<span>
							{{value.ctext}}
						</span>
					</div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div id="isnotlogin" class="column column-35">
					<h5 style="color:#528B8B">
						全部评论
					</h5>
				</div>
			</div>
			<div class="row">
				<div class="column">
					<div v-for="(value,key) in data" v-if="key > 0" class="column">
						<span>
							<span style="color:yellow" v-on:click="re_user(value.uname)">
								{{value.uname}}
							</span>
							:
							<span style="color:green">
								<span v-if="check_image(value.ctext)"><br /><img :src="value.ctext.trim()" alt="img"><br /></span>
								<span v-else>{{value.ctext}}</span>
							</span>
							<span style="color:brown">
								{{value.cdate}}
							</span>
						</span>
					</div>
				</div>
			</div>
			<hr />
			<div class="row">
				<div id="rebox" class="column">
					<input id="re" type="text" maxlength="138" placeholder="请尽量让你的回复言简意赅" style="color:green" />
					<input id="re-btn" type="button" onclick="re_msg()" value="回复" style="width:25px;padding-left:8px;background-color:#388E8E;border:0">
				</div>
			</div>
			<div class="row">
				<div class="column">
					<a href="../">
						返回主页
					</a>
				</div>
			</div>
		</div>
		<script src="https://cdn.bootcss.com/vue/2.6.4/vue.min.js" type="text/javascript">
		</script>
		<script src="https://cdn.bootcss.com/axios/0.19.0-beta.1/axios.min.js"
		type="text/javascript">
		</script>
		<script src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js">
		</script>
		<script src="https://cdn.bootcss.com/jquery-cookie/1.4.1/jquery.cookie.min.js">
		</script>
		<script>
			new Vue({
				el: "#app",
				data() {
					return {
						data: []
					}
				},
				mounted() {
					axios.get("./function.php?loadall=<?php echo !empty($_GET["cid"])?(int)$_GET["cid"]:"1"; ?>").then(response =>(this.data = response.data)).
					catch(error =>console.log(error));
				}
			});

			function re_msg() {
				var re = $("#re").val();
				var rtag = <?php echo !empty($_GET["cid"])?(int)$_GET["cid"]:"1"; ?>;
				var uname = $.cookie('sbbbsname');
				if (!$.isEmptyObject(re)) {
					var re_btn = $("#re-btn");
					re_btn.attr("disabled",true);
					$.get("./function.php?send={\"ctext\":\"" + re + "\",\"rtag\":\"" + rtag + "\",\"uname\":\"" + uname + "\"}",
					function(data) {
						if (data == "1") {
							alert("回复成功!");
							location.reload();
						} else {
							re_btn.attr("disabled",false);
							alert("回复失败!");
						}
					});
				}else{
					alert("请输入内容。");
				}
			}

			function check_image(url){
				if(url.trim().substring(0,4).indexOf('http')!=-1){
					return true;
				}
				return false;
			}

			function re_user(uname){
				var re=$("#re");
				if(re.val().indexOf(uname)==-1){
					re.val(re.val()+"@"+uname+" ");
				}
			}

			if($.cookie('sbbbs')=='islogin' && !$.isEmptyObject($.cookie('sbbbsname'))){
				$("#rebox").show();
			}
		</script>
	</body>

</html>
