<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Đơn hàng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Đơn hàng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng">
            <v-select
              :options="options"
              v-model="form.customer_id"
              @search="onSearch"
              :filterable="false"
              :reduce="customer => customer.code"
            >
              <template v-slot:no-options>
                Nhap ten khach hang
              </template>

              <template v-slot:> </template>
            </v-select>
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
            <b-form-input
              v-model="form.totalValue"
              type="text"
              readonly
            ></b-form-input>
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
            <b-tbody>
              <b-tr v-for="(row, index) in form.details" :key="row.id">
                <td>{{ index + 1 }}</td>
                <td>{{ row.code }}</td>
                <td>
                  <b-form-input
                    v-model="row.price_id"
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
                  <b-button
                    variant="success"
                    @click="deleteRow(index)"
                    v-if="count !== 1"
                  >
                    <i class="fa fa-minus"></i>
                  </b-button>
                </td>
              </b-tr>
            </b-tbody>
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
import _ from "lodash";

export default {
  name: "Tables",
  components: { Widget, Sparklines },
  data() {
    return {
      form: {
        customer_id: "",
        number: "",
        details: [
          {
            code: "",
            price_id: "",
            quantity: null,
            price: null,
            date: null,
            supplier: "",
            note: ""
          }
        ]
      },
      newItem: {
        code: null,
        product: null,
        quantity: null,
        price: null,
        date: null,
        supplier: null,
        note: null
      },
      options: []
    };
  },
  computed: {
    count() {
      return this.form.details.length;
    },
    totalValue() {
      let totalValue = 0;
      this.form.details.forEach(item => {
        totalValue += item.price * item.quantity;
      });
      return totalValue;
    }
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
    onSubmit(e) {
      e.preventDefault();
      axios.post("/api/contracts", this.form).then(res => {
        console.log(res.data.result);
      });
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      axios.get(`api/customers/search?q=${search}`).then(res => {
        console.log(this);
        vm.options = _.map(res.data.data, value => {
          return { label: value.name, code: value.id };
        });
        loading(false);
      });
    }, 350),
    deleteRow(index) {
      this.form.details.splice(index, 1);
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
