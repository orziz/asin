<template>
	<div id="joinOrgan">
		<ul>
			<li><span class="tdTile">栏目</span><span class="tdTile">值</span><span class="tdTile">备注</span></li>
			<li>
				<label for="qq" class="must">QQ：</label>
				<input type="number" id="qq" ref='qq' value="">
				必填/用户QQ号，用作账号绑定
			</li>
			<li>
				<label for="nickname" class="must">姓名：</label>
				<input type="text" id="nickname" ref="nickname" value="">
				必填/显示在刺客组织里的名称
			</li>
			<li>
				<label for="sex">性别：</label>
				<input type="number" id="sex" ref="sex" value="">
				默认为0
			</li>
			<li>
				<label for="age">年龄：</label>
				<input type="number" id="age" ref="age" value="">
				默认为0
			</li>
			<li>
				<label for="height">身高：</label>
				<input type="number" id="height" ref="height" value="">
				默认为0，单位为cm
			</li>
			<li>
				<label for="weight">体重：</label>
				<input type="number" id="weight" ref="weight" value="">
				默认为0，单位为kg
			</li>
			<li>
				<label for="free">自由属性点：</label>
				<input type="number" id="free" ref="free" value="">
				默认为0
			</li>
			<li>
				<label for="str">力量：</label>
				<input type="number" id="free" ref="free" value="">
				默认为0
			</li>
			<li>
				<label for="dex">敏捷：</label>
				<input type="number" id="dex" ref="dex" value="">
				默认为0
			</li>
			<li>
				<label for="con">体质：</label>
				<input type="number" id="con" ref="con" value="">
				默认为0
			</li>
			<li>
				<label for="ine">智力：</label>
				<input type="number" id="ine" ref="ine" value="">
				默认为0
			</li>
			<li>
				<label for="wis">感知：</label>
				<input type="number" id="wis" ref="wis" value="">
				默认为0
			</li>
			<li>
				<label for="cha">魅力：</label>
				<input type="number" id="cha" ref="cha" value="">
				默认为0
			</li>
			<li>
				<label for="arms">武器：</label>
				<textarea id="arms" ref="arms"></textarea>
				默认为空
			</li>
			<li>
				<label for="introduce">介绍：</label>
				<textarea id="introduce" ref="introduce"></textarea>
				默认为空
			</li>
			<li>
				<label for="skill1">技能1：</label>
				<textarea id="skill1" ref="skill1"></textarea>
				默认为空
			</li>
			<li>
				<label for="skill2">技能2：</label>
				<textarea id="skill2" ref="skill2"></textarea>
				默认为空
			</li>
			<li>
				<label for="skill3">技能3：</label>
				<textarea id="skill3" ref="skill3"></textarea>
				默认为空
			</li>
			<li>
				<label for="skill4">技能4：</label>
				<textarea id="skill4" ref="skill4"></textarea></textarea>
				默认为空
			</li>
			<li>
				<label for="score">积分：</label>
				<input type="number" id="score" ref="score" value="">
				默认为0
			</li>
			<li>
				<label for="credit">暗币：</label>
				<input type="number" id="credit" ref="credit" value="">
				默认为0
			</li>
			<li>
				<label for="rank">排名：</label>
				<input type="number" id="rank" ref="rank" value="">
				默认为0
			</li>
		</ul>
		<br>
		<input type="submit" id="postForm" ref="postForm" value="提交" class="postForm">
	</div>
</template>

<script>
export default {
	name: 'JoinOrgan',
	data () {
		return {
		}
	},
	mounted: function() {
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#joinOrgan { text-align: left; }
table { width: 98%; margin: 0 auto; }
tr { border-bottom: 1px solid #dedede; }
td { text-align: left; }
.tdTile { font-weight: bold; font-size: 18px; }
.must { color: #FF0000; }
.postForm { margin: 0 0 20px 20px; padding: 5px 10px; }
label { cursor: pointer; display: inline-block; width: 5%; }
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
  width: 98%;
}
li {
  display: inline-block;
  margin: 0 10px;
  padding: 5px 0;
  width: 100%;
  border-bottom: 1px solid #dedede;
}
a {
  color: #42b983;
}
</style>