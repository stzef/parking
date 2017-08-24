import Vue from 'vue';
import Clock from 'vue-digital-clock';
import SelectTariff from './SelectTariff.vue';
import SelectTyve from './SelectTyve.vue';
import SelectSeat from './SelectSeat.vue';
import SelectRoles from './SelectRol.vue';
import moment from 'moment';
import momentz from 'moment-timezone';
import $ from 'jquery';
import 'datatables.net'
window.$ = $;

var app = new Vue({
  el: '#app',
  delimiters : ['[[', ']]'],
  components: {
    Clock,
    SelectTariff,
    SelectTyve,
    SelectSeat,
    SelectRoles,
  },
  data: {
    tarifas:[],
    tipovehiculo:[],
    sedes:[],
    movimientos:[],
    roles:[],
    _token:'',
    constantes:{
      tarifas :{
        'minuto':1,
        'hora':2,
        'dia':3,
      }
    },
    entrada:{
      'fhentrada' : '',
      'ctarifa' : '',
      'placa' : '',
      'ctipov' : ''
    },
    salida:{
      'cmovi' : '',
      'fhsalida' : '',
      'placa' : '',
      'fhentrada' : '',
      'ctarifa' : '',
      'vrpagar' : '',
      'vrdescuento' : '',
      'vrtotal' : '',
      'tiempo' : '',
      'ctipov' : '',
      'cortesia': ''
    }
  },
  methods:{
    GenInTime(){
      this.entrada.fhentrada = momentz.tz(moment(), "America/Bogota").format('YYYY-MM-DD HH:mm:ss');
      this.entrada.placa = this.slugify(this.entrada.placa)
    },
    GenOutTime(){
      this.salida.fhsalida = momentz.tz(moment(), "America/Bogota").format('YYYY-MM-DD HH:mm:ss');
      this.setTime()
      this.salida.placa = this.slugify(this.salida.placa)
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
          this.entrada.fhentrada = ''
          this.entrada.ctarifa = 1
          this.entrada.placa = ''
          this.entrada.ctipov = 1
          
          var url = 'entrada/ticket/'+response['obj']['cmovi']
          window.open(url)

        })
        .catch(function(error) {
          alertify.error('Error al crear la entrada')
        })
    },
    CreateSalida(){
        this._token = $('form').find("input").val()
        if (this.salida.cortesia){
          this.salida.cortesia = 1
        }else{
          this.salida.cortesia = ''
        }
        this.salida.vrpagar = currencyFormat.sToN(this.salida.vrpagar)
        this.salida.vrdescuento = currencyFormat.sToN(this.salida.vrdescuento)
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
          $("#"+this.salida.cmovi).attr("hidden","false");
          var url = 'salida/ticket/'+this.salida.cmovi
          this.salida.placa = ''
          this.salida.cmovi = ''
          this.salida.vrpagar = ''
          this.salida.vrdescuento = ''
          this.salida.vrtotal = ''
          this.salida.tiempo = ''
          this.salida.cortesia = false
          this.salida.fhsalida = ''
          this.salida.fhentrada = ''
          this.salida.ctarifa = 1
          this.salida.ctipov = 1
          window.open(url)        
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
        this.salida.ctarifa = "1"
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
        app.salida.ctipov = "1"
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
    getRoles(){
      fetch("/api/roles",{
        credentials: 'include',
        type : "GET",
      })
      .then(response => {
        return response.json()
      }).then(roles => {
        app.roles = roles
      });  
    },
    getMovimiento(placa){
      this._token = $('form').find("input").val()
      var dat = {
        "placa" : placa
      }     
      var data = $.param(dat)
      fetch("/api/movimientos",{
        credentials: 'include',
        method : "POST",
        type: "POST",
        headers: {
          'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
          'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
          'X-CSRF-TOKEN' : this._token,
        },
        body : data
      })
      .then(response => {
        return response.json()
      }).then(movimiento => {
          this.salida.cmovi = movimiento[0]['cmovi']
          this.salida.fhentrada = movimiento[0]['fhentrada']
          this.salida.ctarifa = movimiento[0]['ctarifa']
          this.salida.ctipov = movimiento[0]['ctipov']
          this.GenOutTime()
          this.setTime()
          this.setVal(movimiento[0]['tarifa'])
      }); 
    },
    getMovimientos(placa){
      this._token = $('form').find("input").val()
      fetch("/api/movimientos",{
        credentials: 'include',
        method : "POST",
        type: "POST",
        headers: {
          'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
          'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
          'X-CSRF-TOKEN' : this._token,
        },
      })
      .then(response => {
        return response.json()
      }).then(movimientos => {
        app.movimientos = movimientos
      });
    },
    slugify(text){
        return text.toString().toLowerCase()
          .replace(/[^a-z0-9-]/gi, '').
          replace(/-+/g, '').
          replace(/^-|-$/g, '');           // Trim - from end of text
    },
    setData(entrada){
      console.log(entrada)
      app.salida.cmovi = entrada.cmovi
      app.salida.placa = entrada.placa
      app.salida.fhentrada = entrada.fhentrada
      app.salida.ctarifa = entrada.ctarifa
      app.salida.ctipov = entrada.ctipov
      this.GenOutTime()
      this.setTime()
      this.setVal(entrada.tarifa)
    },
    setTime(){
      if(app.salida.fhsalida){
      var fecha1 = moment(app.salida.fhentrada);
      var fecha2 = moment(app.salida.fhsalida);
      var duration = moment.duration(fecha2.diff(fecha1))
      var day = duration.days();
      var hour = duration.hours();
      var minute = duration.minutes();
      var days=("0"+ day ).slice(-2)
      var hours=("0"+ hour ).slice(-2)
      var minutes=("0"+ minute ).slice(-2)
      
        app.salida.tiempo = ""+days+ ":" +hours + ":" +minutes;
      }else{
        alertify.error("no generada la hora de salida");
      }
    },
    setVal(tarifa){
      var data = {
        "salida" : this.salida,
        "tarifa" : tarifa
      }
      data = $.param(data)
      fetch("/movimientos/time",{
        credentials: 'include',
        method : "POST",
        type: "POST",
        headers: {
          'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
          'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
          'X-CSRF-TOKEN' : this._token,
        },
        body : data
      })
      .then(response => {
        return response.json()
      }).then(vrpagar => {
        console.log(vrpagar)
        this.salida.vrpagar = currencyFormat.format(vrpagar.obj)
      });
    },
    setDescu(){
      if(this.salida.cortesia){
        this.salida.vrpagar = currencyFormat.sToN(this.salida.vrpagar)
        this.salida.vrdescuento = this.salida.vrpagar
        this.salida.vrtotal = this.salida.vrpagar - this.salida.vrdescuento
        this.salida.vrdescuento = currencyFormat.format(this.salida.vrdescuento)
        this.salida.vrpagar = currencyFormat.format(this.salida.vrpagar)
        this.salida.vrtotal = currencyFormat.format(this.salida.vrtotal)
      }else{
        this.salida.vrdescuento = currencyFormat.format(0)
        this.setValtotal()        
      }
    },
    setValtotal(){
      this.salida.vrpagar = currencyFormat.sToN(this.salida.vrpagar)
      this.salida.vrdescuento = currencyFormat.sToN(this.salida.vrdescuento)
      if(this.salida.vrdescuento > this.salida.vrpagar){
        alertify.error("El descuento no puede ser mayor al valor calculado")
        this.salida.vrtotal = currencyFormat.format(this.salida.vrpagar)
        this.salida.vrpagar = currencyFormat.format(this.salida.vrpagar)
        this.salida.vrdescuento = currencyFormat.format(0)
      }else{
        this.salida.vrtotal = this.salida.vrpagar - this.salida.vrdescuento
        this.salida.vrpagar = currencyFormat.format(this.salida.vrpagar)
        this.salida.vrdescuento = currencyFormat.format(this.salida.vrdescuento)
        this.salida.vrtotal = currencyFormat.format(this.salida.vrtotal)
      }
    },
    list(){
      $('#table').DataTable().destroy();
      $('#table').DataTable();
    }
  },
  mounted(){
    this.getSeat()
    this.getTyve()
    this.getTariff()
    this.getRoles()
    this.getMovimientos()

  },
})
