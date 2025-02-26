import { createRouter, createWebHistory } from 'vue-router'
import Index from './views/Index.vue'
import Login from './views/Login.vue'
import LoginEmpleado from './views/LoginEmpleado.vue'
import RegisterCliente from './views/RegisterCliente.vue'
import InicioEmpleado from './views/InicioEmpleado.vue'
import PerfilEmpleado from './views/PerfilEmpleado.vue'
import CuentasEmpleado from './views/CuentasEmpleado.vue'
import ClientesEmpleado from './views/ClientesEmpleado.vue'
import TarjetasEmpleado from './views/TarjetasEmpleado.vue'
import TransferenciasEmpleado from './views/TransferenciasEmpleado.vue'
import MovimientosEmpleado from './views/MovimientosEmpleado.vue'
import InicioCliente  from './views/InicioCliente.vue' 
import TarjetasCliente from './views/TarjetasCliente.vue'
import CuentasCliente from './views/CuentasCliente.vue'
import TransferenciasCliente from './views/TransferenciasCliente.vue'
import Contratar from './views/Contratar.vue'
import PerfilCliente from './views/PerfilCliente.vue'
import MiTarjeta from './views/MiTarjeta.vue'

const routes = [
  { path: '/', component: Index },
  { path: '/login-cliente', component: Login },
  { path: '/register-cliente', component: RegisterCliente },
  { path: '/login-empleado', component: LoginEmpleado },
  { path: '/inicio-empleado', component: InicioEmpleado },
  { path: '/perfil-empleado', component: PerfilEmpleado },
  { path: '/cuentas-empleado', component: CuentasEmpleado },
  { path: '/clientes-empleado', component: ClientesEmpleado },
  { path: '/tarjetas-empleado', component: TarjetasEmpleado },
  { path: '/transferencias-empleado', component: TransferenciasEmpleado },
  { path: '/movimientos-empleado', component: MovimientosEmpleado },
  { path: '/inicio-cliente', component: InicioCliente},
  { path: '/tarjetas-cliente', component: TarjetasCliente},
  { path: '/transferencias-cliente', component: TransferenciasCliente},
  { path: '/contratar-cliente', component: Contratar},
  { path: '/perfil-cliente', component: PerfilCliente},
  { path: '/cuentas-cliente', component: CuentasCliente},
  { path: '/miTarjeta', component: MiTarjeta}
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
