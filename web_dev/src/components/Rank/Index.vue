<template>
	<div id="main">
		<ul>
			<li class="top">
				<span class="rank">排名</span>
				<span class="nickname">姓名</span>
				<span class="score">积分</span>
			</li>
			<li v-for="item in rankList">
				<span class="rank">{{ item.rank }}</span>
				<!-- <span class="nickname"><router-link :to="'/info/'+item.qq">{{ item.nickname }}</router-link></span> -->
				<span class="nickname">{{ item.nickname }}</span>
				<span class="score">{{ item.score }}</span>
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
li { display: block; font-size: 1.5em; border-bottom: 1px solid #dedede; }
li.top { font-size: 3em; font-weight: bold; width: 100%; border-top: 1px solid #dedede; }
span { display: inline-block; }
span.rank { width: 25%; }
span.nickname { width: 35%; }
span.score { width: 30%; }
h1, h2 {
  font-weight: normal;
}
a {
  color: #42b983;
}
</style>