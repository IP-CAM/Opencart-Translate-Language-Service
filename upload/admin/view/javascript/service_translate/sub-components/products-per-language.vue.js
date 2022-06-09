import translateBtn from "./translate-btn.vue.js";
import { missing_translations } from '../libraries/apiCall.js';

export default Vue.component( 'products-per-language',{
  template: `
    <div>
      <v-card
        class="mx-auto"
        outlined
      >
        <v-list-item three-line>
          <v-list-item-content>
            <div class="text-overline mb-4">
              {{ langName }}
            </div>
            <v-list-item-title class="text-h5 mb-1">
              {{ missing_translations }}
            </v-list-item-title>            
            <v-list-item-subtitle>{{ $t("dash.sub.missing_translations") }}</v-list-item-subtitle>
          </v-list-item-content>
          <v-list-item-avatar
            tile
            size="80"
            :color="missingTranslationColor"
          ></v-list-item-avatar>
        </v-list-item>
        <v-card-actions>
          <translate-btn :lang-id="getLangId"></translate-btn>          
        </v-card-actions>
      </v-card>
    </div>
  `,
  component:{
    translateBtn: 'translate-btn'
  },
  data(){
    return{
      basic_url_data : '', 
      missing_translations : 0
    }
  },
  props:['langId','langName'],
  computed: {
    missingTranslationColor() {      
      let total_products = this.$store.getters.getTotalProducts;
      if(this.missing_translations === total_products)
        return 'red';
      if(this.missing_translations === 0)
        return 'green';
      return 'orange';
    },
    getLangId(){
      return this.langId;
    }
  },
  created(){    
    this.basic_url_data =  this.$store.getters.getBasicUrlData;
    this.getMissingTranslationsCount();
  },
  methods:{
    getMissingTranslationsCount(){
      missing_translations({
        lang: this.langId
      })
			.then((result) => {
        this.missing_translations = result;
        this.$store.commit('setMissingTranslations', {
          lang: this.langId, 
          missing: this.missing_translations
        });
			})
      .catch(error => {
        console.log(error);
      })
    }
  }
});