export default Vue.component( 'total-products',{
  template: `
    <div>
      <v-card
        class="mx-auto"
        outlined
      >
        <v-list-item three-line>
          <v-list-item-content>
            <v-list-item-title class="text-h5 mb-1">
              {{ $t("dash.sub.products") }}
            </v-list-item-title> 
            <div class="text-overline mb-4">
              {{ $t("dash.sub.shop_products") }} : {{ total_products }}
            </div>
            <div class="text-overline mb-12">
              {{ $t("dash.sub.without_description") }} : {{ products_without_description }}
            </div>
          </v-list-item-content>
          <v-list-item-avatar
            tile
            size="80"
            :color="getStatus"
          ></v-list-item-avatar>
        </v-list-item>
      </v-card>
    </div>
  `,
  data(){
    return{
      basic_url_data : '', 
      total_products : 0,
      products_without_description: 0,
    }
  },
  computed:{
    getStatus(){
      if(this.products_without_description === 0)
        return 'green';      
      if(this.total_products === this.products_without_description)
        return 'red';
      return 'orange';
    }
  },
  created(){    
    this.basic_url_data =  this.$store.getters.getBasicUrlData;
    this.getTotalProducts();
    this.getProductsWithoutDescription();
  },
  methods:{
    getTotalProducts(){
      axios.get(this.basic_url_data.site_url + 'index.php',{
				params: {
					route:'api/st/products/total',
					user_token: this.basic_url_data.token,
				}
			})
			.then((result) => {
        this.total_products = result.data;
        this.$store.commit('setTotalProducts', this.total_products);
			})
      .catch(error => {
        console.log(error);
      })
    },
    getProductsWithoutDescription(){
      axios.get(this.basic_url_data.site_url + 'index.php',{
				params: {
					route:'api/st/products/without_description',
					user_token: this.basic_url_data.token,
				}
			})
			.then((result) => {
        this.products_without_description = result.data;
			})
      .catch(error => {
        console.log(error);
      })
    },
  }
});