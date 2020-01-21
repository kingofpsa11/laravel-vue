<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu giao việc</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu giao việc - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị">
            <v-select
              :options="options"
              v-model="form.factory_id"
              @search="onSearch"
              :filterable="false"
              :reduce="factory => factory.id"
            >
              <template v-slot:no-options>
                Nhập tên đơn vị
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số lệnh sản xuất">
            <b-form-input v-model="form.order" type="text"></b-form-input>
          </b-form-group> </b-col
        ><b-col md="4">
          <b-form-group label="Số phiếu">
            <b-form-input
              v-model="form.number"
              type="text"
              required
              placeholder="Nhập số phiếu"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày">
            <input
              type="text"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              class="form-control"
              v-model="form.date"
            />
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
                <th class="hidden-sm-down">Tiến độ</th>
                <th class="hidden-sm-down">Ghi chú</th>
              </tr>
            </thead>
            <b-tbody>
              <b-tr v-for="(row, index) in form.details" :key="row.index">
                <td>{{ index + 1 }}</td>
                <td>{{ row.code }}</td>
                <td>
                  <b-form-input
                    v-model="row.product_id"
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
                  <input
                    type="text"
                    v-mask="{ alias: 'dd/mm/yyyy' }"
                    class="form-control"
                    v-model="row.deadline"
                  />
                </td>
                <td>
                  <b-form-input v-model="row.note" type="text"></b-form-input>
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
import _ from "lodash";
import moment from "moment";

export default {
  created() {
    this.getFactoryList(this);
  },
  data() {
    return {
      form: {
        factory_id: "",
        number: "",
        date: "",
        details: [
          {
            code: "",
            product_id: "",
            quantity: null,
            deadline: null,
            note: ""
          }
        ]
      },
      newItem: {
        code: null,
        product_id: null,
        quantity: null,
        date: null,
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
    addRow(e) {
      e.preventDefault();
      this.form.details.push({ ...this.newItem });
    },
    onSubmit() {
      axios.post("/api/assignments", this.form).then(res => {
        console.log(res.data.result);
      });
    },
    onSearch(search, loading) {
      loading(true);
      this.search(loading, search, this);
    },
    search: _.debounce((loading, search, vm) => {
      axios.get(`api/factories/search?q=${search}`).then(res => {
        vm.options = _.map(res.data.data, value => {
          return { label: value.name, code: value.id };
        });
        loading(false);
      });
    }, 350),
    deleteRow(index) {
      this.form.details.splice(index, 1);
    },
    getFactoryList(vm) {
      axios.get("api/factories").then(res => {
        vm.options = _.map(res.data.data, value => {
          return { label: value.name, code: value.id };
        });
      });
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
