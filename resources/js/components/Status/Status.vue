<template>
  <b-button variant="warning" v-if="data.status === 10" size="xs"
    >Đang chờ</b-button
  >
  <b-button variant="success" v-else-if="data.status === 0" size="xs"
    >Hoàn thành</b-button
  >
</template>

<script>
export default {
  props: ["data", "index"]
};
</script>
