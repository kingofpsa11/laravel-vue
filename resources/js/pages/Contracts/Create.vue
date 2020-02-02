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
              v-model="form.customer_id"
              @search="onSearchCustomer"
              :filterable="false"
              :reduce="customer => customer.code"
            >
              <template v-slot:no-options>
                Nhap ten khach hang
              </template>
              <template v-slot:option="option">
                {{ option.label }}
              </template>
              <template v-slot:selected-option="option">
                {{ option.label }}
              </template>
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
            <input
              type="text"
              class="form-control"
              v-mask="{ alias: 'dd/mm/yyyy' }"
              v-model="form.date"
            />
          </b-form-group>
        </b-col>
        <b-col md="4">
          <b-form-group label="Giá trị đơn hàng">
            <b-form-input
              :value="totalValue"
              type="text"
              readonly
            ></b-form-input>
          </b-form-group>
        </b-col>
      </b-row>
      <b-row>
        <b-col>
          <table class="table table-bordered">
            <b-thead>
              <b-tr>
                <th class="hidden-sm-down">STT</th>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th class="hidden-sm-down">Số lượng</th>
                <th class="hidden-sm-down">Đơn giá</th>
                <th class="hidden-sm-down">Tiến độ</th>
                <th class="hidden-sm-down">ĐVSX</th>
                <th class="hidden-sm-down">Ghi chú</th>
              </b-tr>
            </b-thead>

            <b-tbody>
              <b-tr v-for="(row, index) in form.contract_details" :key="row.id">
                <td>{{ index + 1 }}</td>
                <td>{{ row.code }}</td>
                <td>
                  <v-select
                    :options="priceList"
                    @search="onSearchPrice"
                    :filterable="false"
                    @input="price => updatePrice(row, price)"
                    name="price_id"
                    label="row.name"
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
                  <b-form-input
                    v-model="row.selling_price"
                    type="text"
                    readonly
                  ></b-form-input>
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
      form: {
        customer_id: "",
        number: "",
        contract_details: [
          {
            code: "",
            name: "",
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
      ]
    };
  },
  created() {
    if (this.$route.params.id) this.getContracts(this.$route.params.id);
  },
  computed: {
    count() {
      return this.form.contract_details.length;
    },
    totalValue() {
      let totalValue = 0;
      this.form.contract_details.forEach(item => {
        totalValue += item.selling_price * item.quantity;
      });
      return totalValue;
    }
  },
  methods: {
    getContracts(id) {
      axios.get(`api/contracts/${id}`).then(res => {
        this.form = res.data.data;
      });
    },
    addRow() {
      this.form.contract_details.push({ ...this.newItem });
    },
    onSubmit() {
      axios.post("/api/contracts", this.form).then(res => {
        console.log(res.data);
      });
    },
    onSearchCustomer(search, loading) {
      loading(true);
      this.searchCustomer(loading, search, this);
    },
    searchCustomer: _.debounce((loading, search, vm) => {
      axios.get(`api/customers/search?q=${search}`).then(res => {
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
        .get(`api/prices/search?q=${search}&customer_id=${vm.form.customer_id}`)
        .then(res => {
          vm.priceList = _.map(res.data, value => {
            return {
              label: value.name,
              id: value.id,
              price: value.sell_price
            };
          });
          loading(false);
        });
    }, 350),
    updatePrice(row, price) {
      row.price_id = price.id;
      row.selling_price = price.price;
      row.name = price.label;
    },
    deleteRow(index) {
      this.form.contract_details.splice(index, 1);
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
