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
              :options="factoryList"
              v-model="assignment.factory_id"
              @search="onSearch"
              :filterable="false"
              :reduce="factory => factory.code"
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
          <b-form-group label="Số phiếu">
            <b-form-input
              v-model="assignment.number"
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
              v-model="assignment.date"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <thead>
              <tr>
                <th>STT</th>
                <th>LSX</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Tiến độ</th>
                <th>Ghi chú</th>
              </tr>
            </thead>
            <b-tbody>
              <b-tr
                v-for="(row, index) in assignment.assignment_details"
                :key="row.index"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ row.manufacturer_order_number }}</td>
                <td>{{ row.code }}</td>
                <td>
                  <v-select
                    :options="productList"
                    @search="onSearchProduct"
                    :filterable="false"
                    @input="product => updateProduct(row, product)"
                    name="product_id"
                    :value="row.product_name"
                  >
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!row.product_id"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                    <template v-slot:no-options>
                      Chọn sản phẩm
                    </template>
                    <template v-slot:option="option">
                      <div class="container-fluid">
                        <div class="row">
                          <div class="col-md-2">
                            {{ option.number }}
                          </div>
                          <div class="col-md-8" style="white-space:normal;">
                            {{ option.label }}
                          </div>
                          <div class="col-md-2">
                            {{ option.quantity }}
                          </div>
                        </div>
                      </div>
                    </template>
                  </v-select>
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
                <td v-if="count !== 1">
                  <b-button variant="success" @click="deleteRow(index)">
                    <i class="fa fa-minus"></i>
                  </b-button>
                </td>
              </b-tr>
            </b-tbody>
          </table>
          <b-button variant="primary" @click.prevent="addRow"
            >Thêm dòng</b-button
          >
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

    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();

    this.assignment.date = dd + "/" + mm + "/" + yyyy;
  },
  data() {
    return {
      assignment: {
        factory_id: "",
        number: "",
        date: "",
        assignment_details: [
          {
            id: "",
            manufacturer_order_number: "",
            product_id: "",
            product_code: "",
            product_name: "",
            quantity: null,
            deadline: null,
            note: ""
          }
        ]
      },
      newItem: {
        id: "",
        manufacturer_order_number: "",
        product_id: "",
        product_code: "",
        product_name: "",
        quantity: null,
        deadline: null,
        note: ""
      },
      factoryList: [],
      productList: []
    };
  },
  computed: {
    count() {
      return this.assignment.assignment_details.length;
    }
  },
  methods: {
    addRow() {
      this.assignment.assignment_details.push({ ...this.newItem });
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
      this.assignment.assignment_details.splice(index, 1);
    },
    getFactoryList(vm) {
      axios.get("api/factories").then(res => {
        vm.options = _.map(res.data.data, value => {
          return { label: value.name, code: value.id };
        });
      });
    },
    onSearchProduct(search, loading) {
      loading(true);
      this.searchProduct(loading, search, this);
    },
    searchProduct: _.debounce((loading, search, vm) => {
      axios
        .get(`api/manufacturer-orders/search?q=${encodeURI(search)}`)
        .then(res => {
          vm.productList = _.map(res.data, value => {
            return {
              label: value.name,
              id: value.id,
              code: value.code,
              quantity: value.quantity,
              number: value.number
            };
          });
          loading(false);
        });
    }, 350),
    updateProduct(row, product) {
      row.price_id = product.id;
      row.product_name = product.label;
      row.product_code = product.code;
    }
  }
};
</script>

<style src="./Create.scss" lang="scss" scoped />
