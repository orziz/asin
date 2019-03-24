<template>
	<div id="main">
		<h1>【刺客组织&子不小语】（Q群机器人）使用协议（管理员版）</h1>
        <div class="desc">
            <p class="title">概述</p>
            <p>【刺客组织&子不小语】（Q群机器人）使用协议（管理员版）（以下简称“本协议”）为《刺客伍六七》的同人Q群娱乐系统（以下简称“本系统”）的使用协议，本协议适用于所有《刺客伍六七》相关Q群，请在Q群内接入本群之前，仔细阅读本协议，以免造成不必要的损失。</p>
            <p>使用本系统即视为同意本协议内所有规则，请务必遵守。如有异议，请立即停止使用本系统。</p>
            <p class="title">用途</p>
            <p>本系统适用于所有《刺客伍六七》相关Q群，主要用于群内娱乐及管理，本系统由网页应用及QQ机器人组成。</p>
            <p class="title">系统功能</p>
            <p class="title subtitle">清理群成员</p>
            <p>Q群机器人内置管理功能（需Q群机器人为群管理员）</p>
            <p>用于踢出群内指定用户或批量清除不活跃用户<span class="red">（此功能为高危功能，请谨慎使用）</span></p>
            <p class="title subtitle">加入刺客组织</p>
            <p>本系统内置功能</p>
            <p>将用户录入刺客组织，使用户拥有使用本系统的权利</p>
            <p class="title subtitle">刺客排行榜</p>
            <p>本系统内置功能</p>
            <p>根据积分将加入刺客组织的用户进行排名</p>
            <p class="title subtitle">签到</p>
            <p>本系统内置功能</p>
            <p>用于刺客组织内用户签到</p>
            <p class="title subtitle">信息</p>
            <p>本系统内置功能</p>
            <p>用于查看、使用刺客组织内用户信息（包括但不仅限于信息、属性、积分、暗币等）</p>
            <p class="title subtitle">刺客大乱斗</p>
            <p>本系统娱乐功能（需额外授权）</p>
            <p>在Q群内的娱乐项目<span class="red">（本功能可能存在刷屏风险，请斟酌使用）</span></p>
            <p class="title">如何接入本系统</p>
            <p>接入本系统需所接入本系统的Q群管理（以下简称“被授权人”）提交申请及授权</p>
            <p class="title">被授权人拥有的权力</p>
            <p>1.接入或取消接入本系统</p>
            <p>2.Q群机器人在接入群的使用权（本系统的管理功能除外，包括但不仅限于录入用户信息、修改用户信息、开始刺客大乱斗等）</p>
            <p>3.本系统的修改建议权</p>
            <p class="title">被授权人应尽的责任</p>
            <p>1.接入群的日常维护</p>
            <p class="title">其他备注</p>
            <p>1.本系统暂定为免费使用（如有更变，将会另行通知）</p>
            <p>2.接入群因本系统造成的一切损失由被授权人承担</p>
            <p>3.接入群对本系统造成的一切损失由被授权人承担</p>
            <p>4.本系统可能存在不定期更新、维护或停止使用</p>
            <p>5.本系统管理维护组有权暂停或停止接入群使用或拒绝接入</p>
            <p class="right">《刺客组织&子不小语》管理维护组</p>
        </div>
	</div>
</template>

<script>
export default {
	name: 'Admin',
	data () {
		return {
			msg: '欢迎来到刺客组织'
		}
	},
	mounted: function() {
		console.log(this);
	}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
#main { text-align: left; margin: 20px 0; /*font-family: "黑体"*/ }
.desc p { text-indent: 2em; width: 80%; margin: 0 auto; font-size: 1.5em; }
.desc .title { text-indent: 0; font-size: 3em; padding: 20px; }
.desc .subtitle { font-size: 2em; padding: 10px 0 10px 1em; }
.desc .right { text-align: right; padding: 30px; }
.desc .red { color: #FF0000; }
h1, h2 {
    text-align: center;
    font-weight: normal;
}
a {
  color: #42b983;
}
</style>