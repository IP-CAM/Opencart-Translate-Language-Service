import store  from './store.vue.js';
import router from './router.vue.js';
import i18n from './lang/i18n.vue.js'

new Vue({
	el: '#er-es',
	vuetify: new Vuetify(),
	delimiters: ['${', '}'],
	store,
	router,
  i18n,
	data(){
		return {}
	},
	created(){
		this.storeBasicData();
	},
	methods:{
		storeBasicData(){
			var url = new URL(window.location.href);
			this.$store.commit('setBasicUrlData', {
				token : url.searchParams.get("user_token"),
				site_url : url.href.split("admin")[0],
			});
		}
	}
});