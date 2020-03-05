<template>
  <div class="tables-basic">
    <b-breadcrumb>
      <b-breadcrumb-item>Hapulico</b-breadcrumb-item>
      <b-breadcrumb-item active>Đơn hàng</b-breadcrumb-item>
    </b-breadcrumb>
    <h2 class="page-title">
      Đơn hàng - <span class="fw-semi-bold">Tạo mới</span>
    </h2>
    <b-form @submit.prevent="onSubmit">
      <b-row>
        <b-col>
          <b-form-group label="Khách Hàng" name="customer">
            <v-select
              :options="customerList"
              :selectOnTab="true"
              :value="contract.customer_name"
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
          <b-form-group label="Số đơn hàng">
            <b-form-input
              v-model="contract.number"
              type="number"
              required
              placeholder="Nhập số đơn hàng"
            ></b-form-input>
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Ngày đặt hàng">
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              v-model="contract.date"
            />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Giá trị đơn hàng">
            <input
              type="text"
              :value="totalValue"
              v-mask="currencyFormat"
              class="form-control"
              readonly
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
                <th>Đơn giá</th>
                <th>Tiến độ</th>
                <th>ĐVSX</th>
                <th>Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr
                v-for="(row, index) in contract.contract_details"
                :key="row.id"
              >
                <td>{{ index + 1 }}</td>
                <td>{{ row.product_code }}</td>
                <td>
                  <v-select
                    :options="priceList"
                    :selectOnTab="true"
                    @search="onSearchPrice"
                    :filterable="false"
                    @input="price => updatePrice(row, price)"
                    name="price_id"
                    :value="row.product_name"
                  >
                    <template #search="{attributes, events}">
                      <input
                        class="vs__search"
                        :required="!row.price_id"
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
                    type="text"
                    required
                  ></b-form-input>
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    :value="row.selling_price"
                    v-mask="currencyFormat"
                    readonly
                  />
                </td>
                <td>
                  <input
                    type="text"
                    class="form-control"
                    v-mask="{ alias: 'dd/mm/yyyy' }"
                    v-model="row.deadline"
                    required
                  />
                </td>
                <td class="width-150">
                  <b-form-select
                    v-model="row.supplier_id"
                    :options="supplierList"
                    required
                  ></b-form-select>
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

export default {
  data() {
    return {
      contract: {
        id: null,
        customer_id: "",
        number: "",
        contract_details: [
          {
            product_code: "",
            product_name: "",
            price_id: "",
            quantity: null,
            selling_price: null,
            deadline: null,
            supplier_id: "",
            note: ""
          }
        ]
      },
      newItem: {
        code: "",
        price_id: "",
        quantity: null,
        selling_price: null,
        deadline: null,
        supplier_id: "",
        note: ""
      },
      customerList: [],
      priceList: [],
      supplierList: [
        { value: 74, text: "Litec" },
        { value: 584, text: "Tấn Phát" },
        { value: 994, text: "Nhà máy" }
      ],
      currencyFormat: {
        alias: "integer",
        groupSeparator: ".",
        autoGroup: true,
        removeMaskOnSubmit: true,
        unmaskAsNumber: true
      }
    };
  },
  created() {
    let today = new Date();
    let dd = String(today.getDate()).padStart(2, "0");
    let mm = String(today.getMonth() + 1).padStart(2, "0");
    let yyyy = today.getFullYear();

    this.contract.date = dd + "/" + mm + "/" + yyyy;

    if (this.$route.params.id) {
      this.id = this.$route.params.id;
      this.getContracts(this.id);
    }
  },
  computed: {
    count() {
      return this.contract.contract_details.length;
    },
    totalValue() {
      let totalValue = 0;
      this.contract.contract_details.forEach(item => {
        totalValue += item.selling_price * item.quantity;
      });
      return totalValue;
    }
  },
  methods: {
    getContracts(id) {
      axios.get(`api/contracts/${id}`).then(res => {
        this.contract = res.data.data;
      });
    },
    getNewNumber() {
      axios.get(`api/contracts/getNewNumber/${this.contract.customer_id}`).then(res => {
        console.log(res);
        this.contract.number = res.data;
      });
    },
    addRow() {
      this.contract.contract_details.push({ ...this.newItem });
    },
    onSubmit() {
      let currentRoute = this.$route.path;
      if (currentRoute.includes("edit")) {
        axios
          .put(`/api/contracts/${this.id}`, this.contract)
          .then(res => {
            if (res.data.status === "success")
              this.$router.push("/contracts/" + res.data.id);
          })
          .catch(error => {});
      } else {
        axios
          .post("/api/contracts", this.contract)
          .then(res => {
            if (res.data.status === "success")
              this.$router.push("/contracts/" + res.data.id);
          })
          .catch(error => {});
      }
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
    onSearchPrice(search, loading) {
      loading(true);
      this.searchPrice(loading, search, this);
    },
    searchPrice: _.debounce((loading, search, vm) => {
      axios
        .get(
          `api/prices/search?q=${encodeURI(search)}&customer_id=${
            vm.contract.customer_id
          }`
        )
        .then(res => {
          vm.priceList = _.map(res.data, value => {
            return {
              label: value.name,
              id: value.id,
              price: value.sell_price,
              code: value.code
            };
          });
          loading(false);
        });
    }, 350),
    updateCustomer(customer) {
      this.contract.customer_name = customer.label;
      this.contract.customer_id = customer.code;
      this.getNewNumber();
    },
    updatePrice(row, price) {
      row.price_id = price.id;
      row.selling_price = price.price;
      row.product_name = price.label;
      row.product_code = price.code;
    },
    deleteRow(index) {
      this.contract.contract_details.splice(index, 1);
    }
  }
};
</script>
<style lang="scss">
[name="price_id"] {
  .vs__dropdown-menu {
    width: 500px;
  }
}
</style>
<style src="./Create.scss" lang="scss" scoped />
