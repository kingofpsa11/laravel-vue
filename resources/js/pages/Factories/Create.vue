<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phân xưởng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phân xưởng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="createFactory">
      <b-card>
        <b-row>
          <b-col>
            <b-form-group label="Tên phân xưởng">
              <b-form-input
                v-model="factory.name"
                type="text"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-button type="submit" variant="success">Lưu</b-button>
        <b-button type="reset" variant="danger">Huỷ</b-button>
      </b-card>
    </b-form>
  </div>
</template>

<script>
import Vue from "vue";

export default {
  created() {
    if (this.$route.params.id) {
      this.getFactory(this.$route.params.id);
    }
  },
  data() {
    return {
      factory: {
        name: null
      }
    };
  },
  methods: {
    createFactory() {
      axios
        .post("/api/factories", this.factory)
        .then(res => {
          if (res.data.status === "success") {
            this.$router.push("/factories/" + res.data.id);
          }
        })
        .catch(error => {
          this.errors = error.response.data.errors.name;
        });
    },
    getFactory(id) {
      axios.get(`api/factories/${id}`).then(res => {
        this.factory = res.data;
      });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
