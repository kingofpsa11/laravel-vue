<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phân xưởng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phân xưởng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-card>
      <b-row>
        <b-col>
          <b-form-group label="Tên phân xưởng">
            <b-form-input
              v-model="factory.name"
              type="text"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <router-link :to="`/factories/${id}/edit`" class="btn btn-warning"
        >Sửa</router-link
      >
    </b-card>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  created() {
    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.getFactory(this.id);
    }
  },
  data() {
    return {
      id: null,
      factory: {}
    };
  },
  methods: {
    getFactory(id) {
      axios.get(`api/factories/${id}`).then(res => {
        this.factory = res.data;
      });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
