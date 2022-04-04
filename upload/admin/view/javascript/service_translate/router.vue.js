import TabDashboard from './components/dashboard.vue.js';
import TabHtmlEncode from './components/html_encode.vue.js';

export default new VueRouter({
	routes : [
		{
			path:'/',
			component:TabDashboard
		}, 
		{
			path:'/html-encode',
			component:TabHtmlEncode
		},
	]
})