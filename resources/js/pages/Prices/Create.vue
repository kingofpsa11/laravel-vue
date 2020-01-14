<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Giá sản phẩm</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Giá sản phẩm - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit="createPrice">
      <b-card>
        <b-row>
          <b-col md="6">
            <b-form-group label="Mã sản phẩm">
              <b-form-input
                v-model="price.code"
                type="text"
                readonly
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Tên sản phẩm">
              <b-form-input
                v-model="price.product_id"
                type="text"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Giá bán nội bộ">
              <b-form-input
                v-model="price.selling_price"
                type="number"
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Ngày áp dụng">
              <b-form-input
                v-model="price.effective_date"
                type="date"
              ></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="6">
            <b-form-group label="Ghi chú">
              <b-form-textarea v-model="price.note"></b-form-textarea>
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
import Widget from "../../components/Widget/Widget";
import Sparklines from "../../components/Sparklines/Sparklines";

export default {
  name: "Tables",
  components: { Widget, Sparklines },
  data() {
    return {
      price: {
        status: 10
      }
    };
  },
  methods: {
    parseDate(date) {
      const dateSet = date.toDateString().split(" ");
      return `${date.toLocaleString("en-us", { month: "long" })} ${
        dateSet[2]
      }, ${dateSet[3]}`;
    },
    createPrice(e) {
      e.preventDefault();
      axios
        .post("/api/prices", {
          product_id: this.price.product_id,
          selling_price: this.price.selling_price,
          note: this.price.note,
          status: this.price.status
        })
        .then(response => {
          this.$router.push("/prices/list");
        })
        .catch(error => {
          this.errors = error.response.data.errors.name;
        });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
