export default new Vuex.Store({
  state: {
    basic_url_data:'',
    total_products:'',
    missing_translations:{},
    settings:{},
    languages:{}
  },
  getters: {
    getBasicUrlData: state => { return state.basic_url_data },
    getTotalProducts: state => { return state.total_products },
    getMissingTranslations: (state) => (lang) => { return state.missing_translations[lang] },
    getSetting: (state) => (setting) => { return state.settings[setting] },
    getLanguages: (state) => { return state.languages },
  },
  mutations: {
    setBasicUrlData(state, basicUrlData){
      state.basic_url_data = basicUrlData;
    },
    setTotalProducts(state, totalProducts){
      state.total_products = totalProducts;
    },   
    setMissingTranslations(state, params){
      state.missing_translations[params.lang] = params.missing;
    },
    setSettings(state, settings){
      state.settings = settings;
    },
    setLanguages(state, languages){
      state.languages = languages;
    }
  }
});