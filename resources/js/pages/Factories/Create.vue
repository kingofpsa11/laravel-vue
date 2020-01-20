<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Sản phẩm</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Sản phẩm - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit="createProduct">
      <b-card>
        <b-row>
          <b-col md="6">
            <b-form-group label="Nhóm sản phẩm">
              <b-form-select
                v-model="product.category_id"
                required
                :options="categories"
              >
              </b-form-select>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Mã sản phẩm">
              <b-form-input
                v-model="product.code"
                type="text"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Tên sản phẩm">
              <b-form-input
                v-model="product.name"
                type="text"
                required
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Tên sản phẩm hoá đơn">
              <b-form-input
                v-model="product.name_bill"
                type="text"
              ></b-form-input>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Đơn vị tính">
              <b-form-input v-model="product.unit" type="text"></b-form-input>
            </b-form-group>
          </b-col>
        </b-row>
        <b-row>
          <b-col md="6">
            <b-form-group label="Ghi chú">
              <b-form-textarea v-model="product.note"></b-form-textarea>
            </b-form-group>
          </b-col>
          <b-col md="6">
            <b-form-group label="Bản vẽ">
              <b-form-file v-model="product.file"></b-form-file>
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
  created() {
    this.getOptions();
  },
  data() {
    return {
      product: {
        category_id: null,
        code: "",
        unit: "",
        status: 10,
        note: "",
        file: null
      },
      categories: []
    };
  },
  methods: {
    parseDate(date) {
      const dateSet = date.toDateString().split(" ");
      return `${date.toLocaleString("en-us", { month: "long" })} ${
        dateSet[2]
      }, ${dateSet[3]}`;
    },
    addRow(e) {
      e.preventDefault();
      this.form.details.push(this.newItem);
    },
    getOptions() {
      axios
        .get("/api/categories")
        .then(response => {
          this.categories.push({
            value: null,
            text: "Chọn nhóm sản phẩm"
          });
          response.data.data.forEach(category => {
            this.categories.push({
              value: category.id,
              text: category.name
            });
          });
        })
        .catch(error => {
          this.errors = error.response.data.errors.name;
        });
    },
    createProduct(e) {
      e.preventDefault();
      axios
        .post("/api/products", {
          name: this.product.name,
          code: this.product.code,
          category_id: this.product.category_id,
          status: this.product.status,
          note: this.product.note
        })
        .then(response => {
          this.$router.push("/products/list");
        })
        .catch(error => {
          this.errors = error.response.data.errors.name;
        });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
