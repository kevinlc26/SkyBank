import { createRouter, createWebHistory } from 'vue-router'
import Index from './views/Index.vue'

import LoginEmpleado from './views/Empleado/LoginEmpleado.vue'
import InicioEmpleado from './views/Empleado/InicioEmpleado.vue'
import PerfilEmpleado from './views/Empleado/PerfilEmpleado.vue'
import CuentasEmpleado from './views/Empleado/CuentasEmpleado.vue'
import ClientesEmpleado from './views/Empleado/ClientesEmpleado.vue'
import TarjetasEmpleado from './views/Empleado/TarjetasEmpleado.vue'
import TransferenciasEmpleado from './views/Empleado/TransferenciasEmpleado.vue'
import MovimientosEmpleado from './views/Empleado/MovimientosEmpleado.vue'
import DetalleEmpleado from './views/Empleado/DetalleEmpleado.vue'
import EditForm from './views/Empleado/EditForm.vue'


import Login from './views/InicioCliente/Login.vue'
import InicioCliente  from './views/InicioCliente/InicioCliente.vue' 
import RegisterCliente from './views/InicioCliente/RegisterCliente.vue'
import TarjetasCliente from './views/TarjetasCliente/TarjetasCliente.vue'
import CuentasCliente from './views/CuentaCliente/CuentasCliente.vue'
import TransferenciasCliente from './views/TransferenciasCliente/TransferenciasCliente.vue'
import Contratar from './views/ContratarCliente/Contratar.vue'
import PerfilCliente from './views/PerfilCliente/PerfilCliente.vue'
import MiTarjeta from './views/TarjetasCliente/MiTarjeta.vue'


import LimitesTarjeta from './views/TarjetasCliente/LimitesTarjeta.vue'
import pinTarjeta from './views/TarjetasCliente/pinTarjeta.vue'
import detallesTarjeta from './views/TarjetasCliente/detallesTarjeta.vue'
import ContratosCliente from './views/ContratarCliente/ContratosCliente.vue'
import UpdateDNI from './views/PerfilCliente/UpdateDNI.vue'
import bajaCliente from './views/PerfilCliente/bajaCliente.vue'
import ConsultaTransferencias from './views/TransferenciasCliente/ConsultaTransferencias.vue'
import TraspasoTransferencia from './views/TransferenciasCliente/TraspasoCliente.vue'
import NuevaTarjeta from './views/ContratarCliente/NuevaTarjeta.vue'
import verCuenta from './views/CuentaCliente/verCuenta.vue'
import NuevaCuenta from './views/ContratarCliente/NuevaCuenta.vue'
import DetallesCuenta from './views/CuentaCliente/DetallesCuenta.vue'
import AhorroClientes from './views/CuentaCliente/AhorroClientes.vue'
import RecibosCliente from './views/CuentaCliente/RecibosCliente.vue'


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

  { path: '/inicio-cliente', component: InicioCliente},
  { path: '/tarjetas-cliente', component: TarjetasCliente},
  { path: '/transferencias-cliente', component: TransferenciasCliente},
  { path: '/contratar-cliente', component: Contratar},
  { path: '/perfil-cliente', component: PerfilCliente},
  { path: '/cuentas-cliente', component: CuentasCliente},
  { path: '/miTarjeta', component: MiTarjeta},
  { path: '/edit/:table/:id', component: EditForm },
  { path: '/limitesTarjeta', component: LimitesTarjeta },
  { path: '/consultarPIN', component: pinTarjeta },
  { path: '/detallesTarjeta', component: detallesTarjeta },
  { path: '/contratos-cliente', component: ContratosCliente },
  { path: '/updateDNI', component: UpdateDNI },
  { path: '/bajaCliente', component: bajaCliente },
  { path: '/consulta-Transferencias', component: ConsultaTransferencias },
  { path: '/traspaso', component: TraspasoTransferencia },
  { path: '/nuevaTarjeta', component: NuevaTarjeta },
  { path: '/verCuenta', component: verCuenta },
  { path: '/nuevaCuenta', component: NuevaCuenta },
  { path: '/detallesCuenta', component: DetallesCuenta },
  { path: '/ahorroClientes', component: AhorroClientes },
  { path: '/recibosCliente', component: RecibosCliente }

]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router
