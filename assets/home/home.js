import Vue from "vue";
import HomeComponent from "./../vue/home/HomeComponent";

new Vue({
    el: '#app', // where <div id="app"> in your DOM contains the Vue template
    render: h => h(HomeComponent)
});
