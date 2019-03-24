import Vue from 'vue'
import Router from 'vue-router'
import HelloWorld from '@/components/HelloWorld'

Vue.use(Router)

export default new Router({
	mode: 'history',
	routes: [{
		path: '/',
		name: 'Index',
		// component: require('@/components/Index').default
		component: () => import('@/components/Index')
	},{
		path: '/rank',
		name: 'Rank',
		// component: require('@/components/Rank/Index').default
		component: () => import('@/components/Rank/Index')
	},{
		path: '/info',
		name: 'Info/Index',
		// component: require('@/components/Info/Index').default
		component: () => import('@/components/Info/Index')
	},{
		path: '/info/:id',
		name: 'Info/Id',
		// component: require('@/components/Info/Index').default
		component: () => import('@/components/Info/Index')
	},{
		path: '/admin',
		name: 'Admin/Index',
		// component: require('@/components/Admin/Index').default
		component: () => import('@/components/Admin/Index')
	},{
		path: '/admin/login',
		name: 'Admin/Login',
		// component: require('@/components/Admin/Login').default
		component: () => import('@/components/Admin/Login')
	},{
		path: '/admin/register',
		name: 'Admin/Register',
		// component: require('@/components/Admin/Register').default
		component: () => import('@/components/Admin/Register')
	},{
		path: '/admin/setUserInfo',
		name: 'Admin/SetUserInfo',
		// component: require('@/components/Admin/SetUserInfo').default
		component: () => import('@/components/Admin/SetUserInfo')
	},{
		path: '/admin/setUserInfo/:id',
		name: 'Admin/SetUserInfo/',
		// component: require('@/components/Admin/SetUserInfo').default
		component: () => import('@/components/Admin/SetUserInfo')
	},{
		path: '/admin/list',
		name: 'Admin/List',
		// component: require('@/components/Admin/List').default
		component: () => import('@/components/Admin/List')
	},{
		path: '/protocol/admin',
		name: 'Protocol/Admin',
		// component: require('@/components/Admin/List').default
		component: () => import('@/components/Protocol/Admin')
	}]
})
