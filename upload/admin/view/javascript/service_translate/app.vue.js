import store  from './store.vue.js';
import router from './router.vue.js';
import i18n from './lang/i18n.vue.js'

const mountEl = document.querySelector("#er-es");

new Vue({
	el: '#er-es',
	vuetify: new Vuetify(),
	delimiters: ['${', '}'],
	store,
	router,
  i18n,
  propsData: { ...mountEl.dataset },
  props: ["stService","stSuffix","stMetaFromTitle","stTranslateDescription","adminFolder"],
	data(){
		return {}
	},
	created(){
		this.storeBasicData();
    this.storeModuleSettings();
	},
	methods:{
		storeBasicData(){
			let url = new URL(window.location.href);
			this.$store.commit('setBasicUrlData', {
				token : url.searchParams.get("user_token"),
				site_url : url.href.split(this.adminFolder)[0]
			});
		},
    storeModuleSettings(){
      this.$store.commit('setSettings', {
				st_service : this.stService,
				st_suffix : this.stSuffix,
        st_meta_fromt_tile : this.stMetaFromTitle,
        st_translate_description : this.stTranslateDescription,
			});
    }
	}
}).$mount("#er-es");