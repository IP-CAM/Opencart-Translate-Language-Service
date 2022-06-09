//import apiCall from '../libraries/apiCall.js';
import { missing_translations, traslate, source_lang } from '../libraries/apiCall.js';

export default Vue.component( 'translate-btn',{
  template: `
    <div>
      <v-btn            
        color="primary"
        :disabled="disable"
        @click="createTranslations"
      >
        {{ $t("dash.sub.translate") }}
      </v-btn>
      <v-dialog
        v-model="dialog"
        width="500"
      >
        <v-card>
          <v-card-title class="text-h5 grey lighten-2">
            {{ $t("dash.sub.translate") }}
          </v-card-title>
          <v-card-text>
            <v-timeline
              align-top
              dense
            >
              <v-timeline-item
                v-for="message in messages"
                :color="message.color"
                v-bind:key="message.text"
                small
              >
                <div>
                  <div class="font-weight-normal">
                    <strong>{{ message.text }}</strong>
                  </div>
                </div>
              </v-timeline-item>
            </v-timeline>
          </v-card-text>
          <v-divider></v-divider>
          <v-card-actions>
            <v-spacer></v-spacer>
            <v-btn
              color="primary"
              text
              @click="dialog = false"
            >
              {{ $t("dash.sub.close") }}
            </v-btn>
          </v-card-actions>
        </v-card>
      </v-dialog>
    </div>
  `,
  data(){
    return{
      dialog: false,
      disable: false,
      basic_url_data : '', 
      source_lang: '',
      products_updated: 0,
      messages: [],
    }
  },
  props:['langId'],
  created(){    
    this.basic_url_data =  this.$store.getters.getBasicUrlData;    
    this.getSourceLang();
  },
  methods:{    
    getSourceLang(){
      source_lang({
        lang_id: this.langId,
      }).then(result =>{
        this.source_lang = result;
      }).catch(error => {
        this.disable = true;
      });
    },
    createTranslations(){
      
      this.dialog = true;
      this.messages.push({text:this.$t('dash.sub.loading'), color: 'orange'});
      
      let apiCalls = this.calculateApiCalls();
      
      this.excecuteAsyncCalls(apiCalls);
    },
    excecuteAsyncCalls(numberOfCalls){
      (async () => {
        for (let i = 1; i <= numberOfCalls; i++) {            
          let res = await this.excecuteApiCall();            
          this.products_updated += res.products_description_updated;
          let message = this.$t('dash.sub.trasnslated') + ' : ' + this.products_updated;
          this.messages.push({text:message, color: 'orange'});
        }
        this.messages.push({text:this.$t('dash.sub.success'), color: 'green'});
        missing_translations({
          lang: this.langId,
        }).then((result) => {
          this.$store.commit('setMissingTranslations', {
            lang: this.langId, 
            missing: result
          });
        });

      })().catch((error) => {
        this.messages.push({text:this.$t('dash.sub.fail'), color: 'red'});
      });
    },
    excecuteApiCall(){
      return traslate({
        translate_lang: this.langId,
        source_lang: this.source_lang
      }).then(result =>{
        return result;
      });
    },
    calculateApiCalls()
    {
      let batch = 10;
      let missing = this.$store.getters.getMissingTranslations(this.langId);      
      let calls = 1;

      if(missing < batch)
        return calls;

      if(missing > batch)
       calls = parseInt(missing / batch);

      if( (missing % batch) > 0)
        calls++;

      return calls;
    },
  }
});