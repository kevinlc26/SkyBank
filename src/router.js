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

import InicioCliente  from './views/InicioCliente.vue' 
import TarjetasCliente from './views/TarjetasCliente.vue'
import CuentasCliente from './views/CuentasCliente.vue'
import TransferenciasCliente from './views/TransferenciasCliente/TransferenciasCliente.vue'
import Contratar from './views/Contratar.vue'
import PerfilCliente from './views/PerfilCliente.vue'
import MiTarjeta from './views/MiTarjeta.vue'
import EditForm from './views/EditForm.vue'
import menuTarjeta from './components/menuTarjeta.vue'
import LimitesTarjeta from './views/LimitesTarjeta.vue'
import pinTarjeta from './views/pinTarjeta.vue'
import detallesTarjeta from './views/detallesTarjeta.vue'
import menuPerfil from './components/MenuPerfil.vue'
import ContratosCliente from './views/ContratosCliente.vue'
import UpdateDNI from './views/UpdateDNI.vue'
import bajaCliente from './views/bajaCliente.vue'
import MenuTransferencia from './components/MenuTransferencia.vue'
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
  { path: '/menuTarjeta', component: menuTarjeta },
  { path: '/limitesTarjeta', component: LimitesTarjeta },
  { path: '/consultarPIN', component: pinTarjeta },
  { path: '/detallesTarjeta', component: detallesTarjeta },
  { path: '/menuPerfil', component: menuPerfil },
  { path: '/contratos-cliente', component: ContratosCliente },
  { path: '/updateDNI', component: UpdateDNI },
  { path: '/bajaCliente', component: bajaCliente },
  { path: '/menuTransferencia', component: MenuTransferencia },
  { path: '/consulta-Transferencias', component: ConsultaTransferencias },
  { path: '/traspaso', component: TraspasoTransferencia },
  { path: '/altaTarjeta', component: NuevaTarjeta },
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
