<template>
	  <select v-model="entrada.ctipov" :name="id" :id="id" class="form-control" :required="required">
        <template v-for="tipoveh in tipovehiculo">
        	<option :value="tipoveh.ctipov">{{tipoveh.ntipov}}</option>
        </template>
      </select>  
</template>
<script>
export default {
  name: 'select-selecttyve',
  props: {
    name: {type : String,},
    id: {type : String,},
    required: {type : String,},
    tipovehiculo: {type: Array,},
    entrada: {type: Object,}
  },
  mounted (){this.$emit("mounted")}
}
</script>
<style></style>