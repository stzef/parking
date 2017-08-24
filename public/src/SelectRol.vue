<template>
      <select :name="id" :id="id" class="form-control" :required="required">
        <option selected="" value="">Seleccione...</option>
        <template v-for="rol in roles">
        	<option :value="rol.slug">{{rol.name}}</option>
        </template>
      </select>        
</template>
<script>
export default {
  name: 'select-rol',
  props: {
    name: {type : String,},
    id: {type : String,},
    required: {type : String,},
    roles: {type: Array,}
  },
  mounted (){this.$emit("mounted")}
}
</script>
<style></style>