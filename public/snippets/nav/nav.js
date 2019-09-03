/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


Vue.component('snippets-nav', {
  name: 'snippets-nav',
  template: `
   <nav class="nav nav-tabs">       
       <router-link to="/" v-bind:class="{'nav-link':true, 'active':(this.$route.name === 'home')}" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
            {{ $t('nav.archives') }}
            </router-link>     
        <router-link to="/errors" v-bind:class="{'nav-link':true, 'active':(this.$route.name === 'errors')}"> {{ $t('nav.file_errors') }}</router-link>
    <span class="menu-tab-right">  
    <select v-model="$i18n.locale">
            <option v-for="(lang, i) in langues"  :value="i">{{ lang }}</option>
        </select>
    </span>
    </nav>
<!--    
<nav >    
    <ul>
            <li class="menu-tab-main">
                <a href="#" class="menu-item">{{$t('nav.title') | capitalize}}</a>
                <ul class="submenu">
                     <li v-for="key in submenuitems">
                        <router-link to="/">{{$t('nav.' + key)}}</router-link>
                    </li>
                </ul>
                
            </li>
     <li class="menu-tab-right">
        <select v-model="$i18n.locale">
            <option v-for="(lang, i) in langues"  :value="i">{{ lang }}</option>
        </select>
    </li>
  
        </ul> 
    </nav> -->
  `,
    data: function () {   
    return {
      langs: ['fr', 'en'] ,
      langues: {'fr':'Français','en':'English'}
    }
  },
     props: {
    submenuitems: Array
  },
  filters: {
    capitalize: function (str) {
      return str.charAt(0).toUpperCase() + str.slice(1)
    }
  },
  methods: {
      emitEvent: function(){
          var params = {};
          this.eventBus.$emit('event-update-archives',params);
      }
  }
  })