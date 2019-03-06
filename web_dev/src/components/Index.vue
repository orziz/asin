<template>
	<div id="main">
		<h1>{{ msg }}</h1>
		<h2>如你所见，该系统正在制作中，如有任何建议或问题请联系 &lt;子不语&gt;(QQ:1063614727)</h2>
	</div>
</template>

<script>
export default {
	name: 'Index',
	data () {
		return {
			msg: '欢迎来到刺客组织'
		}
	},
	mounted: function() {
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
h1, h2 {
  font-weight: normal;
}
ul {
  list-style-type: none;
  padding: 0;
}
li {
  display: inline-block;
  margin: 0 10px;
}
a {
  color: #42b983;
}
</style>