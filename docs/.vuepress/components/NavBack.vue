<template>
  <span class="prev">
    ← <a href="#" @click="back" class="prev">Back</a>
  </span>
</template>

<script>
export default {
  name: 'NavBack',
  methods: {
    back(){
      history.back(-1)
    }
  }
}
</script>