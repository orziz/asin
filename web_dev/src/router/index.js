import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [{
		path: '/',
		name: 'Index',
		component: require('@/components/Index').default
	},{
		path: '/rank',
		name: 'Rank',
		component: require('@/components/Rank/Index').default
	},{
		path: '/info',
		name: 'Info/Index',
		component: require('@/components/Info/Index').default
	},{
		path: '/info/:id',
		name: 'Info/Id',
		component: require('@/components/Info/Index').default
	},{
		path: '/admin',
		name: 'Admin/Index',
		component: require('@/components/Admin/Index').default
	},{
		path: '/admin/joinOrgan',
		name: 'Admin/JoinOrgan',
		component: require('@/components/Admin/JoinOrgan').default
	},{
		path: '/admin/joinOrgan/:id',
		name: 'Admin/JoinOrgan/Id',
		component: require('@/components/Admin/JoinOrgan').default
	},{
		path: '/admin/list',
		name: 'Admin/List',
		component: require('@/components/Admin/List').default
	},{
		path: '/admin/setUserInfo',
		name: 'Admin/SetUserInfo/Index',
		component: require('@/components/Admin/SetUserInfo').default
	},{
		path: '/admin/setUserInfo/:id',
		name: 'Admin/SetUserInfo/Id',
		component: require('@/components/Admin/SetUserInfo').default
	}]
})
