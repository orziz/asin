<template>
	<div id="joinOrgan">
		<ul>
			<li><span class="tdTile">栏目</span><span class="tdTile">值</span><span class="tdTile">备注</span></li>
			<li v-for="(item,key) in liObj">
				<label :for="key" :class="item.must ? 'must' : ''">{{ item.title }}</label>
				<input v-if="item.type != 'textarea'" :type="item.type" :id="key" :ref="key">
				<textarea v-else :id="key" :ref="key"></textarea>
				<span>{{ item.note }}</span>
			</li>
		</ul>
		<br>
		<input type="submit" id="postForm" value="提交" class="postForm" click="postForm">
	</div>
</template>

<script>
export default {
	name: 'JoinOrgan',
	data () {
		return {
			liObj: {
				qq: {
					title: 'QQ',
					type: 'number',
					note: '必填/用户QQ号，用作账号绑定',
					must: true
				},
				nickname: {
					title: '姓名',
					type: 'text',
					note: '必填/显示在刺客组织里的名称',
					must: true
				},
				sex: {
					title: '性别',
					type: 'number',
					note: '默认为0'
				},
				age: {
					title: '年龄',
					type: 'number',
					note: '默认为0'
				},
				height: {
					title: '身高',
					type: 'number',
					note: '默认为0，单位为cm'
				},
				weight: {
					title: '体重',
					type: 'number',
					note: '默认为0，单位为kg'
				},
				str: {
					title: '力量',
					type: 'number',
					note: '默认为0'
				},
				dex: {
					title: '敏捷',
					type: 'number',
					note: '默认为0'
				},
				con: {
					title: '体质',
					type: 'number',
					note: '默认为0'
				},
				ine: {
					title: '智力',
					type: 'number',
					note: '默认为0'
				},
				wis: {
					title: '感知',
					type: 'number',
					note: '默认为0'
				},
				cha: {
					title: '魅力',
					type: 'number',
					note: '默认为0'
				},
				free: {
					title: '自有属性点',
					type: 'number',
					note: '默认为0'
				},
				arms: {
					title: '武器',
					type: 'textarea',
					note: '默认为空'
				},
				introduce: {
					title: '介绍',
					type: 'textarea',
					note: '默认为空'
				},
				skill1: {
					title: '技能1',
					type: 'textarea',
					note: '默认为空'
				},
				skill2: {
					title: '技能2',
					type: 'textarea',
					note: '默认为空'
				},
				skill3: {
					title: '技能3',
					type: 'textarea',
					note: '默认为空'
				},
				skill4: {
					title: '技能4',
					type: 'textarea',
					note: '默认为空'
				},
				score: {
					title: '积分',
					type: 'number',
					note: '默认为0'
				},
				credit: {
					title: '暗币',
					type: 'number',
					note: '默认为0'
				},
				rank: {
					title: '排名',
					type: 'number',
					note: '默认为0，一般不填此值'
				}
			}
		}
	},
	methods: {
		postForm: function () {
			var $refs = this.$refs;
			for (var i = 0; i < $refs.length; i++) {
				console.log(refs[i]);
			}
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