<template>
  <div id="app">
     <nav class="nav nav-tabs"> 
      <router-link to="/" v-bind:class="{'nav-link':true, 'active':(this.$route.name === 'home')}">{{ $t('nav.archives') }}</router-link> |
      <router-link to="/new" v-bind:class="{'nav-link':true, 'active':(this.$route.name === 'new')}">{{ $t('nav.new') }}</router-link> |
      <router-link to="/errors" v-bind:class="{'nav-link':true, 'active':(this.$route.name === 'errors')}">{{ $t('nav.errors') }}</router-link>|
       <span class="menu-tab-right">
      <select v-model="$i18n.locale">
            <option v-for="(lang, i) in {'fr':'Français','en':'English'}"  :value="i">{{ lang }}</option>
        </select>
       </span>
    </nav>
      <div class="content"><router-view/></div>
  </div>
</template>

<style>
    body{
        background-color: #eeeeee;
    }
#app {
  font-family: 'Avenir', Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
.content {
     height: 100%;
    padding: 0;
    margin: 2em;
    display: -webkit-box;
    display: -moz-box;
    display: -ms-flexbox;
    display: -webkit-flex;
    display: flex;
    /* align-items: center; */
    justify-content: center;
}
#nav {
  padding: 30px;
}

#nav a {
  font-weight: bold;
  color: #2c3e50;
}

#nav a.router-link-exact-active {
  color: #42b983;
}

nav{
    width: 100%;
    background-color: #424558;
    font-family: sans-serif;
    font-size: 17px;
    margin: 0 0 2em 0;
}
.nav-link {
    color: #fff;
}
nav .menu-item{
    font-size: 20px;
    font-weight: bold;
}
nav > ul > li{
    float: left;
    position: relative;
}
nav > ul::after{
    content: "";
    display: block;
    clear: both;
}
nav a{
    display: inline-block;
    text-decoration: none;
}
nav > ul > li > a{
    /* padding: 20px 30px;*/
    color: #FFF;
}
.nav li:hover a {
    color: #fff ;
    font-weight: bold;
    text-decoration: none;
}
.menu-tab-right {
    float: right;
    padding: 5px;
    color: #FFF;
    text-align: center;
    border-left: 1px dotted #ccc;
}
</style>
