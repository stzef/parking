import Vue from 'vue';
import Clock from 'vue-digital-clock';
import moment from 'moment';
Vue.component('select-tarifa',{
  props: ['id','required','tarifas','entrada'],
  template:
  `<div class="col-md-12">
      <select v-model="entrada.ctarifa" :name="id" :id="id" class="form-control" :required="required">
        <option v-for="tarifa in tarifas" :value="tarifa.ctarifa">{{tarifa.ntarifa}}</option>
      </select>        
  </div>`,
  created: function(){
    fetch("/api/tarifas",{
      credentials: 'include',
      type : "GET",
    })
    .then(response => {
      return response.json()
    }).then(tarifas => {
      app.tarifas = tarifas
      app.entrada.ctarifa = "1"
    });
  }
})
Vue.component('select-tipovehiculo',{
  props: ['id','required','tipovehiculo','entrada'],
  template:
  `<div class="col-md-12">
      <select v-model="entrada.ctipov" :name="id" :id="id" class="form-control" :required="required">
        <option v-for="tipoveh in tipovehiculo" :value="tipoveh.ctipov">{{tipoveh.detalle}}</option>
      </select>        
  </div>`,
  created: function(){
    fetch("/api/tipovehiculo",{
      credentials: 'include',
      type : "GET",
    })
    .then(response => {
      return response.json()
    }).then(tipovehiculo => {
      app.tipovehiculo = tipovehiculo
      app.entrada.ctipov = "1"
    });
  }
})
var app = new Vue({
  el: '#app',
  components: {
    Clock,
  },
  data: {
    tarifas:[],
    tipovehiculo:[],
    _token:'',
    entrada:{
      'fhentrada' : '',
      'ctarifa' : '',
      'placa' : '',
      'ctipov' : ''
    }
  },
  methods:{
    GenTime:function(){
      this.entrada.fhentrada = moment().format('YYYY-MM-DD h:mm:ss a');
    },
    CreateEntrada:function(){
        this._token = $('form').find("input").val()
        var entrada = $.param(this.entrada)
        console.log(entrada)
        fetch("/movimientos/entrada/create",{
          credentials: 'include',
          method : "POST",
          type: "POST",
          headers: {
            'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
            'X-CSRF-TOKEN' : this._token,
          },
          body: entrada,
        })
        .then(response => {
          console.info(response)
          return response.json();
        })
        .then(response => {
          console.info(response)
          alertify.success('Entrada Exitosa')        
        })
        .catch(function(error) {
          console.warn(error)
          alertify.error('Error al crear la entrada')
        })
      },
  }
})
