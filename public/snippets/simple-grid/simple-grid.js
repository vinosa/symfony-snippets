/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

// register the grid component
 
Vue.component('simple-grid', {
  template: `
   <div class="grid-container">    
  <table>
     
      <thead>
      <tr >
        <th   v-for="(key,index) in columns"
        @click="sortBy(key)"
         class="tableHeader" scope="col"  >
          
          {{ $t('main.' + key ) | capitalize  }} 
           <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
          </span>
        </th>
      </tr>
    </thead>
    
    <tbody>
      <tr class="row100 body" v-for="entry in filteredData">
        <td  v-for="(key,index) in columns"
        :class="'cell100 column' + index ">
    <div class="scrollable">   
        <template v-if="columnsToTranslate[key]">
          {{$t(columnsToTranslate[key] + '.' + entry[key] )}} 
        </template>
        <template v-else-if="isJson(entry[key])">
            <button type="button" v-on:click="eventBus.$emit('show-modal',{body:entry[key],modalClass:'modal-lg'})" class="btn btn-info">{{$t('button.view')}}</button>    
        </template>
        <template v-else>
            {{entry[key]}} 
        </template>
    </div>
        </td>
        <buttons-cell :entry="entry" v-if="showButtons" ></buttons-cell>
      </tr>
    </tbody>
    </table>
    <pagination :current-page="currentPage"
                :total-items="totalItems"
                :items-per-page="limit"
                @limit-changed="changeLimit"
                @page-changed="changePage">
    </pagination>
 </div>
`,
    props: {
    columns: Array,
    filterKey: String,
    archiveId: String,
    limit: Number,
    dataUrl: String,
    showButtons: {type:Boolean,default:false},
    columnsToTranslate: {type:Object, default: () => ({})}
  },
   data: function () {
    var sortOrders = {}
    this.columns.forEach(function (key) {
      sortOrders[key] = 1
    })
    return {
       data: [],
       archive : '',
       sortKey: '',
       sortOrders: sortOrders,
       offset:0,
       currentPage: 1,
       totalItems: 0,
       urlParams: {}
    }
  },
  
  computed: {
    filteredData: function () {
      var sortKey = this.sortKey
      var filterKey = this.filterKey && this.filterKey.toLowerCase()
      var order = this.sortOrders[sortKey] || 1
      var data = this.data
      if (filterKey) {
        data = data.filter(function (row) {
          return Object.keys(row).some(function (key) {
            return String(row[key]).toLowerCase().indexOf(filterKey) > -1
          })
        })
      }
      if (sortKey) {
        data = data.slice().sort(function (a, b) {
          a = a[sortKey]
          b = b[sortKey]
          return (a === b ? 0 : a > b ? 1 : -1) * order
        })
      }
      return data
    }
  },
  filters: {
    capitalize: function (str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    }
  },
  methods: {
    sortBy: function (key) {
      this.sortKey = key
      this.sortOrders[key] = this.sortOrders[key] * -1
    },
    
    switchGrids: function(params){
        this.eventBus.$emit('event-show-grid',params.grid,1)
    },
    updateData: function(params){
        var urlParamsArr = [];
        var url = this.dataUrl ;
        this.urlParams.limit = this.limit;
        this.urlParams.offset = this.offset;
        for (var param in params) {
            this.urlParams[param] = params[param];
        }
        for (var param in this.urlParams) {
            if(this.urlParams[param] !== ''){
                urlParamsArr.push(param + '=' + this.urlParams[param]) ;
            }
        }
        if(urlParamsArr.length > 0){
            url += '?' + urlParamsArr.join('&')
        }
        axios.get(url).then(response => {
           this.data = response.data.items
           this.totalItems = response.data.count
        })
        
    },
    changePage: function(page){
        this.offset = (page - 1)* this.limit;
        this.updateData();
        this.currentPage = page;
    },
    changeLimit(limit){
        this.limit = limit;
        this.offset = 0;
        this.updateData();
        this.currentPage = 1;
    },
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
  },
  created: function(){  
     this.updateData() 
     this.eventBus.$on("update-grid",this.updateData );
  },
})


