<template>
  <div class="home">
      <div class="container">
        <b-form v-if="show"  class="form-inline form-search-archive">
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
      
     </div>
             </b-form>
      </div>
      
</div>
</template>
<script>
// @ is an alias to /src
import SimpleGrid from '@/components/SimpleGrid.vue'
import { EventBus }  from '@/event-bus.js'
import axios from 'axios'

export default {
  name: 'new',
  data() {
      return {
        form: {
          platform: null,
          site: null,
          id: null,
        },      
        show: true
      }
    },
  methods:{
      addArchive: function(platform,site,id){
            axios.post("/api/archive",{platform: this.form.platform,site:this.form.site,id:this.form.id}).then(response => {
                var text = this.$t(response.data.message);                                   
                this.resetForm()
                this.$bvModal.msgBoxOk(text).then(value => {
                    this.resetForm()
                    this.$router.push('/')
                })
                .catch(err => {
                    // An error occurred
                })
                this.$router.push('home')
        },(error) => {
          var text = this.$t(error.response.data.message);
          this.$bvModal.msgBoxOk(text).then(value => {
            this.resetForm()
           // this.$router.push('/')
          })
          .catch(err => {
            // An error occurred
          })
          
        })
         
      },
      resetForm: function(){
          this.form.id = '';
          this.form.site = '';
          this.form.platform = null;
          // Trick to reset/clear native browser form validation state
          this.show = false
          this.$nextTick(() => {
              this.show = true
          }) 
      },
  },
  computed: {
       platforms: function() {
           return [{ text: 'Select One', value: null }, { text: this.$t('main.OB'), value: 'OB' }, { text: this.$t('main.OJ'), value: 'OJ' }]
       }
   }
  }
</script>