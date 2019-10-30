<template>
  <div class="home">
      <div class="container">
        <b-form  v-if="show" class="form-inline form-search-archive">
     <div class="row">	
     <b-form-group id="input-group-3" :label="$t('main.platform')" label-for="input-3">
        <b-form-select
          id="input-3"
          v-model="form.platform"
          :options="platforms"
          required
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
     <b-button @click="addArchive(platformInput,siteInput,idInput)" variant="success"><font-awesome-icon icon="plus"></font-awesome-icon>{{$t('main.add')}}</b-button>
      <b-button @click="updateGrid({'offset':0})"  variant="dark"><font-awesome-icon icon="search"></font-awesome-icon>{{$t('main.search')}}</b-button>
     </div>
            <div class="row">
                <b-form-group id="input-group-4" :label="$t('main.status')" label-for="input-4">
        <b-form-select
          id="input-4"
          v-model="form.status"
          :options="statuses"
        ></b-form-select>
      </b-form-group>
               <b-form-group
        id="input-group-6"
        :label="$t('main.uuid')"
        label-for="input-6"
        description="UUID"
      >
        <b-form-input
          id="input-2"
          v-model="form.uuid"
          placeholder="Uuid.."
        ></b-form-input>
        </b-form-group>
            </div>
       </b-form>
      </div>
    <SimpleGrid  :columns="['resource.platform', 'resource.site','resource.id','version','status_id','uuid']"
        :columnsToTranslate="{}"
        :limit="8" 
        dataUrl="/api/archives" 
        :showButtons="true"  />

  <b-modal id="modal-1" title="BootstrapVue">
    <p class="my-4">Hello from modal!</p>
  </b-modal>
  </div>
    
</template>

<script>
// @ is an alias to /src
import SimpleGrid from '@/components/SimpleGrid.vue'
import { EventBus }  from '@/event-bus.js'
import axios from 'axios'

export default {
  name: 'home',
  components: {
    SimpleGrid
  },
   data() {
      return {
        form: {
          platform: null,
          site: null,
          id: null,
          status: null,
          uuid: null
        },
        
        show: true
      }
    },
    computed: {
       platforms: function() {
           return [{ text: 'Select One', value: null }, { text: this.$t('main.OB'), value: 'OB' }, { text: this.$t('main.OJ'), value: 'OJ' }]
       },
        statuses: function() {
           return [{ text: this.$t('archive_status.queued'), value: 'queued' }, { text: this.$t('archive_status.incomplete'), value: 'incomplete' }
           , { text: this.$t('archive_status.invalid'), value: 'invalid' }, { text: this.$t('archive_status.signalled'), value: 'signalled' }
        , { text: this.$t('archive_status.ready'), value: 'ready' }, { text: this.$t('archive_status.sent'), value: 'sent' }
    , { text: this.$t('archive_status.rejected'), value: 'rejected' }]
       } 
    },
  methods:{
      addArchive: function(platform,site,id){
           var url = "/api/archive" ;
            axios.post(url,{platform: this.form.platform,site:this.form.site,id:this.form.id}).then(response => {
                var text = this.$t(response.data.message);                                   
                this.resetForm()
                this.$bvModal.msgBoxOk(text)  
                this.updateGrid({});
        },(error) => {
          var text = this.$t(error.response.data.message);
          this.$bvModal.msgBoxOk(text)
          this.resetForm()
          this.updateGrid({});
        })
         
      },
      resetForm: function(){
          this.form.id = '';
          this.form.site = '';
          this.form.platform = null;
          this.form.uuid = null;
          this.form.status = null;
          // Trick to reset/clear native browser form validation state
          this.show = false
          this.$nextTick(() => {
              this.show = true
          }) 
          this.updateGrid({});
      },
      updateGrid: function(params){
          var myparams = {"id":this.form.id,"site":this.form.site,"platform":this.form.platform,"status":this.form.status,"uuid":this.form.uuid};
          for (var param in params) {
            myparams[param] = params[param];
           }
          EventBus.$emit('update-grid',myparams) ;
      }
  }
}
</script>

