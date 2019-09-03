/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Vue.component('errors', {
  template: `
<div>
 <div class="container search-section">
    <form class="form-inline form-search-archive">	
        <div class="row">	
                 <div class="form-group "> 
                    <label>{{$t('main.extension') }}</label>
                     <select v-model="extensionInput" class="form-control search-slt" >
                            <option  value="" >{{$t('main.all') }}</option>
                            <option  value="xml" >Xml</option>
                            <option  value="jpg" >Jpg</option>
                            <option  value="png" >Png</option>
                            <option  value="gif" >Gif</option>
                            <option  value="pdf" >Pdf</option>
                     </select>
                </div>  
            </div>
            <div class="row">
                <template v-if="singleArch === false">
                 <div class="form-group">  
                    <label>{{$t('main.id') }}</label>    
                    <input type="text" placeholder="ID.." v-model="idInput" class="form-control search-slt"  />
                </div>
                </template>
        </div>    
            <div class="form-group">  
                <button type="button" v-on:click="eventBus.$emit('update-grid',{'offset':0,'extension':extensionInput,'site':siteInput,'id':idInput})" class="btn btn-search wrn-btn"><i class="fa fa-search fa-fw"></i>{{$t('main.search')}}</button>            
            </div>
    </form>
    </div>
<simple-grid  :columns="['description','resource.site','resource.id','message']" 
                 :limit=10 :datasUrl='datasUrl'  >  
                </simple-grid>      
    </div>
` ,
    props: {
        dataUrl: String,
        columns: Array,   
        singleArch: {type:Boolean,default: false},
        translatableColumns: {type:Object, default: () => ({})}
  },
   data: function () {
      return {    
        extensionInput: ''
    }
   },
   
   });