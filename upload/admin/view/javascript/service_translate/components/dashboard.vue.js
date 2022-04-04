export default Vue.component( 'tab-dashboard',{
  template: `
    <div>
      <h1>Translate Service Dash</h1>
    
      </div>
    </div>
  `,
  data(){
    return{
      basic_url_data : '',           
    }
  },
  created(){
    this.basic_url_data =  this.$store.getters.getBasicUrlData;
  },
  methods:{

  }
});