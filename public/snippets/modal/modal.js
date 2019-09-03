/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
Vue.component('modal', {
  template: `
<transition name="modal">
    <div class="modal-mask">
      <div class="modal-wrapper">
        <div class="modal-container"  v-bind:class="[modalclass]" >
          <div class="modal-header2">
              {{$t('modal.header.' + header)}}
          </div>
          <div class="modal-content">
             <template v-if="isJson(body)">
                <div class="well">
                    <tree-view :data="JSON.parse(body)" max-depth="3"></tree-view>
                </div>     
                </template>
                 <template v-else>
                     {{body}} 
                </template>
          </div>
          <div class="modal-footer">
    <button class="btn btn-primary" type="button" @click="$emit('close')">OK</button>
    <button class="btn btn-secondary" type="button" @click="$emit('close')">Cancel</button>              
          </div>
        </div>
      </div>
    </div>
  </transition>
  `,
    props:{
        body: String,
        header: {type:String,default:'default'},
        modalclass: {type: String,default:'modal-sm'}      
    },
    methods: {
        isJson: function(str){
            if(str === null){
                return false;
            }
            if(Number.isInteger(str)){
                return false ;
            }
            try {
                checkedjson = JSON.parse(str)
                return true;
            } catch (e) {
   
            }
            return false;
        }
    }
    
});
