/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Vue.component('b-cell', {
  template: `
  <span>
        <span v-if="entry.status_id == 'error' " class="well">
           <td> 
            <router-link :to="{ name: 'errorsByArchive', params: { archiveId:  entry.archive_id.toString() } }" tag="button" class="btn btn-warning text-capitalize btn-xs">
                {{ $t('button.errors')   }}
            </router-link>
            </td>
             <td> <button type="button" v-on:click="patchJson('/api/archive/' + entry.archive_id.toString(),{status:'signalled'})" class="btn btn-info">{{$t('button.signal')}}</button> </td>
            
        </span>
    </span>
`,
    props: {
    entry: {}
  },
  methods: { 
       patchJson : function(url,json){
            axios.patch(url,json).then(response => {
             this.eventBus.$emit('display-modal',{body:response.data.message,header:"success"}) ; 
             this.eventBus.$emit('update-grid',{}) ;
        },(error) => {
             this.eventBus.$emit('display-modal',{body:error.response.data.message,header:"error"}) ;
        })
      }
  }
})