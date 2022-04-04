export default Vue.component( 'tab-html-encode',{
  template: `
    <div>
      <v-container class="grey lighten-5">
        <v-col class="d-flex flex-column" sm="12" md="8">
          <v-row>          
            <v-col sm="12" md="12">
              <v-card elevation="5" outlined tile>
                <v-card-text>
                  <h4>{{ $t("html_encode.instructions_title") }}</h4>
                  {{ $t("html_encode.instructions") }}
                </v-card-text>
              </v-card>
            </v-col>
            <v-col sm="12" md="12">
              <v-card elevation="5" outlined tile>
                <v-card-text>
                  <v-container fluid>                  
                    <v-row>
                      <v-col cols="12" sm="12" md="12">
                        <label>{{ $t("html_encode.remove_tags") }}:</label><br>
                      </v-col>
                      <v-col v-for="tag in tags" :key="tag.id" cols="12" sm="4" md="2">
                        <v-checkbox 
                          v-model="selected"
                          :label="tag.name"
                          :value="tag.id"
                        ></v-checkbox>
                      </v-col>
                    </v-row>
                    <v-row>
                      <v-col cols="12" sm="12" md="12">
                        <v-tooltip bottom>
                          <template v-slot:activator="{ on, attrs }">
                            <v-btn block 
                              color="primary"
                              v-bind="attrs"
                              v-on="on"                            
                              @click="startDecode">
                              {{ $t("html_encode.decode_all") }}
                            </v-btn>   
                          </template>
                          <span>{{ $t("html_encode.decode_all_tooltip") }}</span>
                        </v-tooltip>
                      </v-col>
                    </v-row>              
                  </v-container>  
                </v-card-text>
              </v-card>
            </v-col>
          </v-row> 
        </v-col>
        <v-col class="d-flex flex-column" sm="12" md="4">
          <v-row> 
            <v-col sm="12" md="12">
              <v-card elevation="16" class="mx-auto">
                <v-card-text>
                  <h4 class="">{{ $t("html_encode.progress") }}</h4>
                  <v-virtual-scroll
                    :bench="benched"
                    :items="items"
                    height="290"
                    item-height="25">      
                    <template v-slot:default="{ item }">          
                      <v-list-item :key="item">
                        <v-list-item-content>
                          <v-list-item-title>
                            <v-icon
                              color="green"
                              x-small>
                              mdi-check
                            </v-icon>
                            {{ item.current_products }} {{$t("html_encode.products_parsed")}} {{ item.total_products }}
                          </v-list-item-title>
                        </v-list-item-content>
                      </v-list-item>
                    </template>
                  </v-virtual-scroll>
                </v-card-text>
              </v-card>
            </v-col>
          </v-row>
        </v-col>  
      </v-container>      
      </div>
    </div>
  `,
  data(){
    return{
      basic_url_data : '',
      tags:[
        {id:'b',name:'Bold',tags:['<b>','</b>']},
        {id:'div',name:'Div',tags:['<div>','</div>']},
        {id:'em',name:'em',tags:['<em>','</em>']},
        {id:'i',name:'Italics',tags:['<i>','</i>']},        
        {id:'p',name:'Paragraph',tags:['<p>','</p>']},        
        {id:'span',name:'Span',tags:['<span>','</span>']},
        {id:'strong',name:'Strong',tags:['<strong>','</strong>']},
      ],
      selected:[],
      from:0,
      step:25,
      benched: 0,
      total:0,
      items:[]
    }
  },
  created(){
    this.basic_url_data =  this.$store.getters.getBasicUrlData;
  },
  computed: {
    tags_html(){      
      let html_tags = [];
      
      for(let i=0; i < this.selected.length; i++){
        html_tags.push('<'+this.selected[i]+'>');
        html_tags.push('</'+this.selected[i]+'>');
      }

      return JSON.stringify(html_tags);
    },
  },
  methods:{
    startDecode(){
      axios.get(this.basic_url_data.site_url + 'index.php',{
				params: {
					route:'api/st/handle_html',
					user_token: this.basic_url_data.token,
          from: this.from,
          limit: this.step,
          html_codes: this.tags_html
				}
			})
			.then((result) => {

        let productsDecoded = result.data.products;

        this.from += this.step;
        this.total += productsDecoded;
        
        this.items.push({
          current_products:productsDecoded,
          total_products:this.total,
        });

        this.startDecode();
			})
      .catch(error => {
        console.log(error);
        this.from=0;
      })
    }
  }
});