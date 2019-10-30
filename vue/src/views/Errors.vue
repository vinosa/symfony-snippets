<template>
  <div class="home">
       <b-form  v-if="show" class="form-inline form-search-archive">
     <div class="row">	
     <b-form-group id="input-group-3" :label="$t('main.source')" label-for="input-3">
        <b-form-select
          id="input-3"
          v-model="form.source"
          :options="sources"
        ></b-form-select>
      </b-form-group>
        <b-form-group id="input-group-4" :label="$t('main.type')" label-for="input-4">
        <b-form-select
          id="input-4"
          v-model="form.type"
          :options="types"
        ></b-form-select>
      </b-form-group>
          <b-form-group id="input-group-5" :label="$t('main.extension')" label-for="input-5">
        <b-form-select
          id="input-5"
          v-model="form.extension"
          :options="extensions"
        ></b-form-select>
      </b-form-group>
             <b-form-group
        id="input-group-1"
        :label="$t('main.site')"
        label-for="input-1"
        description="nom court du site"
      >
        <b-form-input
          id="input-2"
          v-model="form.site"
          placeholder="Site name.."
        ></b-form-input>
        </b-form-group>
         <b-form-group
        id="input-group-2"
        :label="$t('main.id')"
        label-for="input-2"
        description="lodel ID"
      >
        <b-form-input
          id="input-2"
          v-model="form.id"
          placeholder="ID.."
        ></b-form-input>
      </b-form-group>
      <b-button @click="updateGrid({'offset':0,'source':form.source,'type':form.type,'extension':form.extension,'site':form.site,'id':form.id})"  variant="dark"><i class="fa fa-search fa-fw"></i>{{$t('main.search')}}</b-button>
     </div>
       </b-form>
     
               <SimpleGrid  :columns="['source','type','description','resource.site','resource.id','fileinfo.filepath','fileinfo.extension','message']" 
                :columnsToTranslate="{'valid':'main'}"
                :limit="8" 
                :dataUrl="apiUrl"
                />
      
      

 
  </div>
</template>
<script>
// @ is an alias to /src
import SimpleGrid from '@/components/SimpleGrid.vue'
import { EventBus }  from '@/event-bus.js'
import axios from 'axios'

export default {
  name: 'errors',
  components: {
    SimpleGrid
  },
  props: {
      archiveId: {type: String,default: ''}
  },
  data() {
      return {
        form: {
          source: null,
          type: null,
          extension: null,
          site: null,
          id: null
        },
        
        show: true
      }
    },
  computed: {
       sources: function() {
           return [{ text: this.$t('main.all'), value: null }, { text: 'Open Edition', value: 'Open Edition' }, { text: 'Facile', value: 'Facile' }]
       } ,
       types: function() {
           return [{ text: this.$t('main.all'), value: null }, { text: 'Validation', value: 'Validation' }, { text: 'Sip', value: 'sip' },{ text: 'Http', value: 'Http' }]
       } ,
       extensions: function() {
           return [{ text: this.$t('main.all'), value: null }, { text: 'Xml', value: 'xml' },
               { text: 'Jpg', value: 'jpg' }, { text: 'Png', value: 'png' }, { text: 'Gif', value: 'gif' }, { text: 'Pdf', value: 'pdf' }]
       } ,
       apiUrl: function(){
           if(this.$route.params.archiveId !== undefined){
               return '/api/errors/' + this.$route.params.archiveId ;
           }
           return '/api/errors';
       }
    },
    methods:{
        updateGrid: function(params){
          EventBus.$emit('update-grid',params) ;
      }
    }
}

</script>