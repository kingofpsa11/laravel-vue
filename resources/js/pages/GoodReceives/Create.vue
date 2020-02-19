<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Phiếu nhập kho</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Phiếu nhập kho - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Đơn vị giao hàng" name="supplier">
            <v-select
              :options="supplierList"
              :selectOnTab="true"
              :value="good_receive.supplier_name"
              @input="supplier => updateSupplier(supplier)"
              @search="onSearchSupplier"
              :filterable="false"
              label="name"
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
              v-model="good_receive.number"
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
              v-model="good_receive.date"
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
                v-for="(row, index) in good_receive.good_receive_details"
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
      good_receive: {
        supplier_id: "",
        number: "",
        good_receive_details: [
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
      supplierList: [],
      productList: [],
      storeList: []
    };
  },
  created() {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();

    this.good_receive.date = dd + "/" + mm + "/" + yyyy;

    if (this.$route.params.id) {
      this.getGoodReceives(this.$route.params.id);
    }
  },
  computed: {
    count() {
      return this.good_receive.good_receive_details.length;
    }
  },
  methods: {
    getGoodReceives(id) {
      axios.get(`api/good-receives/${id}`).then(res => {
        this.good_receive = res.data.data;
      });
    },
    addRow() {
      this.good_receive.good_receive_details.push({ ...this.newItem });
    },
    onSubmit() {
      axios
        .post("/api/good-receives", this.good_receive)
        .then(res => {
          if (res.data.status === "success")
            this.$router.push("/good-receives/" + res.data.id);
        })
        .catch(error => {
          console.log(this.good_receive);
        });
    },
    onSearchSupplier(search, loading) {
      loading(true);
      this.searchSupplier(loading, search, this);
    },
    searchSupplier: _.debounce((loading, search, vm) => {
      axios.get(`api/suppliers/search?q=${encodeURI(search)}`).then(res => {
        console.log(res);
        vm.supplierList = res.data;
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
          `api/products/search?q=${encodeURI(search)}&supplier_id=${
            vm.good_receive.supplier_id
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
    updateSupplier(supplier) {
      this.good_receive.supplier_name = supplier.name;
      this.good_receive.supplier_id = supplier.id;
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
      this.good_receive.good_receive_details.splice(index, 1);
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
