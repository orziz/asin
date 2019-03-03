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
		path: '/admin',
		name: 'Admin/Index',
		component: require('@/components/Admin/Index').default
	}]
})
