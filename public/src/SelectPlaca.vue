<template>
  <div>
    <multiselect
          v-model="value",
          :placas="placas",
          :close-on-select="true",
          :clear-on-select="false",
          placeholder="Select one",
          label="name",
          track-by="name">
    </multiselect>
  </div>
</template>

<script>
  import Multiselect from 'vue-multiselect'
  export default {
    components: { Multiselect },
    props: {
      items: {type: Array,},
      placas: {type: Array,}
    }
  }
</script>
<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
