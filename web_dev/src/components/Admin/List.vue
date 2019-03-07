<template>
	<div id="main">
		<ul>
			<li class="top">
				<span class="rank">排名</span>
				<span class="qq">QQ</span>
				<span class="nickname">姓名</span>
				<span class="score">积分</span>
				<span class="control"><router-link :to="'/admin/setUserInfo/'">新增</router-link></span>
			</li>
			<li v-for="item in rankList">
				<span class="rank">{{ item.rank }}</span>
				<span class="qq">{{ item.qq }}</span>
				<span class="nickname"><router-link :to="'/info/'+item.qq">{{ item.nickname }}</router-link></span>
				<span class="score">{{ item.score }}</span>
				<span class="control"><router-link :to="'/admin/setUserInfo/'+item.qq">修改</router-link></span>
			</li>
		</ul>
	</div>
</template>

<script>
export default {
	name: 'Index',
	data () {
		return {
			msg: '欢迎来到刺客组织',
			rankList: {}
		}
	},
	methods: {
		checkLogin() {
			let token = localStorage.getItem("token");
			if (token) {
				orzzz.$post({
					mod: 'user_login',
					token: token,
					fail: (res)=> {
						alert(res.errMsg);
						this.$router.replace({path:'/admin/login'});
					}
				});
			} else {
				this.$router.replace({path:'/admin/login'});
			}
		}
	},
	mounted: function() {
		this.checkLogin();
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
li { display: block; font-size: 1.1em; border-bottom: 1px solid #dedede; }
li.top { font-size: 2em; font-weight: bold; width: 100%; border-top: 1px solid #dedede; }
span { display: inline-block; }
span.rank { width: 12%; }
span.qq { width: 20%; }
span.nickname { width: 35%; }
span.score { width: 15%; }
span.control { width: 10%; }
h1, h2 {
  font-weight: normal;
}
a {
  color: #42b983;
}
</style>