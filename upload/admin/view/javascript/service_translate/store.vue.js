export default new Vuex.Store({
    state: {
        basic_url_data:''
    },
    getters: {
        getBasicUrlData: state => { return state.basic_url_data },
    },
    mutations: {
        setBasicUrlData(state, basicUrlData){
            state.basic_url_data = basicUrlData;
        }   
    }
});