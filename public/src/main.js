import Vue from 'vue';
import Clock from 'vue-digital-clock';
import SelectTariff from './SelectTariff.vue';
import SelectTyve from './SelectTyve.vue';
import SelectSeat from './SelectSeat.vue';
import moment from 'moment';
import $ from 'jquery';
import 'datatables.net'
window.$ = $;

var app = new Vue({
  el: '#app',
  components: {
    Clock,
    SelectTariff,
    SelectTyve,
    SelectSeat,
  },
  data: {
    tarifas:[],
    tipovehiculo:[],
    sedes:[],
    movimientos:[],
    _token:'',
    entrada:{
      'fhentrada' : '',
      'ctarifa' : '',
      'placa' : '',
      'ctipov' : ''
    },
    salida:{
      'fhsalida' : '',
      'placa' : '',
      'cmovi' : ''
    }
  },
  methods:{
    GenInTime(){
      this.entrada.fhentrada = moment().format('YYYY-MM-DD h:mm:ss a');
    },
    GenOutTime(){
      this.salida.fhsalida = moment().format('YYYY-MM-DD h:mm:ss a');
    },
    CreateEntrada(){
        this._token = $('form').find("input").val()
        var entrada = $.param(this.entrada)
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
          return response.json();

        })
        .then(response => {
          alertify.success('Entrada Exitosa')
          var url = 'entrada/pdf/'+response['obj']['id']
          window.open(url)

        })
        .catch(function(error) {
          alertify.error('Error al crear la entrada')
        })
    },
    CreateSalida(){
        this._token = $('form').find("input").val()
        var salida = $.param(this.salida)
        fetch("/movimientos/salida/create",{
          credentials: 'include',
          method : "POST",
          type: "POST",
          headers: {
            'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
            'X-CSRF-TOKEN' : this._token,
          },
          body: salida,
        })
        .then(response => {  
          return response.json();
        })
        .then(response => {  
          alertify.success('salida Exitosa')        
        })
        .catch(function(error) {  
          alertify.error('Error al crear la salida')
        })
    },
    getTariff(){
      fetch("/api/tarifas",{
        credentials: 'include',
        type : "GET",
      })
      .then(response => {
        return response.json()
      }).then(tarifas => {
        this.tarifas = tarifas
        this.entrada.ctarifa = "1"
      });
    },
    getTyve(){
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
    },
    getSeat(){
      fetch("/api/sedes",{
        credentials: 'include',
        type : "GET",
      })
      .then(response => {
        return response.json()
      }).then(sedes => {
        app.sedes = sedes
      });  
    },
    getMovimientos(){
      fetch("/api/movimientos",{
        credentials: 'include',
        type : "GET",
      })
      .then(response => {
        return response.json()
      }).then(movimientos => {
        app.movimientos = movimientos
      });
    },
    slugify:function(text){
        return text.toString().toLowerCase()
          .replace(/[^a-z0-9-]/gi, '').
          replace(/-+/g, '').
          replace(/^-|-$/g, '');           // Trim - from end of text
    },
    list(){
      var table = $("#table").DataTable({});
      var movimiento = this.movimientos.map(function(movimiento){
        var fhhora = (movimiento.fhentrada).split(' ')
        var fh = fhhora[0] 
        var hora = fhhora[1] 
        var data = [movimiento.placa,fh,hora,movimiento.tarifa.ntarifa,movimiento.tipovehiculo.ntipov,'<button class="btn btn-primary"  @click.native="setPlaca('+movimiento.placa+','+movimiento.cmovi+')">Seleccionar</button>']
        return data
      })
      table.clear()
      table.rows.add(movimiento)
      table.draw()    
    },
    setPlaca:function(placa,cmovi){
      console.log(placa)
      console.log('-')
      console.log(cmovi)
      app.salida.placa = placa
      app.salida.cmovi = cmovi 
    }
  },
  mounted(){
    // var dt = require( 'datatables.net' )();
    this.getSeat()
    this.getTyve()
    this.getTariff()
    this.getMovimientos()
  }
})
