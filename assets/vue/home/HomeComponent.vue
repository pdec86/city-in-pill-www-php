<template>
  <div class="example">Home component: {{ msg }}</div>
</template>

<script>
export default {
  name: "HomeComponent",
  data() {
    return {
      msg: 'Hello world!',
    }
  }
}
</script>

<style scoped>
.example {
  color: red;
}
</style>