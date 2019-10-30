/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
  <template>
   <div class="grid-container">    
  <table>
     
      <thead>
      <tr >
        <th   v-for="key in columns"
        @click="sortBy(key)"
         class="tableHeader" scope="col"
         v-bind:key="key"
         >
          
          {{ $t('main.' + key ) | capitalize  }} 
           <span class="arrow" :class="sortOrders[key] > 0 ? 'asc' : 'dsc'">
          </span>
        </th>
      </tr>
    </thead>
    
    <tbody>
      <tr class="row100 body" v-for="entry in filteredData" v-bind:key="entry">
        <td  v-for="(key,index) in columns"
             v-bind:key="key"
        :class="'cell100 column' + index ">
    <div class="scrollable">   
        <template v-if="columnsToTranslate[key]">
          {{$t(columnsToTranslate[key] + '.' + entry[key] )}} 
        </template>
        <template v-else-if="isJson(entry[key])">
            
            <JsonModal :data="JSON.parse(entry[key])" />
        </template>
        <template v-else>
            {{entry[key]}} 
        </template>
    </div>
        </td>
        <ButtonsCell :entry="entry" v-if="showButtons" />
      </tr>
    </tbody>
    </table>
    <GridPagination :current-page="currentPage"
                :total-items="totalItems"
                :items-per-page="limit"
                @limit-changed="changeLimit"
                @page-changed="changePage">
    </GridPagination>
       
  
 </div>
</template>
<script>
    import axios from 'axios'
    import { EventBus }  from '@/event-bus.js'
    import GridPagination  from './GridPagination.vue'
    import JsonModal  from './JsonModal.vue'
    import ButtonsCell  from './ButtonsCell.vue'
    
    export default {
  name: 'SimpleGrid',
  components:{
      GridPagination,JsonModal,ButtonsCell
    },
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
        var param = "";
        for (param in params) {
            this.urlParams[param] = params[param];
        }
        for (param in this.urlParams) {
            if(this.urlParams[param] !== '' && this.urlParams[param] !== undefined && this.urlParams[param] !== null){
                urlParamsArr.push(param + '=' + this.urlParams[param]) ;
            }
        }
        if(urlParamsArr.length > 0){
            url += '?' + urlParamsArr.join('&')
        }
        axios.get(url).then(response => {
           this.data = response.data.items
           this.totalItems = response.data.count
           console.log('total items ' + this.totalItems)
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
            var checkedjson = JSON.parse(str)
            return true;
        } catch (e) {
            console.log("not json")
        }
        return false;
    }
  },
  
  created: function(){ 
     this.updateData() 
     EventBus.$on('update-grid',this.updateData)
  }
}
</script>
<style>
   *, *:before, *:after {
  -moz-box-sizing: border-box;
  -webkit-box-sizing: border-box;
  box-sizing: border-box;
}

body {
  font-family: 'Nunito', sans-serif;
  color: #384047;
}

table {
 /* margin-top: 20px; */
}
/*
th {
    max-width: 250px;
    min-width: 150px;
}*/

caption {
  font-size: 1.6em;
  font-weight: 400;
  padding: 10px 0;
}

thead th {
  font-weight: 400;
  background: #8a97a0;
  color: #FFF;
}

tr {
  background: #f4f7f8;
  border-bottom: 1px solid #FFF;
  margin-bottom: 5px;
}

tr:nth-child(even) {
  background: #e8eeef;
}

th, td {
  text-align: center;
   padding: 10px; 
  font-weight: 300;
   font-family: monospace ;
}
.tableHeader {
   text-align: center; 
}
td {
   margin: 0;
    max-width: 400px; 
    min-width: 100px; 
    text-align: center;
    font-family: monospace ;
    font-size: 0.9em;
    font-weight: 300;
}

tfoot tr {
  background: none;
}

tfoot td {
  padding: 10px 2px;
  font-size: 0.8em;
  font-style: italic;
  color: #8a97a0;
}
th.active {
  color: #fff;
}

th.active .arrow {
  opacity: 1;
}

.arrow {
  display: inline-block;
  vertical-align: middle;
  width: 0;
  height: 0;
  margin-left: 5px;
  opacity: 0.66;
}

.arrow.asc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-bottom: 4px solid #fff;
}

.arrow.dsc {
  border-left: 4px solid transparent;
  border-right: 4px solid transparent;
  border-top: 4px solid #fff;
}
.wrn-btn{
    width: 100%;
    font-size: 16px;
    font-weight: 400;
    text-transform: capitalize;
    height: calc(3rem + 2px) !important;
    border-radius:0;
}

.search-section {
    padding: 1em 0 1em 0;
}
.btn-search {
    background: #424242;border-radius: 0;color: #fff;border-width: 1px;border-style: solid;border-color: #1c1c1c;margin: 0 0 0 1em ;
}
.btn-search:link, .btn-search:visited {
    color: #fff;
}
.btn-search:active, .btn-search:hover {
  background: #1c1c1c;
  color: #fff;
}
form {
    font-family: serif ;
}        
        
label {
    font-size: 18px;
    /*font-weight: bold;*/
    color:black;
    font-family: serif ; 
    margin: 0 10px 2px 10px;
    display: block;
}
.btn-sky {
color: #fff;
background-color: #0bacd3;
border-bottom:2px solid #098aa9;
}

.btn-sky:hover,.btn-sky.active:focus, .btn-sky:focus, .open>.dropdown-toggle.btn-sky {
color: #fff;
background-color: #29b6d8;
border-bottom:2px solid #2192ad;
outline: none;
}

.btn-sky:active, .btn-sky.active {
color: #fff;
background-color: #0a97b9;
border-top:2px solid #087994;
outline-offset: none;
margin-top: 2px;
}

.btn:focus,
.btn:active:focus,
.btn.active:focus {
    outline: none;
    outline-offset: 0px;
}
.btn{
    margin: 4px;
    box-shadow: 1px 1px 5px #888888;
    /* font-family: 'Oswald', sans-serif;*/
}

.btn-xs{
    font-weight: 300;
}

div.scrollable {
    width: 100%;
    /* height: 100%;*/
    max-height: 150px;
    margin: 0;
    padding: 0;
    overflow: auto;
}     
</style>


