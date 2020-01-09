import Vue from "vue";
import Router from "vue-router";

import Layout from "./components/Layout/Layout";
import Login from "./pages/Login/Login";
import ErrorPage from "./pages/Error/Error";
// Core
import ContractCreate from "./pages/Contracts/Create";
import ContractList from "./pages/Contracts/List";

// Tables
import TablesBasicPage from "./pages/Tables/Basic";

// Maps
import GoogleMapPage from "./pages/Maps/Google";

// Main
import AnalyticsPage from "./pages/Dashboard/Dashboard";

// Charts
import ChartsPage from "./pages/Charts/Charts";

// Ui
import IconsPage from "./pages/Icons/Icons";
import NotificationsPage from "./pages/Notifications/Notifications";

Vue.use(Router);

export default new Router({
  routes: [
    {
      path: "/login",
      name: "Login",
      component: Login
    },
    {
      path: "/error",
      name: "Error",
      component: ErrorPage
    },
    {
      path: "/app",
      name: "Layout",
      component: Layout,
      children: [
        {
          path: "dashboard",
          name: "AnalyticsPage",
          component: AnalyticsPage
        },
        {
          path: "contracts/create",
          name: "Contract",
          component: ContractCreate
        },
        {
          path: "contracts/list",
          name: "Contract",
          component: ContractList
        },
        {
          path: "components/icons",
          name: "IconsPage",
          component: IconsPage
        },
        {
          path: "notifications",
          name: "NotificationsPage",
          component: NotificationsPage
        },
        {
          path: "components/charts",
          name: "ChartsPage",
          component: ChartsPage
        },
        {
          path: "tables",
          name: "TablesBasicPage",
          component: TablesBasicPage
        },
        {
          path: "components/maps",
          name: "GoogleMapPage",
          component: GoogleMapPage
        }
      ]
    }
  ]
});
