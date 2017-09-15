<template>
      <select v-model="obj.ctarifa" :name="id" :id="id" class="form-control" :required="required">
        <template v-for="tarifa in tarifas">
        	<option :value="tarifa.ctarifa">{{tarifa.ntarifa}} ( $ {{tarifa.vrtarifa}} )</option>
        </template>
      </select>
</template>
<script>
export default {
  name: 'select-tariff',
  props: {
    name: {type : String,},
    id: {type : String,},
    required: {type : String,},
    tarifas: {type: Array,},
    obj: {type: Object,}
  },
  mounted (){
    this.$emit("mounted")
  }
}
</script>
<style></style>
