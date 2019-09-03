/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

Vue.component('pagination', {
  template: `
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
  `,
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
      itemsPerPage: [5,10,20]
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

})
