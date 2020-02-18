<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu xuất kho</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu xuất kho - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng" name="customer">
            <v-select
              :options="customerList"
              :selectOnTab="true"
              :value="good_delivery.customer_name"
              @input="customer => updateCustomer(customer)"
              @search="onSearchCustomer"
              :filterable="false"
            >
              <template v-slot:no-options>
                Nhập tên khách hàng
              </template>
            </v-select>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col md="4">
          <b-form-group label="Số phiếu">
            <b-form-input
              v-model="good_delivery.number"
              type="number"
              required
              placeholder="Nhập số phiếu"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày lập">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              v-model="good_delivery.date"
            />
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <b-thead>
              <b-tr>
                <th>STT</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Số lượng</th>
                <th>Kho</th>
              </b-tr>
            </b-thead>
            <b-tbody>
              <b-tr
                v-for="(row, index) in good_delivery.good_delivery_details"
                :key="row.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  <v-select
                    :options="productList"
                    :selectOnTab="true"
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
                  </v-select>
                </td>
                <td>
                  <b-form-input
                    v-model="row.quantity"
                    type="number"
                    required
                  ></b-form-input>
                </td>
                <td>
                  <v-select
                    :options="storeList"
                    :selectOnTab="true"
                    @search="onSearchStore"
                    :filterable="false"
                    name="store_id"
                    :value="row.store_name"
                    @input="store => updateStore(row, store)"
                    label="name"
                  >
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!row.store_id"
                        v-bind="attributes"
                        v-on="events"
                      />
                    </template>
                    <template v-slot:no-options>
                      Chọn sản phẩm
                    </template>
                  </v-select>
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

export default {
  data() {
    return {
      good_delivery: {
        customer_id: "",
        number: "",
        good_delivery_details: [
          {
            product_code: "",
            product_name: "",
            product_id: "",
            quantity: null,
            store_id: ""
          }
        ]
      },
      newItem: {
        product_code: "",
        product_name: "",
        product_id: "",
        quantity: null,
        store_id: ""
      },
      customerList: [],
      productList: [],
      storeList: []
    };
  },
  created() {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();

    this.good_delivery.date = dd + "/" + mm + "/" + yyyy;

    if (this.$route.params.id) {
      this.getGoodDeliveries(this.$route.params.id);
    }
  },
  computed: {
    count() {
      return this.good_delivery.good_delivery_details.length;
    }
  },
  methods: {
    getGoodDeliveries(id) {
      axios.get(`api/good-deliveries/${id}`).then(res => {
        this.good_delivery = res.data.data;
      });
    },
    addRow() {
      this.good_delivery.good_delivery_details.push({ ...this.newItem });
    },
    onSubmit() {
      axios
        .post("/api/good-deliveries", this.good_delivery)
        .then(res => {
          if (res.data.status === "success")
            this.$router.push("/good-deliveries/" + res.data.id);
        })
        .catch(error => {
          console.log(this.good_delivery);
        });
    },
    onSearchCustomer(search, loading) {
      loading(true);
      this.searchCustomer(loading, search, this);
    },
    searchCustomer: _.debounce((loading, search, vm) => {
      axios.get(`api/customers/search?q=${encodeURI(search)}`).then(res => {
        vm.customerList = _.map(res.data.data, value => {
          return { label: value.name, code: value.id };
        });
        loading(false);
      });
    }, 350),
    onSearchProduct(search, loading) {
      loading(true);
      this.searchProduct(loading, search, this);
    },
    searchProduct: _.debounce((loading, search, vm) => {
      axios
        .get(
          `api/products/search?q=${encodeURI(search)}&customer_id=${
            vm.good_delivery.customer_id
          }`
        )
        .then(res => {
          vm.productList = _.map(res.data, value => {
            return {
              label: value.name,
              id: value.id,
              code: value.code
            };
          });
          loading(false);
        });
    }, 350),
    onSearchStore(search, loading) {
      loading(true);
      this.searchStore(loading, search, this);
    },
    searchStore: _.debounce((loading, search, vm) => {
      axios.get(`api/stores/search?q=${encodeURI(search)}`).then(res => {
        // vm.storeList = _.map(res.data.data, value => {
        //   return { label: value.name, code: value.id };
        // });
        vm.storeList = res.data;
        loading(false);
      });
    }, 350),
    updateCustomer(customer) {
      this.good_delivery.customer_name = customer.label;
      this.good_delivery.customer_id = customer.code;
    },
    updateProduct(row, product) {
      row.product_id = product.id;
      row.product_name = product.label;
      row.product_code = product.code;
    },
    updateStore(row, store) {
      row.store_id = store.id;
      row.store_name = store.name;
    },
    deleteRow(index) {
      this.good_delivery.good_delivery_details.splice(index, 1);
    }
  }
};
</script>
<style lang="scss">
[name="product_id"] {
  .vs__dropdown-menu {
    width: 500px;
  }
}
</style>
<style src="./Create.scss" lang="scss" scoped />
