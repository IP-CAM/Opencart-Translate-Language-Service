import productsPerLanguage from "../sub-components/products-per-language.vue.js";
import totalProducts from "../sub-components/total-products.vue.js";

export default Vue.component( 'tab-dashboard',{
  template: `
    <div>
      <h1>Translate Service Dash</h1>
      <v-container class="grey lighten-5">
        <v-col class="d-flex flex-column" sm="12" md="12">
          <v-row>                      
            <v-col sm="12" md="4">
              <total-products></total-products>
            </v-col>
            <v-col sm="12" md="4" v-for="language in languges" v-bind:key="language.name">
              <products-per-language :lang-id="language.language_id" :lang-name="language.name"></products-per-language>
            </v-col>
          </v-row>
        </v-col>
      </v-container>
    </div>
  `,
  component: {
    'products-per-language' : productsPerLanguage,
    'totalProducts' : totalProducts
  },
  data(){
    return{
      basic_url_data : '',  
      languges: {},         
    }
  },
  created(){
    this.basic_url_data =  this.$store.getters.getBasicUrlData;
    this.getLanguages();
  },
  methods:{
    getLanguages() {   
      axios.get(this.basic_url_data.site_url + 'index.php',{
				params: {
					route:'api/st/languages',
					user_token: this.basic_url_data.token,
				}
			})
			.then((result) => {
        this.languges = result.data;
			})
      .catch(error => {
        console.log(error);
      })
    }
  }
});