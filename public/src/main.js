import Vue from 'vue';
import Clock from 'vue-digital-clock';
import SelectTariff from './SelectTariff.vue';
import SelectTyve from './SelectTyve.vue';
import SelectSeat from './SelectSeat.vue';
import SelectPlaca from './SelectPlaca.vue';
import moment from 'moment';
var app = new Vue({
  el: '#app',
  components: {
    Clock,
    SelectTariff,
    SelectTyve,
    SelectSeat,
    SelectPlaca
  },
  data: {
    tarifas:[],
    tipovehiculo:[],
    sedes:[],
    movimientos:[],
    placas:[],
    _token:'',
    entrada:{
      'fhentrada' : '',
      'ctarifa' : '',
      'placa' : '',
      'ctipov' : ''
    },
    salida:{
      'fhsalida' : '',
      'placa' : ''
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
        var placa = []
        for (var i = movimientos.length - 1; i >= 0; i--) {
          if(movimientos[i].ctimovi == 1){
            placa = {"cmovi":movimientos[i].cmovi,"placa":movimientos[i].placa}
            app.placas.push(placa)
          }
        }
        console.log(app.placas)
        app.movimientos = movimientos
      });
    },

  },
  mounted(){
    $("#table").DataTable();
    this.getSeat()
    this.getTyve()
    this.getTariff()
    this.getMovimientos()
  }
})
