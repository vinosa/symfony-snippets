<template>

        <td  v-if="entry.status_id == 'invalid' || entry.status_id == 'incomplete' || entry.status_id == 'error' || entry.status_id == 'rejected' || entry.status_id == 'signalled'  " class="well">       
        <div class="btn-group">
            <router-link :to="{ name: 'errorsByArchive', params: { archiveId:  entry.archive_id.toString() } }" tag="button" class="btn btn-warning text-capitalize btn-xs">
                {{ $t('button.errors')   }}
            </router-link>
            <button v-if="entry.status_id !== 'signalled' " type="button" v-on:click="patchJson('/api/archive/' + entry.archive_id.toString(),{status:'signalled'})" class="btn btn-info">{{$t('button.signal')}}</button> 
            <button v-if="entry.status_id == 'signalled' " type="button"  class="btn btn-secondary">{{$t('button.signalled')}}</button>
            <button type="button" v-on:click="patchJson('/api/archive/' + entry.archive_id.toString(),{status:'queued'})" class="btn btn-success">{{$t('button.corrected')}}</button> 
        </div>
</td>
</template>
<script>
    import axios from 'axios'
   import { EventBus }  from '@/event-bus.js'
   
    export default {
  name: 'ButtonsCell',
  props: {
      entry: Object
  },
  methods: {
      patchJson : function(url,json){
            axios.patch(url,json).then(response => {
             this.$bvModal.msgBoxOk(response.data.message) ; 
             EventBus.$emit('update-grid',{}) ;
        },(error) => {
             this.$bvModal.msgBoxOk(error.response.data.message) ;
        })
      }
  }
 }
 </script>
