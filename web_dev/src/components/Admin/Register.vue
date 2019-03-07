<template>
	<div id="main">
		<ul>
			<li v-for="(item,key) in liObj">
				<label :for="key" :class="item.must ? 'must' : ''">{{ item.title }}</label>
				<input v-if="item.type != 'textarea'" :type="item.type" :id="key" :ref="key" :value="item.default" :disabled="item.noChange">
				<textarea v-else :id="key" :ref="key">{{ item.default }}</textarea>
				<span>{{ item.note }}</span>
			</li>
		</ul>
		<br>
		<input type="submit" id="postForm" value="提交" class="postForm" @click="postForm">
	</div>
</template>

<script>
export default {
	name: 'Register',
	data () {
		return {
			liObj: {
				username: {
					title: '用户名',
					type: 'text',
					must: true
				},
				password: {
					title: '密码',
					type: 'password',
					must: true
				},
				code: {
					title: '验证码',
					type: 'password',
					must: true
				}
			}
		}
	},
	methods: {
		postForm: function () {
			var refs = this.$refs;
			let data = {
				mod: 'user_register',
				success: (res)=>{
					alert('注册成功');
				},
				fail: (res)=>{
					alert("注册失败\n错误代码："+res.errCode+"\n错误信息："+res.errMsg);
				}
			};
			for (let key in refs) {
				if (!refs[key][0].value) {
					alert(this.liObj[key].title+' 不可为空');
					return;
				}
				data[key] = refs[key][0].value;
			}
			orzzz.$post(data);
		}
	},
	mounted: function() {
		console.log(this);
		orzzz.$post({
			mod: 'rank_score',
			action: 'getRankList',
			success: (res)=>{
				console.log(res);
				for (let i = 0; i < res.length; i++) {
					if (res[i]['score'] < 0) res[i]['score'] = '？？？';
				}
				this.rankList = res
			}
		})
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
ul { list-style-type: none; padding: 0; margin: 10px auto; }
li { display: block; font-size: 2em; border-bottom: 1px solid #dedede; }
li.top { font-size: 4em; font-weight: bold; width: 100%; border-top: 1px solid #dedede; }
span { display: inline-block; }
span.rank { width: 10%; }
span.qq { width: 20%; }
span.nickname { width: 35%; }
span.score { width: 15%; }
span.control { width: 15%; }
h1, h2 {
  font-weight: normal;
}
a {
  color: #42b983;
}
</style>