import Vue from 'vue';
import Clock from 'vue-digital-clock';
import SelectTariff from './SelectTariff.vue';
import SelectTyve from './SelectTyve.vue';
import SelectSeat from './SelectSeat.vue';
import SelectRoles from './SelectRol.vue';
import moment from 'moment';
import momentz from 'moment-timezone';
import Datepicker from 'vuejs-datepicker';
import $ from 'jquery';
import 'datatables.net';
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
    Datepicker
  },
  data: {
    tarifas:[],
    reportDate : {
        Date1:'',
        Date2:'',
    },
    params:[],
    tipovehiculo:[],
    sedes:[],
    movimientos:[],
    movimiento:[],
    roles:[],
    _token:'',
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
  watch: {
    'salida.ctarifa':function(){
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
      this.salida.placa = this.slugify(this.salida.placa
        )
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
    saveParams(){
        this._token = $('form').find("input").val()
        var params = $('form').serialize()
        console.log(params)    
        fetch("/movimientos/params",{
          credentials: 'include',
          method : "POST",
          type: "POST",
          headers: {
            'Accept': 'application/json, application/xml, text/plain, text/html, *.*',
            'Content-Type': 'application/x-www-form-urlencoded; charset=utf-8',
            'X-CSRF-TOKEN' : this._token,
          },
          body: params,
        })
        .then(response => {
          return response.json();
        })
        .then(response => {
          alertify.success('Guardado Exitoso')
        })
        .catch(function(error) {
          alertify.error('Error al guardar los parametros')
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
    getParams(){
      fetch("/api/params",{
        credentials: 'include',
        type : "GET",
      })
      .then(response => {
        return response.json()
      }).then(params => {
        this.params = params
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
        this.tipovehiculo = tipovehiculo
        this.entrada.ctipov = "1"
        this.salida.ctipov = "1"
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
        this.sedes = sedes
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
        this.roles = roles
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
          this.movimiento = movimiento
          console.log(this.movimiento[0]['cmovi'])
          this.salida.cmovi = this.movimiento[0]['cmovi']
          this.salida.fhentrada = this.movimiento[0]['fhentrada']
          this.salida.ctarifa = this.movimiento[0]['ctarifa']
          this.salida.ctipov = this.movimiento[0]['ctipov']
          this.GenOutTime()
          this.setTime()
          this.setVal(this.movimiento[0]['ctarifa'])
      }); 
    },
    getMovimientos(){
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
      this.movimiento = entrada
      this.salida.cmovi = this.movimiento.cmovi
      this.salida.placa = this.movimiento.placa
      this.salida.fhentrada = this.movimiento.fhentrada
      this.salida.ctarifa = this.movimiento.ctarifa
      this.salida.ctipov = this.movimiento.ctipov
      this.GenOutTime()
      this.setTime()
      this.setVal(this.movimiento.tarifa)
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
      
        this.salida.tiempo = hours + ":" +minutes;
      }else{
        alertify.error("no generada la hora de salida");
      }
    },
    setVal(tarifa){
      var data = {
        "salida" : this.salida,
        "ctarifa" : ctarifa
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
        this.salida.vrtotal = currencyFormat.format(vrpagar.obj)
        this.salida.vrdescuento = currencyFormat.format(0)
      });
    },
    setDescu(){
      if(this.salida.cortesia){
        this.salida.vrpagar = currencyFormat.sToN(this.salida.vrpagar)
        this.salida.vrtotal = currencyFormat.sToN(this.salida.vrtotal)
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
      this.salida.vrtotal = currencyFormat.sToN(this.salida.vrtotal)
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
      $('#table').DataTable({
            "paging": false,
            "ordering": false,
            "searching": false,
            "info": false,
      });
    },
    dataReport(){
      this.reportDate.Date1 = moment(this.reportDate.Date1).format('YYYY-MM-DD')
      this.reportDate.Date2 = moment(this.reportDate.Date2).format('YYYY-MM-DD')
      var url = '/movimientos/list/report/'+this.reportDate.Date1+'/'+this.reportDate.Date2
      window.open(url)
    }
  },
  mounted(){
    this.getSeat()
    this.getTyve()
    this.getTariff()
    this.getParams()
    this.getRoles()
    this.getMovimientos()

  },
})
