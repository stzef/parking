<template>
      <select :name="id" :id="id" class="form-control" :required="required">
        <option selected="" value="">Seleccione...</option>
        <template v-for="sede in sedes">
        	<option :value="sede.id">{{sede.nombre}}</option>
        </template>
      </select>        
</template>
<script>
export default {
  name: 'select-seat',
  props: {
    name: {type : String,},
    id: {type : String,},
    required: {type : String,},
    sedes: {type: Array,}
  },
  mounted (){this.$emit("mounted")}
}
</script>
<style></style>