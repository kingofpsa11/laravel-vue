<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Đơn hàng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Đơn hàng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form>
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng">
            <b-form-input
              v-model="form.customer"
              type="text"
              required
              placeholder="Nhập khách hàng"
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số đơn hàng">
            <b-form-input
              v-model="form.number"
              type="number"
              required
              placeholder="Nhập số đơn hàng"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày đặt hàng">
            <b-form-input
              v-model="form.date"
              type="date"
              required
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Giá trị đơn hàng">
            <b-form-input v-model="form.totalValue" type="text"></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th class="hidden-sm-down">STT</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th class="hidden-sm-down">Số lượng</th>
                <th class="hidden-sm-down">Đơn giá</th>
                <th class="hidden-sm-down">Tiến độ</th>
                <th class="hidden-sm-down">ĐVSX</th>
                <th class="hidden-sm-down">Ghi chú</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="row in form.details" :key="row.id">
                <td>{{ row.id }}</td>
                <td>{{ row.code }}</td>
                <td>
                  <b-form-input
                    v-model="row.product"
                    type="text"
                    required
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    v-model="row.quantity"
                    type="text"
                    required
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    v-model="row.price"
                    type="text"
                    readonly
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    v-model="row.date"
                    type="date"
                    required
                  ></b-form-input>
                </td>
                <td class="width-150">
                  <b-form-input
                    v-model="row.supplier"
                    type="text"
                    readonly
                  ></b-form-input>
                </td>
                <td>
                  <b-form-input
                    v-model="row.note"
                    type="text"
                    readonly
                  ></b-form-input>
                </td>
                <td>
                  <b-button variant="success">
                    <i class="fa fa-minus"></i>
                  </b-button>
                </td>
              </tr>
            </tbody>
          </table>
          <b-button variant="primary" @click="addRow">Thêm dòng</b-button>
          <b-button type="submit" variant="success">Lưu</b-button>
          <b-button type="reset" variant="danger">Huỷ</b-button>
        </b-col>
      </b-row>
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
      form: {
        customer: "",
        number: "",
        details: [
          {
            code: "",
            product: "",
            quantity: 0,
            price: 0,
            date: "",
            supplier: "",
            note: ""
          }
        ]
      },
      newItem: {
        code: "",
        product: "",
        quantity: 0,
        price: 0,
        date: "",
        supplier: "",
        note: ""
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
    addRow(e) {
      e.preventDefault();
      this.form.details.push(this.newItem);
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
