
<template>
    <div>
    <b-button @click="showModal">Voir</b-button>
    <b-modal id="modal-1" :title="title" ref="my-modal" size="xl" class="modal-content">
      <json-view :data="data" />
   </b-modal>
    </div>
</template>
<script>
    import { JSONView } from "vue-json-component";
    export default {
  name: 'JsonModal',
  components:{
      "json-view": JSONView
    },
    props: {
        data: Object,
        title:{type:String,default:'Default Title'}
    },
    methods: {
      showModal() {
        this.$refs['my-modal'].show()
      },
      hideModal() {
        this.$refs['my-modal'].hide()
      },
      toggleModal() {
        // We pass the ID of the button that we want to return focus to
        // when the modal has hidden
        this.$refs['my-modal'].toggle('#toggle-btn')
      }
    }
  }
</script>
<style>
    .modal-content{
        overflow-y: auto;
    }
</style>