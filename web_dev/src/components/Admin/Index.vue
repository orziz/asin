<template>
	<div id="main">
		<h2>这里是管理后台</h2>
	</div>
</template>

<script>
export default {
	name: 'Index',
	data () {
		return {
		}
	},
	methods: {
		checkLogin() {
			let token = localStorage.getItem("token");
			if (token) {
				orzzz.$post({
					mod: 'user_login',
					token: token,
					success: (res)=> {
						this.$router.replace({path:'/admin/list'});
					},
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