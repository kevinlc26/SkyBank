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
import DetalleEmpleado from './views/DetalleEmpleado.vue'


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
  { path: '/detalle-empleado', component: DetalleEmpleado, name: 'detalle-empleado', 
            props: route => ({
            identificador: route.query.identificador,
            tableName: route.query.tableName,
            datos: route.query.datos ? JSON.parse(route.query.datos) : null,
          })
  },

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
