/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// register the grid component
 
Vue.component('grid-archives', {
  template: `
  <div>
    <div class="container search-section">
    <form class="form-inline form-search-archive">	
    <div class="row">
	
                <div class="form-group "> 
                    <label>{{$t('main.platform') }}</label>
                     <select v-model="platformInput" class="form-control search-slt" v-bind:class="{ 'is-invalid': errorPlatform }">
                        <option v-for="(platform, i) in platforms"  :value="i" > {{$t('main.' + platform ) }}</option>
                     </select>
                </div>           
                 <div class="form-group">
                    <label>{{$t('main.site') }}</label>
                    <input type="text" placeholder="Site name.." v-model="siteInput" class="form-control search-slt" v-bind:class="{ 'is-invalid': errorSite}" />
                </div>
      <div class="form-group">  
        <label>{{$t('main.id') }}</label>    
        <input type="text" placeholder="ID.." v-model="idInput" class="form-control search-slt"  v-bind:class="{ 'is-invalid': errorId}" />
    </div>
    <div class="form-group">  
               <button type="button" v-on:click="updateGrid({'offset':0})" class="btn btn-search wrn-btn"><i class="fa fa-search fa-fw"></i>{{$t('main.search')}}</button>
             
    </div>
     <div class="form-group">  
          <button type="button" v-on:click="addArchive(platformInput,siteInput,idInput)" class="btn btn-success  btn-lg"><i class="fa fa-plus fa-fw" aria-hidden="true" ></i>{{$t('main.add')}}</button>
    </div>
    </div>
    <div class="row">
            <div class="form-group "> 
                    <label>{{$t('main.status') }}</label>
                     <select v-model="statusInput" class="form-control search-slt">
                        <option  value="" > {{$t('archive_status.all') }}</option>
                        <option v-for="status in statuses"  :value="status" > {{$t('archive_status.' + status ) }}</option>
                     </select>
                </div> 
                <div class="form-group "> 
                    <label>Uuid</label>
                    <input type="text" placeholder="Uuid.." v-model="uuidInput" class="form-control search-slt"  />
                </div>
    </div>
    </form>
    </div>   
    <simple-grid  :columns='columns' :columnsToTranslate='columnsToTranslate' :limit=8 :dataUrl='dataUrl' :showButtons="true"  >  
    </simple-grid>    
  </div>   
`,
    props: {
        dataUrl: String,
        columns: Array,       
        columnsToTranslate: {type:Object, default: () => ({})}
  },
   data: function () {
      return {   
        idInput: '',
        siteInput: '',
        platformInput: '',
        statusInput: '',
        uuidInput: '',
        platforms: {'':'all_platforms','OB':'OB','OJ':'OJ'},        
        statuses: ["queued","incomplete","invalid","ready","signalled"],
        errorPlatform: false,
        errorSite: false,
        errorId: false,
        sampleData: {"glossary":{"title":"example glossary","GlossDiv":{"title":"S","GlossList":{"GlossEntry":{"ID":"SGML","SortAs":"SGML","GlossTerm":"Standard Generalized Markup Language","Acronym":"SGML","Abbrev":"ISO 8879:1986","GlossDef":{"para":"A meta-markup language, used to create markup languages such as DocBook.","GlossSeeAlso":["GML","XML"]},"GlossSee":"markup"}}}}}
      }
  },
  
  methods: {
      updateGrid: function(params){
          var myparams = {"id":this.idInput,"site":this.siteInput,"platform":this.platformInput,"status":this.statusInput,"uuid":this.uuidInput};
          for (var param in params) {
            myparams[param] = params[param];
           }
          this.eventBus.$emit('update-grid',myparams) ;
      },
      addArchive: function(platform,site,id){
          this.errorPlatform = false;this.errorSite = false;this.errorId = false;
          if(!platform){
              this.errorPlatform = true;
          }
          if(!site){
              this.errorSite = true;
          }
          if(!id){
              this.errorId = true;
          }
          if(!platform || !site || !id){
              console.log("empty input");
              return ;
          }
          var url = "/api/archive" ;
            axios.post(url,{platform: platform,site:site,id:id}).then(response => {
                var text = this.$t(response.data.message);
                this.eventBus.$emit('show-modal',{body:text,header:"success"}) ;                        
                this.idInput = '';
                this.siteInput = '';
                this.platformInput = '';
                this.updateGrid({});
        },(error) => {
          var text = this.$t(error.response.data.message);
          this.eventBus.$emit('show-modal',{body:text,header:"error"}) ;
          this.eventBus.$emit('update-grid',{}) ;
        })
      }
  }
 
   
})


