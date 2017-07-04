import Vue from 'vue'
import Clock from 'vue-digital-clock'
import moment from 'moment';
new Vue({
  el: '#app',
  components: {
    Clock,
  },
  data: {
  	time: moment().locale('es').format('MMMM Do YYYY, h:mm:ss a'),
    tarifas:[],
    entrada:{
      'fhentrada' : '',
      'tarifa' : '',
      'placa' : '',
      'tipovehiculo' : ''
    }
  }
})
