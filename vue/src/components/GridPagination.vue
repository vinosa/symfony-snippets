<template>
<div class="pagination" v-if="totalItems > itemsPerPage">
    <a href="#" @click.prevent="pageChanged(1)" aria-label="Previous" :class="activePage(1)">
      {{$t('pagination.first')}}
    </a>
    <a v-for="n in paginationRange" :class="activePage(n)" href="#" @click.prevent="pageChanged(n)">{{ n }}</a>
    <a href="#" @click.prevent="pageChanged(lastPage)" aria-label="Next" :class="activePage(lastPage)">
      {{$t('pagination.last')}}
    </a>
    <span class="per-page">
     {{$t('pagination.per_page')}}
    <select v-model="itemsPerPage" v-on:change="limitChanged()" class="select-gradient">
            <option v-for="items in [2,5,8,10,20]"  :value="items">{{ items}}</option>
        </select>
    Total: {{totalItems}}
    </span>
</div>
</template>
<script>
 export default {
  name: 'GridPagination',
    props: {

    // Current Page
    currentPage: {
      type: Number,
      required: true
    },

    // Total number of pages
    totalPages: Number,

    // Items per page
    itemsPerPage: Number,

    // Total items
    totalItems: Number,

    // Visible Pages
    visiblePages: {
      type: Number,
      default: 5,
      coerce: (val) => parseInt(val)
    }
  },
  computed: {
    lastPage () {
      if (this.totalPages) {
        return this.totalPages
      } else {
        return this.totalItems % this.itemsPerPage === 0
          ? this.totalItems / this.itemsPerPage
          : Math.floor(this.totalItems / this.itemsPerPage) + 1 ;

      }
    },

    paginationRange () {
        let start =
    this.currentPage - this.visiblePages / 2 <= 0
    ? 1 : this.currentPage + this.visiblePages / 2 > this.lastPage
    ? this.lowerBound(this.lastPage - this.visiblePages + 1, 1)
    : Math.ceil(this.currentPage - this.visiblePages / 2)

  let range = []

  for (let i = 0; i < this.visiblePages && i < this.lastPage; i++) {
    range.push(start + i)
  }

  return range
    }
}    ,
    data: function () {
     return {
      choiceItemsPerPage: [5,8,10,20]
    }
   },
  methods: {
        activePage (pageNum) {
            return this.currentPage === pageNum ? 'page active' : 'page gradient'
        },
        pageChanged (pageNum) {
            this.$emit('page-changed', pageNum)
        },
        lowerBound (num, limit) {
            return num >= limit ? num : limit
        },
        limitChanged() {
            this.$emit('limit-changed', this.itemsPerPage)
        }
  }

}
</script>
<style>
    .pagination {
    background: #f2f2f2;
    padding: 20px;
    margin-bottom: 20px;
    font: 14px/24px sans-serif;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    justify-content: center;
}
.centered-content {
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    /* align-items: center; */
    
}
.page {
    display: inline-block;
    padding: 0px 9px;
    margin-right: 4px;
    border-radius: 3px;
    border: solid 1px #c0c0c0;
    background: #e9e9e9;
    box-shadow: inset 0px 1px 0px rgba(255,255,255, .8), 0px 1px 3px rgba(0,0,0, .1);
    font-size: .875em;
    font-weight: bold;
    text-decoration: none;
    color: #717171;
    text-shadow: 0px 1px 0px rgba(255,255,255, 1);
}

.page:hover, .page.gradient:hover {
    background: #fefefe;
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#FEFEFE), to(#f0f0f0));
    background: -moz-linear-gradient(0% 0% 270deg,#FEFEFE, #f0f0f0);
}

.page.active {
    border: none;
    background: #616161;
    box-shadow: inset 0px 0px 8px rgba(0,0,0, .5), 0px 1px 0px rgba(255,255,255, .8);
    color: #f0f0f0;
    text-shadow: 0px 0px 3px rgba(0,0,0, .5);
}

.page.gradient {
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#f8f8f8), to(#e9e9e9));
    background: -moz-linear-gradient(0% 0% 270deg,#f8f8f8, #e9e9e9);
}
.select-gradient {
    display: inline-block;
    padding: 5px 5px;
    margin-right: 4px;
    border: solid 1px #c0c0c0;
    background: #e9e9e9;
    box-shadow: inset 0px 1px 0px rgba(255,255,255, .8), 0px 1px 3px rgba(0,0,0, .1);
    font-size: .875em;
    font-weight: bold;
    text-decoration: none;
    color: #717171;
    text-shadow: 0px 1px 0px rgba(255,255,255, 1);
}
.per-page {
    color: #717171;
    text-shadow: 0px 1px 0px rgba(255,255,255, 1);
    font-size: .875em;
    justify-content: right;
}

.pagination.dark {
    background: #414449;
    color: #feffff;
}

.page.dark {
    border: solid 1px #32373b;
    background: #3e4347;
    box-shadow: inset 0px 1px 1px rgba(255,255,255, .1), 0px 1px 3px rgba(0,0,0, .1);
    color: #feffff;
    text-shadow: 0px 1px 0px rgba(0,0,0, .5);
}

.page.dark:hover, .page.dark.gradient:hover {
    background: #3d4f5d;
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#547085), to(#3d4f5d));
    background: -moz-linear-gradient(0% 0% 270deg,#547085, #3d4f5d);
}

.page.dark.active {
    border: none;
    background: #2f3237;
    box-shadow: inset 0px 0px 8px rgba(0,0,0, .5), 0px 1px 0px rgba(255,255,255, .1);
}

.page.dark.gradient {
    background: -webkit-gradient(linear, 0% 0%, 0% 100%, from(#565b5f), to(#3e4347));
    background: -moz-linear-gradient(0% 0% 270deg,#565b5f, #3e4347);
}
</style>